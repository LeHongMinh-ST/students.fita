import {GetterTree} from 'vuex'
import {isAuthenticated, authUser, accessToken, isAuthenticatedStudent} from '../../../@type'
import {RootState} from '../../index'
import {State} from './state'

export type Getters = {
    getIsAuthenticated(state: State): isAuthenticated,
    getAuthUser(state: State): authUser,
    getAccessToken(state: State): accessToken,
}

export const getters: GetterTree<State, RootState> & Getters = {
    getIsAuthenticated: (state) => state.isAuthenticated,
    getAuthUser: (state) => state.authUser,
    getAccessToken: (state) => state.accessToken
}
