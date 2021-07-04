export type ColumnLink = {
  name: string
  query: string
  params?: any
}
export type TableField = {
  label: string
  key: string
  sort_status?: string
  ellipsis?: boolean
  style?: string
  link?: ColumnLink
  [k: string]: any
}
