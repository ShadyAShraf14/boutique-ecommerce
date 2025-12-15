import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

// CSRF
const tokenMeta = document.querySelector('meta[name="csrf-token"]');
if (tokenMeta) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
}

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// مهم جدًا للتشخيص
Pusher.logToConsole = true;

const WS_HOST = import.meta.env.VITE_PUSHER_HOST || window.location.hostname;
const WS_PORT = Number(import.meta.env.VITE_PUSHER_PORT || 6001);

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',

  wsHost: WS_HOST,
  wsPort: WS_PORT,
  wssPort: WS_PORT,

  forceTLS: false,
  encrypted: false,
  disableStats: true,
  enabledTransports: ['ws'], // خليها ws فقط طالما local http

  authEndpoint: '/broadcasting/auth',
  auth: {
    headers: {
      'X-CSRF-TOKEN': tokenMeta ? tokenMeta.getAttribute('content') : '',
      'X-Requested-With': 'XMLHttpRequest',
    },
  },
});
