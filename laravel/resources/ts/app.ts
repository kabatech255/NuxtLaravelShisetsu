import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import bootstrap from './bootstrap'

bootstrap()

const createApp = async () => {
  await store.dispatch('auth/currentUser')
  new Vue({
    el: '#app',
    router,
    store,
    render: (h) => h(App),
  });
};

createApp();
