import { Middleware } from '@nuxt/types'
const disAdminCheck: Middleware = ({ store, redirect, route }: any): void => {
  if (!store.state.admin.admin) {
    redirect({
      name: 'admin-login',
      params: {
        before: route.fullPath,
      },
    })
  }
}

export default disAdminCheck
