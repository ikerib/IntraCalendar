
// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('bootstrap-datepicker')
require("bootstrap-datepicker/dist/locales/bootstrap-datepicker.eu.min");
// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

$(document).ready(function() {

});


import Vue from 'vue'
import App from './vue/App'

new Vue({
    el: '#app',
    render(h) {
        return h(App);
    }
});