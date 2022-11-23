import { ActionTree } from 'vuex'
import {RootState} from '../../index'
import { State } from './state'
import {AuthActionTypes} from './actions-type'
import router from '../../../router'

export interface Actions {
    // @ts-ignore
    [AuthActionTypes.LOGOUT_ACTION]({ commit }): void
}

export const actions: ActionTree<State, RootState> & Actions = {
    [AuthActionTypes.LOGOUT_ACTION]({ commit }) {
        commit('updateLoginStatus', false)
        commit('updateLoginStudentStatus', false)
        commit('updateAuthUser', {})
        commit('updateAccessToken', '')
        return router.push({name: 'Login'})
    }
}
