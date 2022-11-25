import {VALIDATE_EMAIL_REGEX, VALIDATE_PHONE_REGEX_RULE} from "./constants";
import moment from 'moment'

export const isValidEmail = (email: string): boolean => {
    let result = false
    if (email && typeof email === 'string') {
        const regex = RegExp(VALIDATE_EMAIL_REGEX);
        result = regex.test(email.trim())
    }
    return result
}

export const isValidPhone = (phone: string): boolean => {
    let result = false

    if (phone && typeof phone === 'string') {
        let trimPhone = phone.trim()

        if (trimPhone) {
            const regexRule = RegExp(VALIDATE_PHONE_REGEX_RULE);

            let ruleMatches = trimPhone.match(regexRule);

            if (ruleMatches && ruleMatches.length > 0) {
                result = (ruleMatches[0] === trimPhone)
            }
        }
    }

    return result
}

export const formatMoney = (money: any): string => {
    return money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
}

export const formatNumber = (money: any): string => {
    return money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
}

export const formatDate = (date: any): string => {
    return moment(date).format('HH:mm DD/MM/YYYY')
}

export const formatTime = (date: any): string => {
    return moment(date).lang('vi').fromNow();
}

export const formatTimeFrom = (stringDate: any): string => {
    moment.locale('vi');
    return moment(stringDate).fromNow();
}

export const convertTime = (time: string) => {
    return /^\d{10}$/.test(time) ? moment.unix(Number(time)).format('DD/MM/YYYY') : time
}
