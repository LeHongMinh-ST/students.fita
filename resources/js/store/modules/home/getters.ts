import {GetterTree} from 'vuex'
import {title, activeMenu} from '../../../@type'
import {RootState} from '../../index'
import {State} from './state'

export type Getters = {
    getTitle(state: State): title,
    getActiveMenu(state: State): activeMenu,
}

export const getters: GetterTree<State, RootState> & Getters = {
    getTitle: (state) => state.title,
    getActiveMenu: (state) => state.activeMenu,
}