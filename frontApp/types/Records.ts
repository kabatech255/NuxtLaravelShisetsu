import { Exam } from '~/types/Exam'
import { MonthlyLogDetail } from '~/types/MonthlyLogDetail'
import { Examinator } from '~/types/Author'
import { ShopObj } from '~/types/ShopObj'

export type MonthlyLog = {
  exam_code: number
  examinator: Examinator
  year_month: string
  examined_year: number
  examined_month: number
  monthly_log_details: MonthlyLogDetail[]
  [k: string]: any
}

export type Record = {
  exam: Exam
  monthlyLogs: MonthlyLog[]
}

export type Records = {
  [k: number]: Record
}

export type ExamApi = {
  shop: ShopObj
  records: Records
}
