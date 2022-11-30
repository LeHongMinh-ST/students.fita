<template>
  <div>Home</div>
</template>

<script>
import {defineComponent, onMounted} from 'vue'
import {HomeMutationTypes} from "../store/modules/home/mutation-types"
import {useStore} from "vuex"

export default defineComponent({
  name: "Home",
  setup() {
    const store = useStore()

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Bảng điều khiển')
    })

  }
})
</script>

<style scoped>

</style>
