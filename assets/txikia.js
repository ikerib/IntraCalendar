
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('bootstrap-datepicker')
require("bootstrap-datepicker/dist/locales/bootstrap-datepicker.eu.min");

const routes = require('../public/js/fos_js_routes.json');
import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
window.routing = Routing

import Vue from 'vue'
import App from './vue/txikia/App'

import axios from 'axios'
import VueAxios from 'vue-axios'
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.put['Content-Type'] = 'application/json';
axios.defaults.headers.get['Content-Type'] = 'application/json';
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.headers.delete['Content-Type'] = 'application/json';
Vue.prototype.$http = axios;
Vue.use(VueAxios, axios)

Vue.directive('links-in-new-window', {
    inserted: function(el) {
        const anchors = el.querySelectorAll('a')
        anchors.forEach((anchor) => anchor.target = "_blank")
    }
})

import VueLuxon from "vue-luxon";
Vue.use(VueLuxon, {
    input: {
        zone: "utc",
        format: "iso"
    },
    output: {
        zone: "local",
        format: "YYYY-MM-DD HH:mm:ss",
        locale: "eu",
    }
});

import moment from 'moment'

Vue.filter('formatDateToHour', function(value) {
    if (value) {
        return moment(String(value)).format('H:mm')
    }
});

new Vue({
    el: '#app',
    render(h) {
        return h(App);
    }
});
