import {isAuthenticatedStudent, authUserStudent, accessTokenStudent} from '../../../@type'

export type State = {
    isAuthenticatedStudent: isAuthenticatedStudent,
    authUserStudent: authUserStudent,
    accessTokenStudent: accessTokenStudent,
}

export const state: State = {
    isAuthenticatedStudent: false,
    authUserStudent: {},
    accessTokenStudent: ''
}
