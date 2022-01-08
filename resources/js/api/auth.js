/**
 * Authentication configuration, some of the options can be override in method calls
 */
const config = {
    tokenDefaultName: 'laravel-jwt-auth',
    tokenStore: ['localStorage'],
    registerData: {
        url: 'auth/user/register',
        method: 'POST',
        redirect: '/login'
    },
    loginData: {
        url: 'auth/user/login',
        method: 'POST',
        redirect: '',
        fetchUser: true
    },
    logoutData: {
        url: 'auth/user/logout',
        method: 'POST',
        redirect: '/login',
        makeRequest: true
    },
    fetchData: {
        url: 'auth/user/profile',
        method: 'GET',
        enabled: true
    },
    refreshData: {
        url: 'auth/user/refresh',
        method: 'GET',
        enabled: true,
        interval: 30
    }
};
export default config
