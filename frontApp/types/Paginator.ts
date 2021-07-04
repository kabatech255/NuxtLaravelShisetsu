export type Paginator = {
  current_page: number
  last_page: number
  from: number
  to: number
  total: number
  [k: string]: any
}

export type SortObj = {
  sortBy: string | null
  orderBy: string | null
}
