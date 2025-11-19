import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    forceTLS: false,
    enabledTransports: ['ws'],
});

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log("Reverb Connected!");
});

window.Echo.channel('surat.status')
    .listen('.SuratStatusUpdated', (data) => {
        console.log("Realtime:", data);

        const el = document.getElementById(`status-${data.id_surat}`);
        if (el) {
            el.textContent = data.status_berkas;
        }
    });
