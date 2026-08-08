# Deploying Baby Tracker with Docker / Portainer

This app ships as a single container: Apache + PHP 8.4, SQLite for the
database, with the DB file and `storage/` (uploaded photos, logs, sessions)
persisted on two named Docker volumes so they survive restarts and image
rebuilds.

Every push of a `vX.Y.Z` tag builds and publishes an image to GitHub
Container Registry via `.github/workflows/docker-publish.yml` — so the
simplest deployment path is pulling that image directly, no build step on
your server at all.

## 1. Generate an APP_KEY

Laravel requires a stable encryption key. Generate one once — it's the same
key every time you redeploy, so save it somewhere safe (a password manager,
or Portainer's own environment variable store).

```bash
docker run --rm php:8.4-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

Copy the `base64:...` output — you'll paste it in as `APP_KEY` below.

## 2. Deploy

### Option A — Pull the prebuilt image (recommended, simplest)

No git checkout, no build step, nothing but Portainer needed.

> **First time only:** the image is published to
> `ghcr.io/daezerwin/baby-tracker`. GitHub Container Registry packages are
> **private by default** even on a public repo — go to the package's page
> on GitHub → **Package settings** → **Change visibility** → **Public**, or
> Portainer won't be able to pull it without registry credentials.

1. In Portainer, go to **Stacks → Add stack**, choose **Web editor**.
2. Paste in the contents of [`docker-compose.prod.yml`](docker-compose.prod.yml).
3. Under **Environment variables**, add:
   - `APP_KEY` = the `base64:...` value from step 1
   - `APP_URL` = e.g. `http://your-server-ip:8080`
4. Click **Deploy the stack** — Portainer pulls the image and starts it in
   seconds.

Prefer pinning to a specific release instead of always tracking `latest`?
Edit the `image:` line to `ghcr.io/daezerwin/baby-tracker:v1.0.0` (see the
repo's [Releases](https://github.com/daezerwin/baby-tracker/releases) page
for available tags).

### Option B — Build from source

Useful if you're modifying the app yourself rather than just running it.

1. In Portainer, go to **Stacks → Add stack**, choose **Repository**, and
   point it at this Git repo. Portainer clones it and builds
   [`Dockerfile`](Dockerfile) using [`docker-compose.yml`](docker-compose.yml)
   (no separate registry needed).
   - Rather not use a Git repo? Choose **Upload** and upload the whole
     project as a `.tar` — Portainer needs the `Dockerfile` and app source
     alongside `docker-compose.yml`, not just the compose file.
2. Add the same `APP_KEY` / `APP_URL` environment variables as above.
3. Deploy — first build takes a minute or two (npm install + composer
   install + asset build).

#### Alternative: SSH + Docker Compose CLI

If you'd rather build on the server yourself and let Portainer just manage
the resulting container:

```bash
git clone git@github.com:daezerwin/baby-tracker.git
cd baby-tracker
export APP_KEY="base64:...(from step 1)"
export APP_URL="http://your-server-ip:8080"
docker compose up -d --build
```

The stack will then show up in Portainer under **Containers**/**Stacks**
like any other Compose-managed deployment.

## 3. Exposing it publicly

Both compose files publish port `8080` on the host (`ports: 8080:80`). This
setup assumes you'll either hit that port directly or point your *own*
existing reverse proxy (Nginx Proxy Manager, Traefik, Caddy, etc.) at
`http://<server-ip>:8080`. Nothing extra is needed on this app's side for
that — just create the proxy host entry and point it at the container's
published port.

## 4. Email verification — read this before inviting anyone else

Routes in this app require a verified email address
(`routes/web.php` uses the `verified` middleware). By default the compose
file sets `MAIL_MAILER=log`, which just writes "sent" emails to the
container's log instead of actually delivering them — meaning **no one can
click a verification link** unless you configure real mail settings.

Pick one:

- **Configure real SMTP**: set `MAIL_MAILER=smtp` plus `MAIL_HOST`,
  `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` as environment variables
  (any provider works — Mailgun, SES, your own mail server, etc.).
- **Personal/family use, skip verification hassle**: manually mark your
  account verified after registering:
  ```bash
  docker exec -it <container-name> php artisan tinker --execute="
      \$u = App\Models\User::first();
      \$u->email_verified_at = now();
      \$u->save();
  "
  ```

## 5. Backing up your data

Everything that matters lives in two named volumes:

- `baby_tracker_database` — the SQLite database file
- `baby_tracker_storage` — uploaded baby photos, logs, cached sessions

```bash
docker run --rm -v baby_tracker_database:/data -v $(pwd):/backup alpine \
    tar czf /backup/baby-tracker-database-backup.tar.gz -C /data .
docker run --rm -v baby_tracker_storage:/data -v $(pwd):/backup alpine \
    tar czf /backup/baby-tracker-storage-backup.tar.gz -C /data .
```

Restore by reversing the tar direction into a fresh volume before starting
the container.

## 6. Updating to a new version

**Pulling (Option A):** in Portainer, open the stack and click **Pull and
redeploy** (or `docker compose pull && docker compose up -d` over SSH).

**Building (Option B):**

```bash
git pull
docker compose up -d --build
```

Database migrations run automatically on every container start (see
`docker/entrypoint.sh`) — they're idempotent, so this is safe on every
deploy either way.

### If you deployed before v1.0.2

v1.0.0/v1.0.1 mounted the database volume over `/var/www/html/database`,
which also holds the app's migration files — Docker only seeds a volume
from image content the *first* time it's empty, so once that volume existed
it permanently shadowed migrations from every subsequent image, leaving you
stuck with a database that looks migrated but is actually missing tables
(`no such table: sessions` and similar). v1.0.2 moves the data file to
`/var/lib/baby-tracker`, which has no code in it, so this can't recur.

If you hit this: pull v1.0.2+, then remove the old `baby_tracker_database`
volume (Portainer → Volumes, or `docker volume rm <stack>_baby_tracker_database`)
before redeploying — the old volume never had real tables in it, so there's
nothing worth keeping.
