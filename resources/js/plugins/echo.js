import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

let echoInstance = null;

/**
 * Initialize the Echo instance with the provided token.
 *
 * @param {string} token - The authentication token for the Echo instance.
 * @returns {Echo|null} - The initialized Echo instance or null if no token is provided.
 */
export function initEcho(token) {
    if (!token) {
        console.warn("Echo initialization failed: No token provided.");
        return null;
    }

    if (!echoInstance) {
        try {
            echoInstance = new Echo({
                broadcaster: "pusher",
                key: import.meta.env.VITE_PUSHER_APP_KEY,
                wsHost:
                    import.meta.env.VITE_PUSHER_HOST ||
                    window.location.hostname,
                wsPort: import.meta.env.VITE_PUSHER_PORT || 80,
                wssPort: import.meta.env.VITE_PUSHER_PORT || 443,
                forceTLS:
                    (import.meta.env.VITE_PUSHER_SCHEME || "https") === "https",
                enabledTransports: ["ws", "wss"],
                auth: {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                },
            });
        } catch (error) {
            console.error("Failed to initialize Echo:", error);
            return null;
        }
    }

    return echoInstance;
}
