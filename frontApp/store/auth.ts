import { GetterTree, ActionTree, MutationTree } from 'vuex'
import { UNAUTHORIZED } from '~/plugins/util'

export const state = () => ({
  author: null,
  error: null,
})

export type RootState = ReturnType<typeof state>

export const mutations: MutationTree<RootState> = {
  setAuthor(state: any, author: any) {
    state.author = author
  },
}

export const getters: GetterTree<RootState, RootState> = {
  currentAuthor(state: any): any {
    return state.author
  },
  currentError(state: any): any {
    return state.error
  },
  isLogin(state: any): boolean {
    return !!state.author
  },
}

export const actions: ActionTree<RootState, RootState> = {
  async login(
    { commit, dispatch }: any,
    { login_id, password }: any
  ): Promise<void> {
    commit('loader/setIsLoader', true, { root: true })
    await this.$axios
      .$post('/login', { login_id, password })
      .then((response: any): void => {
        commit('status/clearStatus', null, { root: true })
        commit('setAuthor', response)
        commit('loader/setIsLoader', false, { root: true })
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  async logout({ commit, dispatch }: any): Promise<void> {
    commit('loader/setIsLoader', true, { root: true })
    await this.$axios
      .$post('/logout')
      .then(() => {
        commit('setAuthor', null)
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
        commit('setAuthor', response)
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
        `/examinator/${state.author.employee_id}/update_profile`,
        submitData,
        config
      )
      .then((examinator: any): void => {
        commit('loader/setIsLoader', false, { root: true })
        commit('setAuthor', examinator)
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
