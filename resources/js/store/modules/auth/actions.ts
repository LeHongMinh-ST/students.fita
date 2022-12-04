import { ActionTree } from 'vuex'
import {RootState} from '../../index'
import { State } from './state'
import {AuthActionTypes} from './actions-type'
import router from '../../../router'
import {AuthMutationTypes} from "./mutation-types";

export interface Actions {
    // @ts-ignore
    [AuthActionTypes.LOGOUT_ACTION]({ commit }): void
}

export const actions: ActionTree<State, RootState> & Actions = {
    [AuthActionTypes.LOGOUT_ACTION]({ commit }) {
        commit(AuthMutationTypes.SET_LOGIN_STATUS, false)
        commit(AuthMutationTypes.SET_AUTH_USER, {})
        commit(AuthMutationTypes.SET_ACCESS_TOKEN, '')
        return router.push({name: 'Login'})
    }
}
