import {store} from "../store"
import {RouteRecordRaw, createRouter, createWebHistory} from 'vue-router'

const routeAdmin: Array<RouteRecordRaw> = [
    {
        path: '',
        name: 'Home',
        component: () => import('../pages/Home.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: 'students',
        name: 'Student',
        component: () => import('../pages/Student/StudentIndex.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: 'classes',
        name: 'Classes',
        component: () => import('../pages/Classes/ClassesIndex.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: 'users',
        name: 'User',
        component: () => import('../pages/User/UserIndex.vue'),
        meta: {isAuthenticated: true},
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
    }
]

const routes: Array<RouteRecordRaw> = [
    {
        path: '/admin',
        meta: {isAuthenticated: true},
        component: () => import('../layouts/AppLayout.vue'),
        children: routeAdmin,
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
    } else {
        if (store.state.auth.isAuthenticated) {
            if (to.name === 'Login') {
                next({name: 'Home'})
            }
        }
        next()
    }
});


export default router