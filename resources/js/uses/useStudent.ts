import _ from 'lodash';
import { ref } from "vue";
import api from "../api";
import apiStudent from "../apiStudent";
import IPaginate from "../models/IPaginate";
import { IStudentResult } from "../models/IStudentResult";

const student = ref<IStudentResult>({full_name: "", student_code: ""})
const students = ref<[]>()
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

const getStudentTemp = (id: number): void => {
    isError.value = false
    isLoading.value = true
    api.getRequestStudentDetail<IStudentResult>(id).then((res) => {
        student.value = _.get(res, 'data.data.request', {full_name: "", student_code: ""})
    }).catch(() => {
        isError.value = true
    }).finally(()=> isLoading.value = false)
}

const getStudentTempStudent = (id: number): void => {
    isError.value = false
    isLoading.value = true
    apiStudent.getRequestStudentDetail<IStudentResult>(id).then((res) => {
        student.value = _.get(res, 'data.data.request', {full_name: "", student_code: ""})
    }).catch(() => {
        isError.value = true
    }).finally(()=> isLoading.value = false)
}

const getStudentClasses = (params = {}) => {
    isError.value = false
    isLoading.value = true
    apiStudent.getAllStudent<IPaginate<IStudentResult[]>>(params).then(res => {
        students.value = _.get(res, 'data.data.students', [])
    }).catch(error => {
        isError.value = true
    }).finally(()=> isLoading.value = false)
}


export default function () {
    return {
        student,
        students,
        getStudent,
        getStudentClasses,
        isError,
        isLoading,
        getStudentTemp,
        getStudentTempStudent
    }
}
