import _ from "lodash";
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import IPermissionResult from "../models/IPermissionResult";
import { store } from "../store";

const routeAdmin: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'Home',
        component: () => import('../pages/Home.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: 'profile',
        name: 'Profile',
        component: () => import('../pages/Profile/ProfileIndex.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: '/ok/test',
        name: 'test',
        component: () => import('../pages/Register.vue')
    },
    {
        path: 'classes',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Classes',
                component: () => import('../pages/Classes/ClassesIndex.vue'),
                meta: {isAuthenticated: true, permission: 'class-index'},
            },
            {
                path: 'create',
                name: 'ClassesCreate',
                component: () => import('../pages/Classes/ClassesCreate.vue'),
                meta: {isAuthenticated: true,permission: 'class-create'},
            },
            {
                path: ':id/edit',
                name: 'ClassesUpdate',
                component: () => import('../pages/Classes/ClassesCreate.vue'),
                meta: {isAuthenticated: true, permission: 'class-update'},
            },
            {
                path: ':id',
                name: 'ClassesDetail',
                component: () => import('../pages/Classes/ClassesDetail.vue'),
                meta: {isAuthenticated: true, permission: 'class-index'},
            },
        ]
    },
    {
        path: 'report-student',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'ReportStudent',
                component: () => import('../pages/ReportStudent/ReportStudentIndex.vue'),
                meta: {isAuthenticated: true, permission: 'report-index'}
            },
            {
                path: 'create',
                name: 'ReportStudentCreate',
                component: () => import('../pages/ReportStudent/ReportStudentCreate.vue'),
                meta: {isAuthenticated: true, permission: 'report-create'},
            },
            {
                path: 'update/:id',
                name: 'ReportStudentUpdate',
                component: () => import('../pages/ReportStudent/ReportStudentUpdate.vue'),
                meta: {isAuthenticated: true, permission: 'report-update'},
            },
            {
                path: ':id',
                name: 'ReportStudentDetail',
                component: () => import('../pages/ReportStudent/ReportStudentDetail.vue'),
                meta: {isAuthenticated: true, permission: 'report-index'},
            },
        ]
    },
    {
        path: 'review-list',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'ReviewListIndex',
                component: () => import('../pages/ReviewList/ReviewListIndex.vue'),
                meta: {isAuthenticated: true, permission: 'student-update'},
            },
        ]
    },
    {
        path: 'departments',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Department',
                component: () => import('../pages/Department/DepartmentIndex.vue'),
                meta: {isAuthenticated: true, permission: 'department-index'},
            },
        ]
    },
    {
        path: 'users',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'User',
                component: () => import('../pages/User/UserIndex.vue'),
                meta: {isAuthenticated: true, permission: 'user-index'},
            },
            {
                path: ':id/edit',
                name: 'UserUpdate',
                component: () => import('../pages/User/UserCreate.vue'),
                meta: {isAuthenticated: true, permission: 'user-create'},
            },
            {
                path: 'create',
                name: 'UserCreate',
                component: () => import('../pages/User/UserCreate.vue'),
                meta: {isAuthenticated: true, permission: 'user-update'},
            }
        ]
    },
    {
        path: 'roles',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Role',
                component: () => import('../pages/Role/RoleIndex.vue'),
                meta: {isAuthenticated: true, permission: 'role-index'},
            },
            {
                path: ':id/edit',
                name: 'RoleUpdate',
                component: () => import('../pages/Role/RoleUpdate.vue'),
                meta: {isAuthenticated: true, permission: 'role-update'},
            },
            {
                path: 'create',
                name: 'RoleCreate',
                component: () => import('../pages/Role/RoleCreate.vue'),
                meta: {isAuthenticated: true, permission: 'role-create'},
            }
        ]
    },

]
const routeStudent: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'HomeStudent',
        component: () => import('../pages/StudentPage/HomeStudent.vue'),
        meta: {isAuthenticatedStudent: false},
    },
    {
        path: 'update-profile',
        name: 'StudentUpdateProfile',
        component: () => import('../pages/StudentPage/StudentUpdateProfile.vue'),
        meta: {isAuthenticatedStudent: false},
    },
    {
        path: 'class',
        name: 'StudentClass',
        component: () => import('../pages/StudentPage/StudentClass.vue'),
        meta: {isAuthenticatedStudent: false},
    },
    {
        path: 'update',
        name: 'StudentUpdateProfile',
        component: () => import('../pages/StudentPage/StudentUpdateProfile.vue'),
        meta: {isAuthenticatedStudent: false},
    },
    {
        path: 'reports',
        meta: {isAuthenticatedStudent: true},
        children: [
            {
                path: '',
                name: 'ReportStudent',
                component: () => import('../pages/ReportStudent/ReportStudentIndex.vue'),
                meta: {isAuthenticatedStudent: true, permission: 'report-index'}
            },
            {
                path: 'create',
                name: 'ReportStudentCreate',
                component: () => import('../pages/ReportStudent/ReportStudentCreate.vue'),
                meta: {isAuthenticatedStudent: true, permission: 'report-create'},
            },
            {
                path: 'update/:id',
                name: 'ReportStudentUpdate',
                component: () => import('../pages/ReportStudent/ReportStudentUpdate.vue'),
                meta: {isAuthenticatedStudent: true, permission: 'report-update'},
            },
            {
                path: ':id',
                name: 'ReportStudentDetail',
                component: () => import('../pages/ReportStudent/ReportStudentDetail.vue'),
                meta: {isAuthenticatedStudent: true, permission: 'report-index'},
            },
        ]
    },
]


const routes: Array<RouteRecordRaw> = [
    {
        path: '/admin',
        meta: {isAuthenticated: true},
        component: () => import('../layouts/AppLayout.vue'),
        children: routeAdmin,
    },
    {
        path: '/student',
        meta: {isAuthenticatedStudent: true},
        component: () => import('../layouts/StudentLayout.vue'),
        children: routeStudent,
    },
    {
        path: '/student/login',
        name: 'LoginStudent',
        component: () => import('../pages/StudentPage/LoginStudent.vue')
    },
    {
        path: '/admin/login',
        name: 'Login',
        component: () => import('../pages/Login.vue')
    },
    // {
    //     path: '/admin/register',
    //     name: 'Register',
    //     component: () => import('../pages/Register.vue')
    // },
    {
        path: '/authorize/google/callback',
        name: 'LoginGoogle',
        component: () => import('../pages/LoginGoogle.vue')
    },

    {
        path: '/error-403',
        name: 'Error403',
        component: () => import('../pages/Errors/Error403.vue'),
    },
    {
        path: '/:catchAll(.*)*',
        name: 'Error404',
        component: () => import('../pages/Errors/Error404.vue'),
    },
    {
        path: '/admin/test',
        name: 'Register',
        component: () => import('../pages/Register.vue')
    },
    {
        path: '/stu/login',
        name: 'StuLogin',
        component: () => import('../pages/SVLogin.vue')
    },
    {
        path: '/stu/view',
        name: 'StuView',
        component: () => import('../pages/TraCuuThongTinSV.vue')
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((route) => route.meta.isAuthenticated)) {
        if (store.state.auth.isAuthenticated) {
            if (to.name === 'Login') {
                next({name: 'Home'})
            }
            const permission = to.meta.permission
            // @ts-ignore
            if (!checkPermission(permission)) {
                next({name: 'Error403'})
            }
            next()
        }
        next({name: 'Login'})
    } else if (to.matched.some((route) => route.meta.isAuthenticatedStudent)) {
        if (store.state.authStudent.isAuthenticatedStudent) {
            if (to.name === 'LoginStudent') {
                next({name: 'HomeStudent'})
            }
            next()
        }
        next({name: 'LoginStudent'})
    } else {
        if (store.state.auth.isAuthenticated) {
            if (to.name === 'Login') {
                next({name: 'Home'})
            }
        }
        next()
    }
});

const checkPermission = (permission: string): boolean => {
    if (!permission) return true
    if (_.get(store.state.auth.authUser, 'is_super_admin', 0)) return true
    const permissions = _.get(store.state.auth.authUser, 'role.permissions', [])
    return permissions.some((item: IPermissionResult) => item.code === permission)
}
// @ts-ignore
export default router
