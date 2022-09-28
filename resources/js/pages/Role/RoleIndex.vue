<template>
  <div>Role</div>
</template>

<script>
import {defineComponent, onMounted} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";

export default defineComponent({
  name: "RoleIndex",
  setup() {
    const store = useStore()

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý phân quyền')
    })

    return {}
  }
})
</script>

<style scoped>

</style>