export type Exam = {
  exam_code: number
  name: string
  color: string
  file_name: string
  risk_rank_id: number
  exam_issues: ExamIssue[]
  [k: string]: any
}
export type ExamIssue = {
  id: number
  exam_code: number
  name: string
  judgement_base: string
  exam_issue_details: ExamIssueDetail[]
  [k: string]: any
}
export type ExamIssueDetail = {
  id: number
  exam_issue_id: number
  issue_content: string
  created_by: number
  [k: string]: any
}
