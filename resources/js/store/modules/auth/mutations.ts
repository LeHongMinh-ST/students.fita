import { MutationTree } from 'vuex'

import {isAuthenticated, authUser, accessToken} from '../../../@type'

import { State } from './state'

import {AuthMutationTypes as MutationTypes} from './mutation-types'

export type Mutations<S = State> = {
    [MutationTypes.SET_LOGIN_STATUS](state: S, payload: isAuthenticated): void
    [MutationTypes.SET_AUTH_USER](state: S, payload: authUser): void
    [MutationTypes.SET_ACCESS_TOKEN](state: S, payload: accessToken): void
}

export const  mutations: MutationTree<State> & Mutations = {
    [MutationTypes.SET_LOGIN_STATUS](state: State, isAuthenticated: isAuthenticated) {
        state.isAuthenticated = isAuthenticated
    },

    [MutationTypes.SET_AUTH_USER](state: State, authUser: authUser) {
        state.authUser = authUser
    },

    [MutationTypes.SET_ACCESS_TOKEN](state: State, accessToken: accessToken) {
        state.accessToken = accessToken
    }
}
