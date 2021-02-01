import Vue from 'vue';
import axios from 'axios';
import localforage from 'localforage';
import VueObserveVisibility from 'vue-observe-visibility';
import * as GmapVue from 'gmap-vue';
import InstantSearch from 'vue-instantsearch';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faMapMarkerAlt } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import App from './App.vue';
import router from './router';
import store from './store';

library.add(faMapMarkerAlt);
Vue.component('font-awesome-icon', FontAwesomeIcon);

Vue.use(InstantSearch);
Vue.use(VueObserveVisibility);
Vue.use(GmapVue, {
  load: {
    key: process.env.VUE_APP_GOOGLE_PLACES_API_KEY,
    libraries: 'places',
  },
  installComponents: true,
});

localforage.config({
  driver: localforage.LOCALSTORAGE,
  name: 'weldapp',
});

require('./interceptors');

axios.defaults.baseURL = process.env.VUE_APP_API_URI;
axios.defaults.withCredentials = true;

Vue.config.productionTip = false;

store.dispatch('auth/user')
  .then(() => {
    new Vue({
      router,
      store,
      render: (h) => h(App),
    }).$mount('#app');
  });
