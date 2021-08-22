/**
 * Base endpoints:
 */
export const BASE_URI = 'https://imes.pro/'
export const BASE_API_URI = BASE_URI + 'api/'
export const V1 = BASE_API_URI + 'v1/'

/**
 * Api resource endpoints:
 */
export const ARTICLE = V1 + 'blog'
export const TEST = V1 + 'tests'
export const PROJECT = V1 + 'project'
export const PROJECT_SUBMIT = V1 + 'projects/submit'
export const PROJECT_IMAGE = PROJECT + '/image/'
export const ANALYTIC = V1 + 'analytics'
export const USER = V1 + 'users'

/**
 * Auth endpoints:
 */
const USER_AUTH = 'api/v1/auth/user/'
export const LOGIN = USER_AUTH + 'login'
export const LOGOUT = USER_AUTH + 'logout'
export const PROFILE = USER_AUTH + 'profile'
export const REFRESH = USER_AUTH + 'refresh'

/**
 * Remote JWT token
 * todo:: remove the constant before production and replace all usage to localStorage.auth_token_default
 *
 * @type {string}
 */
export const TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2ltZXMucHJvL2FwaS92Mi9hdXRoL2xvZ2luIiwiaWF0IjoxNjE4NjkxNzkwLCJleHAiOjIyMTg2OTE3MzAsIm5iZiI6MTYxODY5MTc5MCwianRpIjoiVFhiQ3N4cnJNbDdhU2RjWCIsInN1YiI6ImFkbWluQGFkbWluLmFkbWluIiwicHJ2IjoiNDExYzkxN2EwZmI1MWUwYTQyN2E3ZTNkZWFhNWE0OWUyOTJkZGI5YiJ9.DkMMgskDXCj6AKK2vsTbiR6dDkC0CohNKpyPXauxLe'
