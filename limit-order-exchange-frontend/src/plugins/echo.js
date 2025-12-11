import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import {api} from '@/api/axios'
window.Pusher = Pusher
const axiosApi = api()
export const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_KEY,
    cluster: import.meta.env.VITE_PUSHER_CLUSTER,
    forceTLS: true,
    authEndpoint: `${import.meta.env.VITE_BASE_URL}/broadcasting/auth`,
    wsHost: `ws-${import.meta.env.VITE_PUSHER_CLUSTER}.pusher.com`,
    wsPort: 443,
    wssPort: 443,
    encrypted: true,
    enabledTransports: [ 'ws', 'wss' ],
    authorizer: (channel) => {
        return {
            authorize: (socketId, callback) => {
                axiosApi.post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                .then(response => {
                    callback(false, response.data);
                })
                .catch(error => {
                    callback(true, error);
                });
            }
        };
    },
})
