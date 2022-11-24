import {createStore} from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import {store as auth, AuthStore, State as AuthState} from './modules/auth'
import {store as home, HomeStore, State as HomeState} from './modules/home'
import {store as authStudent, AuthStudentStore, State as AuthStudentState} from './modules/auth_student'

export type RootState = {
    auth: AuthState,
    home: HomeState,
    authStudent: AuthStudentState
}

export type Store = AuthStore<Pick<RootState, 'auth'>>
    & HomeStore<Pick<RootState, 'home'>>
    &AuthStudentStore<Pick<RootState, 'authStudent'>>

export const store = createStore({
    modules: {
        home,
        auth,
        authStudent
    },
    plugins: [createPersistedState({paths: ['auth']})]
})

export function useStore(): Store {
    return store as Store
}
