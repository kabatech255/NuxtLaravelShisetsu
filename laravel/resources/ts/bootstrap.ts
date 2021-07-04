import Axios, { AxiosStatic } from 'axios'
import { getCookieValue } from '@/util'

declare global {
  interface Window {
    axios: AxiosStatic
  }
  interface Element {
    content: string
  }
}

export default function bootstrap() {
  window.axios = Axios

  // window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

  // window.axios.interceptors.request.use((config: any) => {
  //   // クッキーからトークンを取り出してヘッダーに添付する
  //   config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')
  //
  //   return config
  // })
}
