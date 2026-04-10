import './bootstrap';

// ── Synth chime (Web Audio API — no audio file needed) ────────────────────────
function playChime(type) {
    try {
        const Ctx = window.AudioContext || window.webkitAudioContext;
        if (!Ctx) return;
        const ctx = new Ctx();
        const notes = {
            success: [
                { freq: 523.25, t: 0,    dur: 0.35, vol: 0.12 }, // C5
                { freq: 659.25, t: 0.12, dur: 0.35, vol: 0.10 }, // E5
            ],
            error: [
                { freq: 311.13, t: 0,    dur: 0.40, vol: 0.12 }, // Eb4
                { freq: 277.18, t: 0.10, dur: 0.40, vol: 0.10 }, // Db4
            ],
            warning: [
                { freq: 466.16, t: 0,    dur: 0.25, vol: 0.11 }, // Bb4
                { freq: 369.99, t: 0.15, dur: 0.30, vol: 0.09 }, // F#4
            ],
            info: [
                { freq: 440.00, t: 0,    dur: 0.30, vol: 0.10 }, // A4
            ],
        };
        (notes[type] || notes.info).forEach(({ freq, t, dur, vol }) => {
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = type === 'error' ? 'triangle' : 'sine';
            osc.frequency.value = freq;
            const at = ctx.currentTime + t;
            gain.gain.setValueAtTime(0, at);
            gain.gain.linearRampToValueAtTime(vol, at + 0.015);
            gain.gain.exponentialRampToValueAtTime(0.001, at + dur);
            osc.start(at);
            osc.stop(at + dur + 0.05);
        });
    } catch (_) { /* silently ignore if audio blocked */ }
}

// ── Alpine toast manager component ───────────────────────────────────────────
window.toastManager = function () {
    return {
        toasts: [],
        _id: 0,

        init() {
            // window.toast('type', 'message', 'title?') → CustomEvent → here
            window.addEventListener('toast', (e) => {
                if (!e.detail || !e.detail.message) return;
                const { type = 'info', message, title = null } = e.detail;
                this.push(type, message, title);
            });
        },

        push(type, message, title = null) {
            const id  = ++this._id;
            const ttl = type === 'error' ? 6000 : 4500;
            this.toasts.push({ id, type, message, title, show: true, progress: 100 });
            playChime(type);

            const step = 100 / (ttl / 50);
            const iv = setInterval(() => {
                const t = this.toasts.find(x => x.id === id);
                if (t) t.progress = Math.max(0, t.progress - step);
            }, 50);

            setTimeout(() => { clearInterval(iv); this.dismiss(id); }, ttl);
        },

        dismiss(id) {
            const t = this.toasts.find(x => x.id === id);
            if (t) t.show = false;
            setTimeout(() => { this.toasts = this.toasts.filter(x => x.id !== id); }, 350);
        },
    };
};

// Global helper ─ usable from any JS or inline onclick:
// window.toast('success', 'Profile saved!', 'Optional title')
window.toast = (type, message, title = null) => {
    window.dispatchEvent(new CustomEvent('toast', { detail: { type, message, title } }));
};
