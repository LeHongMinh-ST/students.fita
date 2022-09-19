import {createStore} from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import {store as auth, AuthStore, State as AuthState} from './modules/auth'
import {store as home, HomeStore, State as HomeState} from './modules/home'

export type RootState = {
    auth: AuthState,
    home: HomeState,
}

export type Store = AuthStore<Pick<RootState, 'auth'>>
    & HomeStore<Pick<RootState, 'home'>>

export const store = createStore({
    modules: {
        home,
        auth
    },
    plugins: [createPersistedState({paths: ['auth']})]
})

export function useStore(): Store {
    return store as Store
}