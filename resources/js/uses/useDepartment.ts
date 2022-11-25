import {ref} from "vue";
import {IDepartmentResult} from "../models/IDepartmentResult";
import api from "../api";
import _ from 'lodash'

interface IDepartments {
    departments: IDepartmentResult[]
}

const departments = ref<IDepartmentResult[]>([])

const isError = ref<boolean>(false)
const isLoading = ref<boolean>(false)

const getAllDepartment = (params = {}): void => {
    isError.value = false
    isLoading.value = true
    api.getAllDepartment<IDepartments>(params).then((res) => {
        departments.value = _.get(res, 'data.data.departments', [])
    }).catch(() => {
        isError.value = true
    }).finally(()=> isLoading.value = false)
}

export default function () {
    return {
        departments,
        isError,
        isLoading,
        getAllDepartment,
    }
}
