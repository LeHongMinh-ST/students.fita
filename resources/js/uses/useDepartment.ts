import {ref} from "vue";
import {IDepartmentResult} from "../models/IDepartmentResult";
import api from "../api";
import _ from 'lodash'

interface IDepartments {
    departments: IDepartmentResult[]
}

const departments = ref<IDepartmentResult[]>([])

const isError = ref<boolean>(false)

const getAllDepartment = (params = {}): void => {
    api.getAllDepartment<IDepartments>(params).then((res) => {
        departments.value = _.get(res, 'data.data.departments', [])
    }).catch(() => {
        isError.value = true
    })
}

export default function () {
    return {
        departments,
        isError,
        getAllDepartment,
    }
}
