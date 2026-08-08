# Baby Tracker

A self-hosted Laravel app for tracking a baby's day-to-day care and growth —
feeds, diapers, sleep, weight, milestones, photos, and pediatrician info, all
in one mobile-friendly dashboard.

## Features

- **Baby profiles** — name, sex, date/time of birth, birth weight/length,
  blood type, notes, and a profile picture (click-to-upload, with automatic
  date detection from a photo's EXIF metadata when available)
- **Tracking** — weight, feeds (breast/bottle/solid, with oz amounts for
  bottles), diapers (pee/poop logged independently), and sleep, each with
  full CRUD and a timeline view
- **Dashboard** — live clock, last feed/diaper at a glance, one-tap quick-add
  for feeds and diapers, a photo slideshow, 7-day activity charts, and a
  growth chart comparing the baby's weight against a typical median for
  their age
- **CSV import** — bulk-import historical diaper (pee/poop) and bottle-feed
  logs from a spreadsheet
- **Parent's Guide** — age-appropriate tips (feeding, sleep, development,
  safety) and a milestone catalog, seeded with general pediatric reference
  content for 0–24 months
- **Pediatrician info** — clinic/doctor contact details and next appointment
- **Photo gallery** — upload, set a profile picture, lightbox viewing

## Tech stack

- Laravel 13, PHP 8.4
- Livewire + Volt for the dashboard's interactive pieces
- Tailwind CSS, Alpine.js, Chart.js
- SQLite (default) — no separate database server required

## Local development

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm run build   # or `npm run dev` while working on frontend
php artisan serve
```

Visit `http://localhost:8000`, register an account, and add your first baby.

## Running the tests

```bash
php artisan test
```

## Deploying with Docker / Portainer

See [README-DOCKER.md](README-DOCKER.md) for a full walkthrough — pulling
the prebuilt image, or building it yourself, and running it as a Portainer
stack with persistent storage for the database and uploaded photos.

## License

Released under the [MIT License](LICENSE.md).
