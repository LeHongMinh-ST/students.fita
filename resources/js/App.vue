<template>
  <div>
<!--    <AppLayout v-if="isAuthenticated"/>-->
<!--    <AuthLayout v-else/>-->
    <router-view />
  </div>
</template>

<script lang="ts">

import AppLayout from './layouts/AppLayout.vue'
import AuthLayout from './layouts/AuthLayout.vue'
import {mapState} from 'vuex'
import {computed, defineComponent} from 'vue'

export default defineComponent({
  name: 'App',
  components: {
    AppLayout,
    AuthLayout,


  },
  computed: {
    ...mapState('auth', [
      'isAuthenticated'
    ])
  }
})
</script>

<style lang="scss">
.required {
  color: #F44336;
}

.imgEmpty {
  width: 500px;
}
</style>
