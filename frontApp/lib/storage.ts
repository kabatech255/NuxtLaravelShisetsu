export default class {
  key: string
  data: any

  constructor(key: string) {
    this.key = key
    this.data = []
  }

  init(): void {
    this.data = JSON.parse(<string>sessionStorage.getItem(this.key)) || []
  }

  save(): void {
    sessionStorage.setItem(this.key, JSON.stringify(this.data))
  }

  regist(payload: any): void {
    this.data.length = 0
    this.data.push(payload)
    this.save()
  }

  remove(): void {
    sessionStorage.removeItem(this.key)
  }
}
