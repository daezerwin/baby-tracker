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
        // ticking every second via `now` above. Shows a zero-padded
        // HH MM SS breakdown under a day so the live update is visibly
        // confirmable, rather than collapsing to a rounded unit like
        // "2 hours ago".
        timeAgo(iso) {
            const diffSec = Math.round((new Date(iso) - this.now) / 1000);
            const abs = Math.abs(diffSec);
            const suffix = diffSec <= 0 ? 'ago' : 'from now';

            if (abs < 86400) {
                const pad = (n) => String(n).padStart(2, '0');
                const h = Math.floor(abs / 3600);
                const m = Math.floor((abs % 3600) / 60);
                const s = abs % 60;
                return `${pad(h)}H ${pad(m)}M ${pad(s)}S ${suffix}`;
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
