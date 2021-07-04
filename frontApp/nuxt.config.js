const environment = process.env.NODE_ENV || 'development'
require('dotenv').config()

export default {
  // Global page headers (https://go.nuxtjs.dev/config-head)
  head: {
    title: 'コンプラ検査サイト',
    htmlAttrs: {
      lang: 'ja',
    },
    meta: [
      { charset: 'utf-8' },
      {
        name: 'viewport',
        content:
          'width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0',
      },
      { hid: 'description', name: 'description', content: '' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'stylesheet',
        href:
          'https://fonts.googleapis.com/icon?family=Material+Iconss%7CMaterial+Icons+Outlined',
      },
    ],
  },

  router: {
    extendRoutes(routes, resolve) {
      routes.push({
        name: 'not-found',
        path: '*',
        component: resolve('~/pages/error/404.vue'),
      })
    },
    scrollBehavior: (to, from, savedPosition) => ({
      x: 0,
      y: 0,
    }),
  },

  // Global CSS (https://go.nuxtjs.dev/config-css)
  css: [{ src: '~/assets/scss/style.scss', lang: 'scss' }],

  // Plugins to run before rendering page (https://go.nuxtjs.dev/config-plugins)
  plugins: [
    '~/plugins/axios',
    '~/plugins/vue-loaders',
    '~/plugins/util',
    { src: '~/plugins/coreui', mode: 'client' },
    { src: '~/plugins/v-calendar', ssr: false },
    // { src: '~/plugins/nuxt-client-init', mode: 'client' },
  ],

  // Auto import components (https://go.nuxtjs.dev/config-components)
  components: true,

  // Modules for dev and build (recommended) (https://go.nuxtjs.dev/config-modules)
  buildModules: [
    // https://go.nuxtjs.dev/typescript
    '@nuxt/typescript-build',
  ],

  // Modules (https://go.nuxtjs.dev/config-modules)
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/proxy',
    '@nuxtjs/dotenv',
  ],
  proxy: {
    '/api':
      environment === 'development'
        ? process.env.API_URL
        : 'https://exam.kensa-system.net',
  },

  // Axios module configuration (https://go.nuxtjs.dev/config-axios)
  axios: {
    baseURL: process.env.API_URL,
    browserBaseURL: process.env.API_BROWSER_URL,
    credentials: true,
  },

  // Build Configuration (https://go.nuxtjs.dev/config-build)
  build: {},
}
