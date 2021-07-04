export type ValidateRule = (inputData: any) => boolean | string

export type TextField = {
  name: string
  label: string
  type: string
  isHidden?: boolean
  value: any
  placeholder?: string
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  options?: string[]
  prependIcon?: string
  appendIcon?: string
  class?: string[]
  [k: string]: any
}

export type TextareaField = {
  name: string
  label: string
  type: string
  value: any
  placeholder?: string
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  options?: string[]
  prependIcon?: string
  appendIcon?: string
  class?: string[]
  [k: string]: any
}

export type FileField = {
  name: string
  type: 'file'
  label: string
  value: any
  preview: any
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  accept: string
  options?: string[]
  prependIcon?: string
  appendIcon?: string
  [k: string]: any
}

export type FileFieldList = {
  name: string
  type: 'fileList'
  value: string
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  list: FileField[]
}

export type RadioField = {
  name: string
  label: string
  type: string
  isHidden?: boolean
  value: any
  keys: string[]
  list: any[]
  verticalFixed: boolean
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  options?: string[]
  prependIcon?: string
  appendIcon?: string
  loader?: boolean
  [k: string]: any
}
export type SelectField = {
  name: string
  label: string
  type: string
  value: any
  labelKey: string
  list: any[]
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  valueKey?: string
  options?: string[]
  prependIcon?: string
  appendIcon?: string
  loader?: boolean
  [k: string]: any
}

export type ModelConfig = {
  type: string
  mask: string
  [k: string]: any
}

export type DateItem = {
  name: string
  label: string
  value: any
  minDate: boolean
  [k: string]: any
}

export type DateField = {
  name: string
  type: string
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  modelConfig: ModelConfig
  list: DateItem[]
  updateFlag: number
  value: any
}

export type CheckField = {
  name: string
  label: string
  type: string
  value: any
  rules: ValidateRule[]
  errorMessages: (string | boolean)[]
  options?: string[]
  prependIcon?: string
  appendIcon?: string
  [k: string]: any
}

export type FormDataType = (
  | TextField
  | TextareaField
  | RadioField
  | SelectField
  | FileFieldList
)[]

export type FormInput =
  | TextField
  | TextareaField
  | RadioField
  | SelectField
  | FileFieldList
  | CheckField
  | DateField

export type FormObj = {
  [k: string]:
    | TextField
    | TextareaField
    | RadioField
    | SelectField
    | FileFieldList
    | CheckField
    | DateField
}

export type ExceptDateObj = {
  [k: string]:
    | TextField
    | TextareaField
    | RadioField
    | SelectField
    | FileFieldList
    | CheckField
}
