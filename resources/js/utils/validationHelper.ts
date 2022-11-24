import {ref} from 'vue'
import {useQuasar} from "quasar";
const $q = useQuasar()
export function validationHelper(): any {
    const errors = ref<any[]>([])

    const getValidationErrorMessages = (field: string): Array<any> => {
        if (!errors.value) {
            return []
        }
        const keys = Object.keys(errors.value)
        const key = keys.find(element => element.toLowerCase() === field.toLowerCase())
        if (key) {
            // @ts-ignore
            if (errors.value[key]) { // @ts-ignore
                return errors.value[key]
            }
        }
        return []
    }

    const getValidationErrors = (field: any): string => {
        const errors = getValidationErrorMessages(field)
        if (errors.length !== 0) {
            return errors.join('\r\n')
        }
        return ''
    }

    const hasValidationErrors = (field: any): boolean => {
        if (getValidationErrorMessages(field).length !== 0) {
            return true
        }
        return false
    }

    const setValidationErrors = (payload: any): void => {
        errors.value = payload
    }

    const resetValidateErrors = (field: any): void => {
        const keys = Object.keys(errors.value)
        const key = keys.find(element => element.toLowerCase() === field.toLowerCase())
        if (key) { // @ts-ignore
            if (errors.value[key]) {
                // @ts-ignore
                delete errors.value[key]
            }
        }
    }

    const showValidationError = (): void => {
        $q.notify({
            type: 'negative',
            message: 'Vui lòng kiểm tra lại dữ liệu nhập vào!',
            icon: 'report_problem',
            position: 'top-right'
        })
    }

    return {
        showValidationError,
        setValidationErrors,
        getValidationErrors,
        hasValidationErrors,
        resetValidateErrors
    }
}
