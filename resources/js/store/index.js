import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import home from './modules/home'
import auth from './modules/auth'

const store = createStore({
    modules: {
        home,
        auth
    },
    plugins: [createPersistedState({paths: ['auth']})]
})

export default store
