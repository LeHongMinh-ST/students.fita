import {store} from "../store"
import {RouteRecordRaw, createRouter, createWebHistory} from 'vue-router'

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../pages/Home.vue'),
        meta: {isAuthenticated: true},
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../pages/Login.vue')
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../pages/Register.vue')
    },
    {
        path: '/authorize/google/callback',
        name: 'LoginGoogle',
        component: () => import('../pages/LoginGoogle.vue')
    }
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