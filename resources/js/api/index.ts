import axios from 'axios'
import router from '../router'
import {store} from '../store'

// @ts-ignore
const baseUrl = import.meta.env.VITE_ADMIN_URL;

export const apiAxios = axios.create({
    baseURL: `${baseUrl}/api`,
    headers: {
        "Content-type": "application/json",
    },
})

apiAxios.interceptors.request.use(config => {
    let token = store.state.auth.accessToken
    if (token) {
        config.headers.common['Authorization'] = `Bearer ${token}`
    }
    return config
}, error => {
    return Promise.reject(error)
})

apiAxios.interceptors.response.use(undefined, (error) => {
    if (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry && router.currentRoute.value.name !== 'Login') {
            store.commit('auth/updateLoginStatus', false)
            store.commit('auth/updateAuthUser', {})
            store.commit('auth/updateAccessToken', '')
            return router.push({name: 'Login'})
        }
    }
    return Promise.reject(error)
})

apiAxios.interceptors.response.use(undefined, error => {
    if (error) {
        if (error.response.status === 403) {
            if (
                error.response.data.error.error_permission &&
                error.response.data.error.error_permission.length > 0
            ) {
                return router.push({name: 'Home', params: {errorPermission: "true"}});
            }
        }
    }
    return Promise.reject(error)
})

export default {
    getAuthUser(): Promise<any> {
        return apiAxios({
            method: 'get',
            url: '/auth/me'
        })
    },
    login(data: any): Promise<any> {
        return apiAxios({
            method: 'post',
            url: '/auth/login',
            data: data
        })
    },
    getRedirectSocial(provider: string): Promise<any> {
        return apiAxios({
            method: 'get',
            url: `/auth/social/${provider}`
        })
    },
    loginSocialCallback(provider: string, payload: Object): Promise<any> {
        return apiAxios({
            method: 'post',
            url: `/auth/social/${provider}/callback`,
            params: payload
        })
    },
    register(data: any): Promise<any> {
        return apiAxios({
            method: 'post',
            url: '/auth/register',
            data: data
        })
    }
}