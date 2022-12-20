import {useStore} from "vuex";
import _ from 'lodash'
import IPermissionResult from "../models/IPermissionResult";
import {StudentRoleEnum} from "../enums/studentRole.enum";

export function permissionHelper(): any {
    const store = useStore()

    const checkPermission = (permission: string): boolean => {
        const auth = store.getters["auth/getAuthUser"]
        if (auth.is_super_admin) return true
        const permissions = _.get(auth, 'role.permissions', [])
        return permissions.some((item: IPermissionResult) => item.code === permission)
    }

    const checkTeacher = (): boolean => {
        const auth = store.getters["auth/getAuthUser"]
        return auth.is_teacher
    }

    const checkClassMonitor = (): boolean => {
        const student = store.getters["authStudent/getAuthUserStudent"]
        return student.role == StudentRoleEnum.ClassMonitor
    }

    return {
        checkPermission,
        checkClassMonitor,
        checkTeacher
    }
}
