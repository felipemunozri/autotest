window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

let tokenCsrf = document.head.querySelector('meta[name="csrf-token"]');
let url = document.head.querySelector('meta[name="url"]');
window.APP_URL = url.content;


if (tokenCsrf) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenCsrf.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/api/v1/';


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

let broadcastConfig = {
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    forceTLS: false,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
};

if(process.env.MIX_BROADCAST_DRIVER === 'custom_broadcasting') {
    delete broadcastConfig.cluster;
    broadcastConfig = {
        ...broadcastConfig,
        wsHost: process.env.MIX_BROADCASTING_HOST,
        wsPort: process.env.MIX_BROADCASTING_PORT,
        disableStats: true,
        enabledTransports: ['ws', 'wss']
    };

}

window.Echo = new Echo(broadcastConfig);