import {ref} from 'vue'

export function validationHelper(): any {
    const errors = ref([])

    const getValidationErrorMessages = (field: any): Array<any> => {
        if (!errors.value) {
            return []
        }
        const keys = Object.keys(errors.value)
        const key = keys.find(element => element.toLowerCase() === field.toLowerCase())
        if (errors.value[key]) {
            return errors.value[key]
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
        if (errors.value[key]) {
            delete errors.value[key]
        }
    }

    const showValidationError = (): void => {
        this.$q.notify({
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