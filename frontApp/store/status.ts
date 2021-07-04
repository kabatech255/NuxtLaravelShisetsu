import { UNAUTHORIZED } from '~/plugins/util'

export const state = () => ({
  statusCode: null,
  messages: null,
})

export const mutations = {
  setStatus(state: any, error: any) {
    state.statusCode = error.status
    state.messages = error.messages
  },
  clearStatus(state: any, val: null) {
    state.statusCode = val
    state.messages = val
  },
}

export const getters = {
  messages: (state: any): any => state.messages,
  status: (state: any): any => state.statusCode,
}

export const actions = {
  error({ commit }: any, err: any): void {
    commit('setStatus', 500, err)
  },

  errorHandler({ commit, dispatch }: any, err: any): void {
    commit('loader/setIsLoader', false, { root: true })
    if (err.response.status === UNAUTHORIZED) {
      dispatch('unAuthorizedHandler', err)
    } else {
      commit('setStatus', {
        status: err.response.status,
        messages: err.response.data.errors,
      })
    }
  },

  unAuthorizedHandler({ commit }: any, err: any) {
    commit('auth/setAuthor', null, { root: true })
    commit('setStatus', {
      status: err.response.status,
      messages: {
        unauthorized: [
          'ログインの有効期限が切れました。お手数ですが再度ログインしてください。',
        ],
      },
    })
  },
}
