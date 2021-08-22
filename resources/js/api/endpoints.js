const BASE_URL = 'https://imes.pro/'
const V1 = BASE_URL + 'api/v1/'
//const V1 = BASE_URL + 'api/v1/'
const V1_ADMIN = BASE_URL + 'admin/api/v1/'
export const ARTICLE = V1 + 'blog'
export const TEST = V1 + 'tests'
export const PROJECT = V1 + 'project'
export const PROFILE = V1 + 'profile/'
export const MODERATION = V1 + 'moderation'
export const PROJECT_IMAGE = PROJECT + '/image/'
export const ANALYTIC = V1 + 'analytics'
export const NOTIFICATION = 'notification'
export const USER = V1 + 'users'
export const TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvZWNoby5teWZ0cC5vcmdcL2FwaVwvdjJcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNjE3NDc0NTMxLCJleHAiOjIyMTc0NzQ0NzEsIm5iZiI6MTYxNzQ3NDUzMSwianRpIjoibXptaWRwNVFDVnZGcndqWiIsInN1YiI6IjFvclZ4OHV2QzJucVJFN1BNS2c5S1lOZ0dHaSIsInBydiI6IjQxMWM5MTdhMGZiNTFlMGE0MjdhN2UzZGVhYTVhNDllMjkyZGRiOWIifQ.8eXEvSuykIXrbBpV_tf7LUBv8rLcUoTZdsQjiBXcDkk'

export const WITHDRAWAL = V1_ADMIN + 'withdraw';
export const WITHDRAWAL_CONFIRMATION = PROFILE + 'confirm'
export const WITHDRAWAL_DECLINE = PROFILE + 'decline'

export const NOTIFICATION_ALL = V1_ADMIN + NOTIFICATION + '/all'
export const NOTIFICATION_TO = V1_ADMIN + NOTIFICATION + '/to'

export const PROFILE_VERIFICATION_LIST = V1_ADMIN + 'profile/verification'
export const PROFILE_VERIFICATION = PROFILE_VERIFICATION_LIST + '/'
