import { MutationTree } from 'vuex'

import {isAuthenticatedStudent, authUserStudent, accessTokenStudent} from '../../../@type'

import { State } from './state'

import {AuthStudentMutationTypes as MutationTypes} from './mutation-types'

export type Mutations<S = State> = {
    [MutationTypes.SET_LOGIN_STATUS_STUDENT](state: S, payload: isAuthenticatedStudent): void
    [MutationTypes.SET_AUTH_USER_STUDENT](state: S, payload: authUserStudent): void
    [MutationTypes.SET_ACCESS_TOKEN_STUDENT](state: S, payload: accessTokenStudent): void
}

export const  mutations: MutationTree<State> & Mutations = {
    [MutationTypes.SET_LOGIN_STATUS_STUDENT](state: State, isAuthenticatedStudent: isAuthenticatedStudent) {
        state.isAuthenticatedStudent = isAuthenticatedStudent
    },

    [MutationTypes.SET_AUTH_USER_STUDENT](state: State, authUserStudent: authUserStudent) {
        state.authUserStudent = authUserStudent
    },

    [MutationTypes.SET_ACCESS_TOKEN_STUDENT](state: State, accessTokenStudent: accessTokenStudent) {
        state.accessTokenStudent = accessTokenStudent
    }
}
