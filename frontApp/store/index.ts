export const actions = {
  async nuxtServerInit({ commit }: any, { app }: any): Promise<void> {
    await app.$axios
      .$get('/currentAuthor')
      .then((author: any): any => commit('auth/setAuthor', author))
      .catch(() => commit('auth/setAuthor', null))

    await app.$axios
      .$get('/currentAdmin')
      .then((admin: any): any => commit('admin/setAdmin', admin))
      .catch(() => commit('admin/setAdmin', null))
  },
}
