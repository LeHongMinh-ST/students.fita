<template>
  <div>
    Đang đăng nhập bằng tài khoản google, vui lòng chờ!
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted} from "vue";
import api from "../api";
import {useRoute} from "vue-router/dist/vue-router";
import _ from "lodash";
import {useStore} from "vuex";
import {AuthMutationTypes} from "../store/modules/auth/mutation-types";
import {useRouter} from "vue-router";

export default defineComponent({
  name: "LoginGoogle",

  setup() {

    const route = useRoute()
    const store = useStore()
    const router = useRouter()

    onMounted(() => {
      loginGoogleCallback()
    })

    const loginGoogleCallback = (): void => {

      api.loginSocialCallback('google', {
        code: route.query.code
      }).then(async res => {
        if (res) {
          store.commit(`auth/${AuthMutationTypes.SET_ACCESS_TOKEN}`, _.get(res, 'data.access_token'))
          store.commit(`auth/${AuthMutationTypes.SET_LOGIN_STATUS}`, true)
          await getAuthUser()
          await router.push({name: 'Home'})
        }
      }).catch(error => {
        console.log(error)
      })
    }
    const getAuthUser = async (): Promise<any> => {
      let auth = {}
      await api.getAuthUser().then((res) => {
        auth = _.get(res, 'data', {})
        store.commit(`auth/${AuthMutationTypes.SET_AUTH_USER}`, auth)
      })
      return auth
    }
    return {}
  }
})
</script>

<style scoped>

</style>