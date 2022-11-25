import {createRouter, createWebHistory, RouteRecordRaw} from 'vue-router'
import {store} from "../store"

const routeAdmin: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'Home',
        component: () => import('../pages/Home.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: 'students',
        children: [
            {
                path: '',
                name: 'StudentIndex',
                component: () => import('../pages/Student/StudentIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'StudentCreate',
                component: () => import('../pages/Student/StudentCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'update/:id',
                name: 'StudentUpdate',
                component: () => import('../pages/Student/StudentUpdate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: ':id',
                name: 'StudentDetail',
                component: () => import('../pages/Student/StudentDetail.vue'),
                meta: {isAuthenticated: true},
            },
        ],
        meta: {isAuthenticated: true},
    },
    {
        path: 'classes',
        meta: {isAuthenticated: true},
        children: [
            {
                path: '',
                name: 'Classes',
                component: () => import('../pages/Classes/ClassesIndex.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'ClassesCreate',
                component: () => import('../pages/Classes/ClassesCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'update/:id',
                name: 'ClassesUpdate',
                component: () => import('../pages/Classes/ClassesCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: '/:id',
                name: 'ClassesDetail',
                component: () => import('../pages/Classes/ClassesDetail.vue'),
                meta: {isAuthenticated: true},
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
                meta: {isAuthenticated: true},
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
                meta: {isAuthenticated: true},
            },
            {
                path: 'update/:id',
                name: 'UserUpdate',
                component: () => import('../pages/User/UserCreate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'UserCreate',
                component: () => import('../pages/User/UserCreate.vue'),
                meta: {isAuthenticated: true},
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
                meta: {isAuthenticated: true},
            },
            {
                path: 'update/:id',
                name: 'RoleUpdate',
                component: () => import('../pages/Role/RoleUpdate.vue'),
                meta: {isAuthenticated: true},
            },
            {
                path: 'create',
                name: 'RoleCreate',
                component: () => import('../pages/Role/RoleCreate.vue'),
                meta: {isAuthenticated: true},
            }
        ]
    },

]
const routeStudent: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'HomeStudent',
        component: () => import('../pages/StudentPage/HomeStudent.vue'),
        meta: {isAuthenticated: false},
    },
    {
        path: 'update-profile',
        name: 'StudentUpdateProfile',
        component: () => import('../pages/StudentPage/StudentUpdateProfile.vue'),
        meta: {isAuthenticated: false},
    },
    {
        path: 'class',
        name: 'StudentUpdateProdile',
        component: () => import('../pages/StudentPage/StudentUpdateProfile.vue'),
        meta: {isAuthenticated: false},
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
    {
        path: '/admin/register',
        name: 'Register',
        component: () => import('../pages/Register.vue')
    },
    {
        path: '/authorize/google/callback',
        name: 'LoginGoogle',
        component: () => import('../pages/LoginGoogle.vue')
    },
    {
        path: '/:catchAll(.*)*',
        name: 'Error404',
        component: () => import('../pages/Errors/Error404.vue'),
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
            next()
        }
        next({name: 'Login'})
    } else if (to.matched.some((route) => route.meta.isAuthenticatedStudent)) {
        if (store.state.authStudent.isAuthenticated) {
            if (to.name === 'LoginStudent') {
                next({name: 'HomeStudent'})
            }
            next()
        }
        next({name: 'LoginStudent'})
    } else {
        // if (store.state.auth.isAuthenticated) {
        //     if (to.name === 'Login') {
        //         next({name: 'Home'})
        //     }
        // }
        next()
    }
});


// @ts-ignore
export default router
