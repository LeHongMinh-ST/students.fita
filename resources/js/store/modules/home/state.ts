import {activeMenu, title} from '../../../@type'

export type State = {
    title: title,
    activeMenu: activeMenu,
}

const DEFAULT_ACTIVE = 0
const DEFAULT_TITLE = 'Bảng điều khiển'

export const state: State = {
    title: DEFAULT_TITLE,
    activeMenu: DEFAULT_ACTIVE
}