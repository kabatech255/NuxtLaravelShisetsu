import { GetterTree, ActionTree, MutationTree } from 'vuex'

export const state = () => ({
  isLoading: false,
  isWaiting: true,
})

export type RootState = ReturnType<typeof state>

export const mutations: MutationTree<RootState> = {
  setIsLoader(state: any, bool: boolean) {
    state.isLoading = bool
  },
  setWaiting(state: any, bool: boolean) {
    state.isWaiting = bool
  },
}

export const getters: GetterTree<RootState, RootState> = {
  loadCheck(state: any): any {
    return state.isLoading
  },
  waitingCheck(state: any): any {
    return state.isWaiting
  },
}

export const actions: ActionTree<RootState, RootState> = {}
