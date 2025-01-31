import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configurar Axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Configurar Pusher
window.Pusher = Pusher;

// Obtener variables de entorno desde Vite
const PUSHER_APP_KEY = import.meta.env.VITE_PUSHER_APP_KEY || 'app-key';
const PUSHER_HOST = import.meta.env.VITE_PUSHER_HOST || '127.0.0.1';
const PUSHER_PORT = import.meta.env.VITE_PUSHER_PORT || 6001;
const PUSHER_SCHEME = import.meta.env.VITE_PUSHER_SCHEME || 'http';
const PUSHER_APP_CLUSTER = import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1';

// Configurar Laravel Echo con Soketi
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.PUSHER_APP_KEY, // Clave de Pusher
    wsHost: PUSHER_HOST, // Host de WebSocket
    wsPort: PUSHER_PORT, // Puerto de WebSocket
    wssPort: PUSHER_PORT, // Puerto seguro de WebSocket
    forceTLS: PUSHER_SCHEME === 'https', // Forzar TLS si el esquema es HTTPS
    disableStats: true, // Deshabilitar estadísticas
    cluster: PUSHER_APP_CLUSTER, // Cluster de Pusher (para compatibilidad)
    encrypted: false,
    enabledTransports: ['ws', 'wss'], // Habilitar WebSocket y WebSocket seguro
});

// Escuchar errores de conexión
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Error de conexión con WebSocket:', err);
});

// Suscribirse al canal 'ventas'
window.Echo.channel('ventas')
    .subscribed(() => console.log("✅ Suscrito al canal Ventas"))
    .listen('.VentasChannelSubscribed', (data) => {
        console.log('📩 Evento recibido:', data);
    });

console.log("🔄 Intentando conectar al WebSocket...");
