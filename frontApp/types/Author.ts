export type User = {
  created_at: string
  deleted_at: string | null
  department_code: number
  email: string
  updated_at: string
  [k: string]: any
}
export type Examinator = {
  created_at: string
  deleted_at: string | null
  employee_id: number
  file_name: string | null
  id: number
  name: string
  team_code: number
  updated_at: string
  user?: User
  [k: string]: any
}

export type Admin = {
  created_at: string
  deleted_at: string | null
  email: string
  updated_at: string
  [k: string]: any
}
export type Employee = {
  created_at: string
  deleted_at: string | null
  employee_id: number
  file_name: string | null
  id: number
  name: string
  updated_at: string
  admin?: Admin
  [k: string]: any
}

export type Author = Examinator | Admin
