require('./bootstrap');
require('alpinejs');

import Vue from "vue";
import Vuelidate from 'vuelidate';
import {ColorPicker, ColorPanel} from 'one-colorpicker'
import { BootstrapVue } from "bootstrap-vue";
import Antd from 'ant-design-vue';
import App from "./App.vue";
import router from "./router";
import store from "./store";
import { i18n } from "./i18n";
import { Trans } from "./plugins/Translation.js";
import Permissions from './mixins/Permissions.vue';
import Vuetify from 'vuetify';
import DaySpanVuetify from 'dayspan-vuetify';

import 'vuetify/dist/vuetify.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'dayspan-vuetify/dist/lib/dayspan-vuetify.min.css'
import 'ant-design-vue/dist/antd.css';

Vue.prototype.$i18nRoute = Trans.i18nRoute.bind(Trans);

Vue.use(Vuelidate);
Vue.use(ColorPanel);
Vue.use(ColorPicker);
Vue.use(Antd);
// Vue.use(VueInputMask);
Vue.use(BootstrapVue);
Vue.mixin(Permissions);
Vue.use(Vuetify);
Vue.use(DaySpanVuetify, {
    methods: {
        getDefaultEventColor: () => '#1976d2'
    }
});

new Vue({
    i18n,
    router,
    store,
    render: (h) => h(App),
}).$mount("#app");

