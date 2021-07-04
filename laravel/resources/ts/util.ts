export function getCookieValue(searchKey: string): string {
  if (typeof searchKey === 'undefined') {
    return ''
  }

  let val: string = ''

  document.cookie.split(';').forEach((cookie: any) => {
    const [key, value] = cookie.split('=')
    if (key === searchKey) {
      return (val = value)
    }
  })

  return val
}

export const OK = 200
export const CREATED = 201
export const DELETED = 204
export const INTERNAL_SERVER_ERROR = 500
export const UNPROCESSABLE_ENTITY = 422
export const UNAUTHORIZED = 401
export const NOT_FOUND = 404
export const BASE_STORAGE_URL = 'https://exam.kensa-system.net'
export const BASE_URL = window.location.origin
