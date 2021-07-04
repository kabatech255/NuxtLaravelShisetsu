export type BranchObj = {
  branch_code: number
  name: string
  [k: string]: any
}
export type ShopObj = {
  branch: BranchObj
  store_code: number
  name: string
  file_name: string
  has_current_record?: boolean
  [k: string]: any
}
