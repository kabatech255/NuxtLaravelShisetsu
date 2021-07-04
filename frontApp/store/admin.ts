import { GetterTree, ActionTree, MutationTree } from 'vuex'

export const state = () => ({
  admin: null,
  error: null,
})

export type RootState = ReturnType<typeof state>

export const mutations: MutationTree<RootState> = {
  setAdmin(state: any, admin: any) {
    state.admin = admin
  },
}

export const getters: GetterTree<RootState, RootState> = {
  currentAdmin(state: any): any {
    return state.admin
  },
  currentError(state: any): any {
    return state.error
  },
  isAdmin(state: any): boolean {
    return !!state.admin
  },
}

export const actions: ActionTree<RootState, RootState> = {
  async login(
    { commit, dispatch }: any,
    { login_id, password }: any
  ): Promise<void> {
    commit('loader/setIsLoader', true, { root: true })
    await this.$axios
      .$post('/admin/login', { login_id, password })
      .then((response: any): void => {
        commit('status/clearStatus', null, { root: true })
        commit('setAdmin', response)
        commit('loader/setIsLoader', false, { root: true })
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  async logout({ commit, dispatch }: any): Promise<void> {
    commit('loader/setIsLoader', true, { root: true })
    await this.$axios
      .$post('/admin/logout')
      .then(() => {
        commit('setAdmin', null)
        commit('status/clearStatus', null, { root: true })
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
    commit('loader/setIsLoader', false, { root: true })
  },
  async testLogin({ commit, dispatch }: any): Promise<void> {
    await this.$axios
      .$post('/testlogin', {
        data: 'willrewrite',
      })
      .then((response: any): void => {
        commit('status/clearStatus', null, { root: true })
        commit('setAdmin', response)
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  async updateProfile(
    { commit, state, dispatch }: any,
    submitData: any
  ): Promise<void> {
    commit('loader/setIsLoader', true, { root: true })
    const config: any = {
      headers: {
        'content-type': 'multipart/form-data',
      },
    }
    config.headers['X-HTTP-Method-Override'] = 'PUT'
    await this.$axios
      .$post(
        `/examinator/${state.admin.employee_id}/update_profile`,
        submitData,
        config
      )
      .then((examinator: any): void => {
        commit('loader/setIsLoader', false, { root: true })
        commit('setAdmin', examinator)
        commit(
          'status/setStatus',
          {
            status: 200,
            messages: {
              success: ['保存しました'],
            },
          },
          { root: true }
        )
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
}
