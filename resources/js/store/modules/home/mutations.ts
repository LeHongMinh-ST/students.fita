import { MutationTree } from 'vuex'

import {activeMenu, title} from '../../../@type'

import { State } from './state'

import {HomeMutationTypes as MutationTypes} from './mutation-types'

export type Mutations<S = State> = {
    [MutationTypes.SET_ACTIVE_MENU](state: S, payload: activeMenu): void
    [MutationTypes.SET_TITLE](state: S, payload: title): void
}

export const  mutations: MutationTree<State> & Mutations = {
    [MutationTypes.SET_TITLE](state: State, title: title) {
        state.title = title
    },

    [MutationTypes.SET_ACTIVE_MENU](state: State, activeMenu: activeMenu) {
        state.activeMenu = activeMenu
    },


}