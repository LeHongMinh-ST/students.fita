import {ref} from "vue";
import {IStudentResult} from "../models/IStudentResult";
import api from "../api";
import _ from 'lodash'

const student = ref<IStudentResult>({full_name: "", student_code: ""})

const isError = ref<boolean>(false)
const isLoading = ref<boolean>(false)

const getStudent = (id: number): void => {
    isError.value = false
    isLoading.value = true
    api.getStudentById<IStudentResult>(id).then((res) => {
        student.value = _.get(res, 'data.data.student', {full_name: "", student_code: ""})
    }).catch(() => {
        isError.value = true
    }).finally(()=> isLoading.value = false)
}


export default function () {
    return {
        student,
        getStudent,
        isError,
        isLoading,
    }
}
