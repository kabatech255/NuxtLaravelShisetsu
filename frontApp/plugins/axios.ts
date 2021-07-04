export default function ({ $axios }: any) {
  $axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
}
