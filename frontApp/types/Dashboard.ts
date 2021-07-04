import { Author } from '~/types/Author'

export type BasePanelTitle = {
  id: string
  value: string
  prependIcon: string
  appendIcon: string
  color: string
  isEdit: boolean
  [k: string]: any
}

export type BasePanelTitleList = {
  [k: string]: BasePanelTitle
}

export type Todo = {
  id: number
  employee_id: number
  body: string
  is_done: number
  validity: boolean
  iconName?: string
  style?: string
  [k: string]: any
}

export type TodoApi = {
  list: Todo[]
  keys: string[]
}

export type ScheduleRecord = {
  body: string
  color: string
  start: string
  end: string
  created_by: number
  shared_members: SharedMember[]
  editable_members: SharedMember[]
  [k: string]: any
}

export type Highlight = {
  color: string
  fillMode: string
}

export type Masks = {
  input: string
  title: string
  dayPopover: string
}

export type SharedMember = {
  employee_id: number
  name: string
  team_code: number
  edit_permission?: number
  [k: string]: any
}

export type CustomData = {
  id: number
  title: string
  body: string
  colorName: string
  startDisp: string
  endDisp: string
  shared_members: SharedMember[]
  editable_members: SharedMember[]
  can_edit: number
  is_private: number
  style: string
  isOpen: boolean
}
export type Dates = {
  start: string
  end: string
}
export type ScheduleObj = {
  key: number
  dates: Dates[]
  highlight?: Highlight
  customData: CustomData
  [k: string]: any
}

export type ScheduleData = {
  masks: Masks
  attributes: ScheduleObj[]
  [k: string]: any
}

export type OthersSchedule = {
  is_private: number
  body: string
  shared_members: Author
}
