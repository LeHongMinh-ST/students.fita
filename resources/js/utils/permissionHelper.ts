import {useStore} from "vuex";
import _ from 'lodash'
import IPermissionResult from "../models/IPermissionResult";
import {StudentRoleEnum} from "../enums/studentRole.enum";

export function permissionHelper(): any {
    const store = useStore()
    const auth = store.getters["auth/getAuthUser"]
    const student = store.getters["authStudent/getAuthUserStudent"]

    const checkPermission = (permission: string): boolean => {
        if (auth.is_super_admin) return true
        const permissions = _.get(auth, 'role.permissions', [])
        return permissions.some((item: IPermissionResult) => item.code === permission)
    }

    const checkClassMonitor = (): boolean => {
        return student.role == StudentRoleEnum.ClassMonitor
    }

    return {
        checkPermission,
        checkClassMonitor
    }
}
