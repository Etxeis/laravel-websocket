import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configurar Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar Pusher
window.Pusher = Pusher;

// Configurar Laravel Echo con Soketi
console.log('Configurando Laravel Echo...');
console.log('Pusher App Key:', import.meta.env.VITE_PUSHER_APP_KEY);
console.log('Pusher Host:', import.meta.env.VITE_PUSHER_HOST);
console.log('Pusher Port:', import.meta.env.VITE_PUSHER_PORT);

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: import.meta.env.VITE_LARAVEL_WEBSOCKETS_HOST || window.location.hostname,
    wsPort: import.meta.env.VITE_LARAVEL_WEBSOCKETS_PORT || 6001,
    forceTLS: false,
    disableStats: true,
    authEndpoint: "http://127.0.0.1:8000/broadcasting/auth",
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
    },
});

console.log('Laravel Echo configurado:', window.Echo);
// Escuchar errores de conexión
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Error de conexión con WebSocket:', err);
});

// Suscribirse al canal 'ventas'
window.Echo.channel('ventas')
    .listen('.MessageSent', (data) => {
        console.log('Mensaje recibido:', data);
        const messagesList = document.getElementById('messagesList');
        const li = document.createElement('li');
        li.textContent = `${data.user.name}: ${data.message}`;
        messagesList.appendChild(li);
});

console.log("🔄 Intentando conectar al WebSocket...");
