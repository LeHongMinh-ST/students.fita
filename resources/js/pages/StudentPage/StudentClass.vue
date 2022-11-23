<template>
    <div>Thông tin lớp học/div>
</template>

<script>
import {defineComponent, onMounted} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";

export default defineComponent({
    name: "StudentClass",
    setup() {
        const store = useStore()

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Hồ sơ sinh viên')
        })
    }
})
</script>

<style scoped>

</style>
