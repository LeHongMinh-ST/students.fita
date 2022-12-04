import {GetterTree} from 'vuex'
import {
    accessTokenStudent,
    authUserStudent,
    isAuthenticatedStudent
} from '../../../@type'
import {RootState} from '../../index'
import {State} from './state'

export type Getters = {
    getIsAuthenticatedStudent(state: State): isAuthenticatedStudent,
    getAuthUserStudent(state: State): authUserStudent,
    getAccessTokenStudent(state: State): accessTokenStudent,
}

export const getters: GetterTree<State, RootState> & Getters = {
    getIsAuthenticatedStudent: (state) => state.isAuthenticatedStudent,
    getAuthUserStudent: (state) => state.authUserStudent,
    getAccessTokenStudent: (state) => state.accessTokenStudent
}
