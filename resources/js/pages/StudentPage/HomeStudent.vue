<template>
    <div>Hồ sơ sinh viên</div>
</template>

<script lang="ts">
import {defineComponent, onMounted} from "vue";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useStore} from "vuex"

export default defineComponent({
    name: "HomeStudent",
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
