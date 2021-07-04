import Vue from 'vue'
import VueRouter from 'vue-router'

import store from '@/store'

// pages
import Top from '@/pages/Top'
import Signin from '@/pages/Signin'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: Top,
  },
  {
    path: '/signin',
    component: Signin,
  },
]

const router = new VueRouter({
  mode: 'history',
  routes,
})

export default router
