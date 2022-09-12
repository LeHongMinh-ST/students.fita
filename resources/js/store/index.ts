import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import home from './modules/home'
import {store as auth, AuthStore, State as AuthState} from './modules/auth'

export type RootState = {
    auth: AuthState;
};

export type Store = AuthStore<Pick<RootState, 'auth'>>

export const store = createStore({
    modules: {
        home,
        auth
    },
    plugins: [createPersistedState({paths: ['auth']})]
})

export function useStore(): Store {
    return store as Store;
}