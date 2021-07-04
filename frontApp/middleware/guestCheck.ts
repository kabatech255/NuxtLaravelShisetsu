import { Middleware } from '@nuxt/types'
const guestCheck: Middleware = ({ store, redirect, route }: any): void => {
  if (!store.state.auth.author) {
    redirect({
      name: 'login',
      params: {
        before: route.fullPath,
      },
    })
  }
}

export default guestCheck
