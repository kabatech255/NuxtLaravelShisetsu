import { ErrorMessages } from '@/types/Error'

export type State = {
  messages: null | ErrorMessages
  status: null | number
}

const state = {
  messages: null,
  status: null,
}

const getters = {
  status: (state: any) => state.status,
  messages: (state: any) => state.messages || [],
}

const mutations = {
  setErrors(state: State, data: State) {
    state.messages = data.messages
    state.status = data.status
  },
}

const actions = {}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}
