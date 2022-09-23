<template>
  <div class="errorImage">
    <img src="/images/error404.png" alt="">

  </div>
  <div class="btnHomePage">
    <q-btn unelevated rounded color="primary" label="Trở lại trang chủ" @click="redirectHomePage"/>
  </div>
</template>

<script lang="ts">
import {defineComponent} from 'vue'
import {useRouter} from "vue-router/dist/vue-router";

export default defineComponent({
  name: "Error404",
  setup() {
    const router = useRouter()

    const redirectHomePage = () => {
      router.push({name: 'Home'})
    }

    return {
      redirectHomePage
    }
  }
})
</script>

<style scoped lang="scss">
.errorImage {
  width: 40vw;
  margin: 0 auto;

  img {
    width: 100%;
  }
}

.btnHomePage {
  text-align: center;
}
</style>