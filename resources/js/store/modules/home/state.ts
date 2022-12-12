import {activeMenu, countRequest, title, countReport} from '../../../@type'

export type State = {
    title: title,
    activeMenu: activeMenu,
    countRequest: countRequest,
    countReport: countReport,
}

const DEFAULT_ACTIVE = 0
const DEFAULT_COUNT_REQUEST = 0
const DEFAULT_COUNT_REPORT = 0
const DEFAULT_TITLE = 'Bảng điều khiển'

export const state: State = {
    title: DEFAULT_TITLE,
    activeMenu: DEFAULT_ACTIVE,
    countRequest: DEFAULT_COUNT_REQUEST,
    countReport: DEFAULT_COUNT_REPORT,
}