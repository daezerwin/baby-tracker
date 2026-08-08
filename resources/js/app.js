import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.Chart = Chart;

document.addEventListener('alpine:init', () => {
    Alpine.store('clock', {
        now: new Date(),

        init() {
            setInterval(() => {
                this.now = new Date();
            }, 1000);
        },

        localDateTimeInput() {
            const d = this.now;
            const pad = (n) => String(n).padStart(2, '0');
            return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        },

        // Live "time ago" / "time from now" string for an ISO timestamp,
        // ticking every second via `now` above. Shows hours/minutes/seconds
        // under a day so the live update is visibly confirmable, rather than
        // collapsing to a single rounded unit like "2 hours ago".
        timeAgo(iso) {
            const diffSec = Math.round((new Date(iso) - this.now) / 1000);
            const abs = Math.abs(diffSec);
            const suffix = diffSec <= 0 ? 'ago' : 'from now';

            if (abs < 86400) {
                const h = Math.floor(abs / 3600);
                const m = Math.floor((abs % 3600) / 60);
                const s = abs % 60;
                const parts = [];
                if (h) parts.push(`${h} hour${h === 1 ? '' : 's'}`);
                if (h || m) parts.push(`${m} minute${m === 1 ? '' : 's'}`);
                parts.push(`${s} second${s === 1 ? '' : 's'}`);
                return `${parts.join(' ')} ${suffix}`;
            }

            const units = [['year', 31536000], ['month', 2592000], ['week', 604800], ['day', 86400]];
            for (const [name, secs] of units) {
                if (abs >= secs) {
                    const value = Math.round(abs / secs);
                    const label = value === 1 ? name : `${name}s`;
                    return `${value} ${label} ${suffix}`;
                }
            }
            return `${abs} seconds ${suffix}`;
        },
    });
});
