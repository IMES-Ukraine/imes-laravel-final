const BASE_URL = '/'

const V1_ADMIN = BASE_URL + 'admin/api/v1/'
export const ARTICLE = V1_ADMIN + 'blog'
export const ARTICLE_LIST = V1_ADMIN + 'blog/list'
export const TEST = V1_ADMIN + 'tests'
export const PROJECT = V1_ADMIN + 'project'
export const ADMIN_PROJECT = V1_ADMIN + 'project'
export const PROFILE = V1_ADMIN + 'profile/'
export const MODERATION = V1_ADMIN + 'moderation'
export const PROJECT_IMAGE = PROJECT + '/image/'
export const PROJECT_FILE = PROJECT + '/file/'
export const ARTICLE_COVER = PROJECT + '/cover'
export const ANALYTIC = V1_ADMIN + 'analytics'
export const NOTIFICATION = 'notification'
export const USER = V1_ADMIN + 'users'
export const USER_LIST = V1_ADMIN + 'users/list'
export const USER_PASSING = V1_ADMIN + 'users/passing'
export const USER_PASSING_TEST_ALL = V1_ADMIN + 'users/passing-test-all'
export const USER_PASSING_ARTICLE_ALL = V1_ADMIN + 'users/passing-article-all'
export const USER_PASSING_TEST = V1_ADMIN + 'users/passing-test'
export const TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvZWNoby5teWZ0cC5vcmdcL2FwaVwvdjJcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNjE3NDc0NTMxLCJleHAiOjIyMTc0NzQ0NzEsIm5iZiI6MTYxNzQ3NDUzMSwianRpIjoibXptaWRwNVFDVnZGcndqWiIsInN1YiI6IjFvclZ4OHV2QzJucVJFN1BNS2c5S1lOZ0dHaSIsInBydiI6IjQxMWM5MTdhMGZiNTFlMGE0MjdhN2UzZGVhYTVhNDllMjkyZGRiOWIifQ.8eXEvSuykIXrbBpV_tf7LUBv8rLcUoTZdsQjiBXcDkk'

export const WITHDRAWAL = V1_ADMIN + 'withdraw';
export const WITHDRAWAL_CONFIRMATION = PROFILE + 'confirm'
export const WITHDRAWAL_DECLINE = PROFILE + 'decline'

export const REQUEST = V1_ADMIN + 'request'

export const TAGS = PROJECT + '/tags'

export const CLIENTS = V1_ADMIN + 'clients'
export const CLIENTS_BLOCK_USER = USER + '/block'
export const CLIENTS_UNBLOCK_USER = USER + '/unblock'
export const CLIENTS_DELETE_USER = USER + '/destroy'
export const SEARCH_USER = USER + '/search'
export const CLIENT_CHANGE_BALANCE = USER + '/balance'
export const USER_CREATE_NAME = USER + '/create-name'

export const REQUEST_CONFIRM = PROFILE + 'confirm-request'
export const REQUEST_DECLINE = PROFILE + 'decline-request'

export const NOTIFICATION_ALL = V1_ADMIN + NOTIFICATION + '/all'
export const NOTIFICATION_TO = V1_ADMIN + NOTIFICATION + '/to'

export const PROFILE_VERIFICATION_LIST = V1_ADMIN + 'profile/verification'
export const PROFILE_VERIFICATION = PROFILE_VERIFICATION_LIST + '/'
export const PROFILE_ACCEPT_VERIFICATION = PROFILE_VERIFICATION + 'accept'
export const PROFILE_DECLINE_VERIFICATION = PROFILE_VERIFICATION + 'decline'

export const CARDS = V1_ADMIN + 'cards'
export const CARD_DISABLE = CARDS + '/disable'
export const CARD_ENABLE = CARDS + '/enable'

export const BANNERS = V1_ADMIN + 'banners'

export const PROJECT_DESTROY = V1_ADMIN + 'project/destroy/'
export const PROJECT_START = V1_ADMIN + 'project/start/'
export const PROJECT_STOP = V1_ADMIN + 'project/stop/'

export const ARTICLE_DESTROY = ARTICLE + '/destroy/'
export const ARTICLE_TAGS = ARTICLE + '/tags'
export const ARTICLE_TIMES = ARTICLE + '/times'
export const ARTICLE_UPDATE = ARTICLE + '/update'

export const TEST_CONFIRMATION = TEST + '/accept'
export const TEST_COMPLEX_CONFIRMATION = TEST + '/accept-complex'
export const TEST_DECLINE = TEST + '/decline'

