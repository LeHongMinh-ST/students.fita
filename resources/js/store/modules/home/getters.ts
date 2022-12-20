import {GetterTree} from 'vuex'
import {title, activeMenu, countRequest, countReport} from '../../../@type'
import {RootState} from '../../index'
import {State} from './state'

export type Getters = {
    getTitle(state: State): title,
    getActiveMenu(state: State): activeMenu,
    getCountRequest(state: State): countRequest,
    getCountReport(state: State): countReport,
}

export const getters: GetterTree<State, RootState> & Getters = {
    getTitle: (state) => state.title,
    getActiveMenu: (state) => state.activeMenu,
    getCountRequest: (state) => state.countRequest,
    getCountReport: (state) => state.countReport,
}