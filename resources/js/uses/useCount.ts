import api from "../api";
import {HomeMutationTypes} from "../store/modules/home/mutation-types";
import {useStore} from "vuex";
import _ from 'lodash'

export default function () {
    const store = useStore()

    const getReportCount = async () => {
        try {
            const res = await api.countReportPending()
            const count = _.get(res, 'data.data.reportCount', 0)
            store.commit(`home/${HomeMutationTypes.SET_COUNT_REPORT}`, count)
            // countReport.value = store.getters['home/getCountReport']
        }catch (error) {
            console.log(error)
        }
    }

    const getRequestCount = async () => {
        try {
            const res = await api.countStudentRequest()
            const count = _.get(res, 'data.data.requestCount', 0)
            store.commit(`home/${HomeMutationTypes.SET_COUNT_REQUEST}`, count)
        }catch (error) {
            console.log(error)
        }
    }

    return {
        getReportCount, getRequestCount
    }
}