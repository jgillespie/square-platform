try {
    window.$ = window.jQuery = require('jquery');
    window.Popper = require('popper.js').default;

    require('bootstrap');
    require('jquery.easing');
    require('startbootstrap-sb-admin-2/js/sb-admin-2');
    require('datatables.net-bs4');
    require('daterangepicker');
} catch (e) { }

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
