import { Middleware } from '@nuxt/types'

const adminCheck: Middleware = ({ store, redirect, route }: any): void => {
  if (!store.state.admin.admin) {
    redirect({
      name: 'admin-login',
      params: {
        before: route.fullPath,
      },
    })
  } else if (route.name === 'admin-login' || route.name === 'admin') {
    redirect({
      name: 'admin-mypage',
    })
  }
}

export default adminCheck
