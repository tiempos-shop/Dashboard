/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


// main.js
import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';


import MediumEditor from 'vuejs-medium-editor';

import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueSweetalert2);

window.Vue = require('vue');


Vue.component('serviciosdhl', require('./components/Serviciosdhl.vue').default);
Vue.component('Productos', require('./components/Productos.vue').default);
Vue.component('Clientes', require('./components/Clientes.vue').default);
Vue.component('productosguardar', require('./components/ProductosGuardar.vue').default);
Vue.component('grafica', require('./components/Grafica.vue').default);
Vue.component('ventas', require('./components/ventas/Ventas.vue').default);
Vue.component('ventasdetalle', require('./components/ventas/VentasDetalle.vue').default);

Vue.component('configdiasdhl', require('./components/config/ConfigDiasDHL.vue').default);
Vue.component('menus', require('./components/menu/Menus.vue').default);

Vue.component('inicio', require('./components/inicio.vue').default);


Vue.component('medium-editor', MediumEditor);
Vue.component('editor', require('./components/editorhtml/editor.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.filter('moneda', function (value) {
    if (typeof value !== "number") {
        return value;
    }
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    });
    return formatter.format(value);
});

Vue.prototype.server = 'http://127.0.0.1:8000/';

const app = new Vue({
    el: '#app',
});
