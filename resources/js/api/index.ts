import axios, {AxiosPromise} from 'axios';
import {IParams} from "../models/IParams";
import IResult from "../models/IResult";
import router from '../router';
import {store} from '../store';
import {AuthMutationTypes} from "../store/modules/auth/mutation-types";

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
        if (error.response.status === 401 && !originalRequest._retry && router.currentRoute.value.name !== 'Login') {
            store.commit(`auth/${AuthMutationTypes.SET_LOGIN_STATUS}`, false)
            store.commit(`auth/${AuthMutationTypes.SET_AUTH_USER}`, {})
            store.commit(`auth/${AuthMutationTypes.SET_ACCESS_TOKEN}`, '')
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
    getAuthUser<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/auth/me'
        })
    },
    login<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/auth/login',
            data: data
        })
    },
    getRedirectSocial<T>(provider: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/auth/social/${provider}`
        })
    },
    loginSocialCallback<T>(provider: string, payload: Object): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: `/auth/social/${provider}/callback`,
            params: payload
        })
    },
    register<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/auth/register',
            data: data
        })
    },

    //roles
    getRoles<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/roles',
            params: params
        })
    },
    getAllRoleId<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/roles/get-all-role-id',
        })
    },
    getRole<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/roles/${id}`
        })
    },
    getPermissionGroups<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/permissions/group'
        })
    },
    createRole<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/roles',
            data: data
        })
    },
    updateRole<T>(data: any, id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/roles/${id}`,
            data: data
        })
    },
    deleteRole<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/roles/${id}`,
        })
    },
    deleteSelected<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/roles/delete-selected`,
            data: data
        })
    },

    //departments
    getDepartments<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/departments',
            params: params
        })
    },
    createDepartment<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/departments',
            data: data
        })
    },
    getAllDepartment<T>(params: IParams): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/departments/all`,
            params: params
        })
    },
    getDepartment<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/departments/${id}`
        })
    },
    updateDepartment<T>(data: any, id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/departments/${id}`,
            data: data
        })
    },
    deleteDepartment<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/departments/${id}`,
        })
    },
    deleteDepartmentSelected<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/departments/delete-selected`,
            data: data
        })
    },
    getAllDepartmentId<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/departments/get-all-id',
        })
    },

    //users
    getUsers<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/users',
            params: params
        })
    },
    createUser<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/users',
            data: data
        })
    },
    getUser<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/users/${id}`
        })
    },
    updateUser<T>(data: any, id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/users/${id}`,
            data: data
        })
    },
    deleteUser<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/users/${id}`,
        })
    },
    deleteUserSelected<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/users/delete-selected`,
            data: data
        })
    },
    resetPassword<T>(id: number, data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            data: data,
            url: `/users/${id}/reset-password`
        })
    },
    getClasses<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/classes',
            params: params
        })
    },
    getAllClasses<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/classes/all',
            params: params
        })
    },


    createClass<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/classes',
            data: data
        })
    },

    getClass<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/classes/${id}`
        })
    },

    updateClass<T>(data: any, id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/classes/${id}`,
            data: data
        })
    },

    deleteClass<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/classes/${id}`,
        })
    },
    importStudentClass<T>(id: number, data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            data: data,
            url: `/classes/${id}/import-student`,
        })
    },
    addStudent<T>(data: any, id: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/classes/${id}/add-student`,
            data: data
        })
    },

    deleteSelectedClass<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/classes/delete-selected`,
            data: data
        })
    },

    getAllClassId<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/class/get-all-class-id',
        })
    },
    //
    getAllReport<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/reports',
            params: params
        })
    },
    getReport<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/reports/${id}`
        })
    },

    changeStatusReport<T>(data: any, id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/reports/${id}/change-status`,
            data: data
        })
    },

    deleteReport<T>(id: string): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/reports/${id}`,
        })
    },
    
    //AUTHOR: TRUONGTN
    /*begin */

    getAllStudent<T>(params: object = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/students',
            params: params
        })
    },

    getStudentById<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/students/${id}`
        })
    },

    updateStudent<T>(data: any, id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: `/students/${id}`,
            data: data
        })
    },

    createStudent<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            url: '/students',
            data: data
        })
    },

    deleteStudent<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/students/${id}`,
        })
    },

    resetStudentPassword<T>(id: number, data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            data: data,
            url: `/students/${id}/reset-password`
        })
    },

    // Api dashboard - TruongTN
    getDashboard<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/dashboard'
        })
    },

    getReports<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/reports'
        })
    },

    getRequest<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/request'
        })
    },
    /*end */

    updateLearningOutcome<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            url: `/students/update-learning-outcome/${id}`
        })
    },
    resetMyPassword<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            data: data,
            url: `/profile/reset-password`
        })
    },
    updateProfile<T>(data: FormData): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'post',
            data: data,
            url: `/profile`
        })
    },
    downloadExcelTemplate<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/classes/download/get-template-import-file',
            responseType: 'blob',
        })
    },
    countReportPending<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/reports/count-pending',
        })
    },
    countStudentRequest<T>(): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/students/request/count',
        })
    },
    getRequestStudent<T>(params: {} = {}): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: '/students/request',
            params
        })
    },
    deleteRequestSelected<T>(data: any): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/students/request/delete-selected`,
            data: data
        })
    },
    getRequestStudentDetail<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'get',
            url: `/students/request/${id}`
        })
    },
    deleteRequest<T>(id: number): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'delete',
            url: `/students/request/${id}`,
        })
    },
    changeStatusRequest<T>(id: number, data: object): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            data: data,
            url: `/students/request/${id}`
        })
    },
    changeStatusRequestSelect<T>(data: object): AxiosPromise<IResult<T>> {
        return apiAxios({
            method: 'put',
            data: data,
            url: `/students/request/selected`
        })
    },
}
