import {ref} from "vue";
import IClassesResult from "../models/IClassesResult";
import api from "../api";
import _ from 'lodash'

interface IClasses {
    classes: IClassesResult[]
}

const classes = ref<IClassesResult[]>([])

const isError = ref<boolean>(false)

const getAllClasses = (params = {}) => {
    api.getAllClasses<IClasses>(params).then(res => {
        classes.value = _.get(res, 'data.data.classes', [])
    }).catch(error => {
        isError.value = true
    })
}

export default function () {
    return {
        classes,
        getAllClasses,
        isError
    }
}
