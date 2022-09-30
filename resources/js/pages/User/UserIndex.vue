<template>
  <div>User</div>
</template>

<script>
import {defineComponent, onMounted} from "vue"
import {useStore} from "vuex"
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";

export default defineComponent({
  name: "UserIndex",
  setup() {
    const store = useStore()

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý người dùng')
    })

    return {}
  }
})
</script>

<style scoped>

</style>