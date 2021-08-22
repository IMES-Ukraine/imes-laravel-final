/**
 * Authentication configuration, some of the options can be override in method calls
 */
import {LOGIN, LOGOUT, PROFILE, REFRESH} from "./endpoints";

const config = {
    tokenDefaultName: 'laravel-jwt-auth',
    tokenStore: ['localStorage'],
    loginData: {
        url: LOGIN,
        method: 'POST',
        redirect: '',
        fetchUser: true
    },
    logoutData: {
        url: LOGOUT,
        method: 'POST',
        redirect: '/login',
        makeRequest: true
    },
    fetchData: {
        url: PROFILE,
        method: 'GET',
        enabled: true
    },
    refreshData: {
        url: REFRESH,
        method: 'GET',
        enabled: true,
        interval: 30
    }
};
export default config
