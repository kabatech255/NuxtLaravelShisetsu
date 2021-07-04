import { Middleware } from '@nuxt/types'

const loginCheck: Middleware = ({ store, redirect }: any): void => {
  if (store.state.auth.author) {
    redirect('/mypage')
  }
}

export default loginCheck
