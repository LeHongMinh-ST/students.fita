import axios, { AxiosPromise } from 'axios';
import IResult from "../models/IResult";
import router from '../router';
import { store } from '../store';
import { AuthStudentMutationTypes } from "../store/modules/auth_student/mutation-types";

// @ts-ignore
const baseUrl = import.meta.env.VITE_ADMIN_URL;

export const apiAxios = axios.create({
    baseURL: `${baseUrl}/api`,
    headers: {
        "Content-type": "application/json",
    },
})

apiAxios.interceptors.request.use(config => {
    let token = store.state.authStudent.accessTokenStudent
    if (token) {
        // @ts-ignore
        config.headers.common['Authorization'] = `Bearer ${token}`
    }
    return config
}, error => {
    return Promise.reject(error)
})

apiAxios.interceptors.response.use(undefined, (error) => {
    if (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry && router.currentRoute.value.name !== 'LoginStudent') {
            store.commit(`authStudent/${AuthStudentMutationTypes.SET_LOGIN_STATUS_STUDENT}`, false)
            store.commit(`authStudent/${AuthStudentMutationTypes.SET_AUTH_USER_STUDENT}`, {})
            store.commit(`authStudent/${AuthStudentMutationTypes.SET_ACCESS_TOKEN_STUDENT}`, '')
            return router.push({name: 'LoginStudent'})
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
                return router.push({name: 'HomeStudent', params: {errorPermission: "true"}});
            }
        }
    }
    return Promise.reject(error)
})

export default {
    loginStudent<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/student/auth/login',
            data: data
        })
    },
    getAuthUserStudent<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/student/auth/me'
        })
    },
    updateLearningOutcome<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/student/profile/update-learning-outcome/${id}`
        })
    },
    getClassUserCurrent<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/student/class',
            params: params
        })
    },

	getReportStudent<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/student/report/${id}`
        })
    },

    getAllReportStudent<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/student/report',
            params: params
        })
    },

    createStudentReport<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/student/report',
            data: data
        })
    },
}
