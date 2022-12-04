import { ActionTree } from 'vuex'
import {RootState} from '../../index'
import { State } from './state'
import {AuthStudentActionTypes} from './actions-type'
import router from '../../../router'
import {AuthStudentMutationTypes} from "./mutation-types";

export interface Actions {
    // @ts-ignore
    [AuthStudentActionTypes.LOGOUT_ACTION_STUDENT]({ commit }): void
}

export const actions: ActionTree<State, RootState> & Actions = {
    [AuthStudentActionTypes.LOGOUT_ACTION_STUDENT]({ commit }) {
        commit(AuthStudentMutationTypes.SET_LOGIN_STATUS_STUDENT, false)
        commit(AuthStudentMutationTypes.SET_AUTH_USER_STUDENT, {})
        commit(AuthStudentMutationTypes.SET_ACCESS_TOKEN_STUDENT, '')
        return router.push({name: 'LoginStudent'})
    }
}
