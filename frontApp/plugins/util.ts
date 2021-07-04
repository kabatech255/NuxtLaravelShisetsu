export const assetPath = 'https://asset.kensa-system.net'
export const primaryColor = '#38c172'
export const examNameByCode = {
  1: 'bousai',
  2: 'shokuhin',
  3: 'shomikigen',
}
export const colorList = {
  primary: '#38c172',
  bousai: '#ea6254',
  shokuhin: '#4dc0b5',
  shomikigen: '#ff9800',
}

export const OK_CODE = 200
export const CREATED = 201
export const DELETED = 204
export const UNAUTHORIZED = 401
export const NOT_FOUND = 404
export const UNPROCESSABLE_ENTITY = 422

/**
 * @param col
 * @param amt
 * amt = 20 -> 20%明るくする
 * amt = -20 -> 20%暗くする
 */
export const changeBrightness = (col: string, amt: number): string => {
  const sliced: string = col.slice(1)
  const num = parseInt(sliced, 16)
  const r = (num >> 16) + amt
  const b = ((num >> 8) & 0x00ff) + amt
  const g = (num & 0x0000ff) + amt
  const newColor = g | (b << 8) | (r << 16)
  return '#' + newColor.toString(16)
}

export const scheduleColorList = {
  blue: {
    code: '#4299e1',
    name: 'blue',
  },
  gray: {
    code: '#a0aec0',
    name: 'gray',
  },
  red: {
    code: '#f56565',
    name: 'red',
  },
  orange: {
    code: '#ed8936',
    name: 'orange',
  },
  yellow: {
    code: '#eac430',
    name: 'yellow',
  },
  green: {
    code: '#48bb78',
    name: 'green',
  },
  teal: {
    code: '#38b2ac',
    name: 'teal',
  },
  indigo: {
    code: '#667eea',
    name: 'indigo',
  },
  purple: {
    code: '#9f7aea',
    name: 'purple',
  },
  pink: {
    code: '#ed64a6',
    name: 'pink',
  },
}

export const formatedScheduleDate = (ms: number = 0) => {
  let newD = new Date()
  if (ms !== 0) {
    newD = new Date(ms)
  }
  const year = newD.getFullYear()
  const month = newD.getMonth() + 1
  const day = newD.getDate()
  const hour = newD.getHours()
  return `${year}/${month}/${day} ${hour}:00:00`
}

export const reformatedScheduleDate = (date: string | number) => {
  const newD = new Date(date)
  const year = newD.getFullYear()
  const month = newD.getMonth() + 1
  const day = newD.getDate()
  const hour = newD.getHours()
  const minute = `00${newD.getMinutes()}`.slice(-2)
  return `${year}/${month}/${day} ${hour}:${minute}`
}

export const localTitle = (left: string = '') => {
  if (left === '') {
    return 'コンプラ検査'
  } else {
    return `${left} | コンプラ検査`
  }
}
