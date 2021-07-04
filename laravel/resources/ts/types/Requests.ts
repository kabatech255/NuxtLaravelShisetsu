export type UserStoreRequest = {
  name: string,
  email: string
  password: string,
  // eslint-disable-next-line camelcase
  password_confirmation: string,
}

export type UserSigninRequest = {
  email: string,
  password: string
}
