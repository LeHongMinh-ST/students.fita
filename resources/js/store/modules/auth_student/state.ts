import {isAuthenticated, authUser, accessToken} from '../../../@type'

export type State = {
    isAuthenticated: isAuthenticated,
    authUser: authUser,
    accessToken: accessToken,
}

export const state: State = {
    isAuthenticated: false,
    authUser: {},
    accessToken: ''
}
