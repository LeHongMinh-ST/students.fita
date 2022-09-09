<template>
  <div>
    <AppLayout v-if="isAuthenticated"/>
    <AuthLayout v-else/>
  </div>
</template>

<script>

import AppLayout from './layouts/AppLayout.vue'
import AuthLayout from './layouts/AuthLayout.vue'
import {mapState} from 'vuex'
import {computed} from 'vue'

export default {
  name: 'App',
  components: {
    AppLayout,
    AuthLayout
  },
  computed: {
    ...mapState('auth',[
      'isAuthenticated'
    ])
  }
}
</script>

<style scoped>

</style>