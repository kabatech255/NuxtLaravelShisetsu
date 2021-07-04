import { Middleware } from '@nuxt/types'

const examApiCheck: Middleware = ({ query, redirect }: any): void => {
  if (query.store_code === undefined) {
    redirect('/mypage/exam/select_shop')
  }
}

export default examApiCheck
