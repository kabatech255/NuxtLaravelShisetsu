import { GetterTree, ActionTree, MutationTree } from 'vuex'
import SessionStorage from '~/lib/storage'
import { Exam } from '~/types/Exam'

export const storageLogs = new SessionStorage('examApi')

export const state = () => ({
  examApi: null,
  error: null,
  currentExamCode: 1,
  currentMonthlyLogIndex: 0,
  currentIssueId: 1,
  orderBy: ['id', 'desc'],
})

export type RootState = ReturnType<typeof state>

export const mutations: MutationTree<RootState> = {
  setExamApi(state: any, logs: any) {
    state.examApi = logs
  },
  setError(state: any, error: any) {
    state.error = error
  },
  setCurrentMonthlyLogIndex(state: any, val: number) {
    state.currentMonthlyLogIndex = state.currentMonthlyLogIndex + val
  },
  setCurrentExamCode(state: any, examCode: number) {
    state.currentExamCode = examCode
  },
  sortMonthlyLogDetails(state: any): void {
    const index: any = Object.keys(state.examApi.records).find(
      (key: any): boolean =>
        state.examApi.records[key].exam.exam_code === state.currentExamCode
    )
    if (
      state.examApi.records[index].monthlyLogs[state.currentMonthlyLogIndex]
        .monthly_log_details.length > 0
    ) {
      state.examApi.records[index].monthlyLogs[
        state.currentMonthlyLogIndex
      ].monthly_log_details.sort((prev: any, next: any) => {
        if (state.orderBy[1] === 'asc') {
          return prev[state.orderBy[0]] - next[state.orderBy[0]]
        } else {
          return next[state.orderBy[0]] - prev[state.orderBy[0]]
        }
      })
    }
  },
  setOrderBy(state: any, orderBy: string[]): void {
    state.orderBy = orderBy
  },
}

export const getters: GetterTree<RootState, RootState> = {
  currentExamApi(state: any): any {
    return state.examApi
  },
  examList(state: any): any {
    return Object.keys(state.examApi.records).map(
      (key: any): Exam => state.examApi.records[key].exam
    )
  },
  isUnFetch(state: any): boolean {
    return state.examApi === undefined
  },
  currentShop(state: any): any {
    return state.examApi.shop
  },
  currentExam(state: any): any {
    if (state.examApi === null) {
      return null
    }
    const index: any = Object.keys(state.examApi.records).find(
      (key: any): boolean =>
        state.examApi.records[key].exam.exam_code === state.currentExamCode
    )
    return state.examApi.records[index].exam
  },
  currentMonthlyLogs(state: any): any {
    if (state.examApi === null) {
      return null
    }
    const index: any = Object.keys(state.examApi.records).find(
      (key: any): boolean =>
        state.examApi.records[key].exam.exam_code === state.currentExamCode
    )
    return state.examApi.records[index].monthlyLogs
  },
  currentMonthlyLogIndex(state: any): any {
    return state.currentMonthlyLogIndex
  },
}

export const actions: ActionTree<RootState, RootState> = {
  moveMonth({ commit }: any, add: number): void {
    commit('setCurrentMonthlyLogIndex', add)
    commit('sortMonthlyLogDetails')
  },
  moveExam({ commit }: any, examCode: number): void {
    commit('setCurrentExamCode', examCode)
    commit('sortMonthlyLogDetails')
  },
  async startExam({ commit, dispatch }: any, submitData: any): Promise<void> {
    commit('loader/setIsLoader', true, { root: true })
    await this.$axios
      .$post('/monthly_logs', submitData)
      .then((response: any): any => {
        storageLogs.regist(response)
        commit('loader/setIsLoader', false, { root: true })
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  async storeDetail({ commit, dispatch }: any, args: any): Promise<any> {
    args.config = {
      headers: {
        'content-type': 'multipart/form-data',
      },
    }
    if (args.path.match('.*update')) {
      args.config.headers['X-HTTP-Method-Override'] = 'PUT'
    }
    return await this.$axios
      .$post(args.path, args.submitData, args.config)
      .then((response: any): object => {
        storageLogs.regist(response)
        commit('setExamApi', storageLogs.data[0])
        dispatch('sort')
        if (args.path.match('.*update')) {
          args.body = {
            status: 200,
            messages: {
              success: ['更新しました'],
            },
          }
        } else {
          args.body = {
            status: 201,
            messages: {
              success: ['追加しました'],
            },
          }
        }
        commit('status/setStatus', args.body, { root: true })
        return {
          isError: false,
        }
      })
      .catch((err: any): object => {
        dispatch('status/errorHandler', err, { root: true })
        return {
          isError: true,
        }
      })
  },
  async deleteDetail({ commit, dispatch }: any, path: string): Promise<void> {
    const config: any = {
      headers: {
        'content-type': 'multipart/form-data',
      },
    }
    config.headers['X-HTTP-Method-Override'] = 'DELETE'
    await this.$axios
      .$post(path, {}, config)
      .then((response: any) => {
        storageLogs.regist(response)
        commit('setExamApi', storageLogs.data[0])
        dispatch('sort')
        commit(
          'status/setStatus',
          {
            status: 204,
            messages: {
              success: ['削除しました'],
            },
          },
          { root: true }
        )
      })
      .catch((err: any) => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  async complete(
    { commit, dispatch }: any,
    monthlyLogId: number
  ): Promise<void> {
    const config: any = {
      headers: {
        'content-type': 'multipart/form-data',
      },
    }
    config.headers['X-HTTP-Method-Override'] = 'PUT'
    await this.$axios
      .$post(`/monthly_logs/${monthlyLogId}/complete`, {}, config)
      .then((response: any): any => {
        storageLogs.regist(response)
        commit('setExamApi', storageLogs.data[0])
        dispatch('sort')
        commit(
          'status/setStatus',
          {
            status: 200,
            messages: {
              success: ['検査完了しました'],
            },
          },
          { root: true }
        )
        return {
          message: '検査完了しました',
          isError: true,
        }
      })
      .catch((err: any) => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  fetchExamApi({ state }: any) {
    storageLogs.regist(state.examApi)
  },
  async getExamApi({ commit, dispatch }: any, storeCode: number | string) {
    await this.$axios
      .$get(`/monthly_logs/${storeCode}`)
      .then((response: any): void => {
        commit('setExamApi', response)
        dispatch('sort')
      })
      .catch((err: any): void => {
        dispatch('status/errorHandler', err, { root: true })
      })
  },
  sort({ commit }: any) {
    commit('sortMonthlyLogDetails')
  },
  order({ commit, dispatch }: any, orderBy: string[]) {
    commit('setOrderBy', orderBy)
    dispatch('sort')
  },
}
