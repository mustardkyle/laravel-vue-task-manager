import axios from "axios";

// Set axios as a global instance
window.axios = axios;

// Set default headers
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Add a comment placeholder for Echo setup if needed in the future
/**
 * If you plan to use Laravel Echo for real-time event broadcasting,
 * you can initialize it here. Uncomment and configure the following:
 *
 * import Echo from 'laravel-echo';
 * import Pusher from 'pusher-js';
 *
 * window.Pusher = Pusher;
 * window.Echo = new Echo({
 *     broadcaster: 'pusher',
 *     key: import.meta.env.VITE_PUSHER_APP_KEY,
 *     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
 *     forceTLS: true,
 * });
 */
