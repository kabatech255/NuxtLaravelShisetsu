export default ({ store }: any): void => {
  Object.keys(store._actions).forEach((val) => {
    if (val.match(/nuxtClientInit/)) {
      store.dispatch(val)
    }
  })
}
