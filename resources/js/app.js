import './bootstrap';
import Vue from 'vue';
window.Vue = Vue;

import vSelect from 'vue-select';
import ProductForm from './components/ProductForm.vue';
import OrderComponent from './components/OrderComponent.vue';
import ProductDetails from './components/ProductDetails.vue'; // Import the new component
import CartComponent from './components/CartComponent.vue'; // Import the new component
import 'vue-select/dist/vue-select.css';

import { instance } from './instance';
Vue.prototype.$instance = instance;

import toastr from 'toastr';
import 'toastr/toastr.scss';

Vue.prototype.$toastr = toastr;

Vue.component('cart-component', CartComponent);
Vue.component('product-form', ProductForm);
Vue.component('order-component', OrderComponent);
Vue.component('product-details', ProductDetails); // Register the new component
Vue.component('v-select', vSelect);

const app = new Vue({
    el:'#app'
});
