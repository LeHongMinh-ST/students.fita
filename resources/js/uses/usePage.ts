import {ref} from "vue";

interface Page {
    currentPage: number,
    total: number,
    perPage: number
}

const page = ref<Page>({
    currentPage: 1,
    total: 0,
    perPage: 10
})
export default function () {
    return {
        page
    }
}
