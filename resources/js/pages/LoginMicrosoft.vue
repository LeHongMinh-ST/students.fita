<template>
  <div>
    Đang liên kết bằng tài khoản Microsoft, vui lòng chờ!
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted} from "vue"
import api from "../api"
import {useRoute} from "vue-router/dist/vue-router"
import _ from "lodash"
import {useStore} from "vuex"
import {AuthMutationTypes} from "../store/modules/auth/mutation-types"
import {useRouter} from "vue-router"
import ILoginResult from "../models/ILoginResult";
import eventBus from "../utils/eventBus";

const PROVIDER_MICROSOFT= 'azure'

export default defineComponent({
  name: "LoginMicrosoft",

  setup() {

    const route = useRoute()
    const store = useStore()
    const router = useRouter()

    onMounted(() => {
      loginMicrosoftCallback()
    })

    const loginMicrosoftCallback = (): void => {
      const payload = {
        code: _.get(route, 'query.code', '')
      }

      api.loginSocialCallback<ILoginResult>(PROVIDER_MICROSOFT, payload).then(async res => {
        if (res) {
          if (_.get(res, 'data.back', false)) {
            await router.push({name: 'Profile'})
          } else {
            store.commit(`auth/${AuthMutationTypes.SET_ACCESS_TOKEN}`, _.get(res, 'data.access_token'))
            store.commit(`auth/${AuthMutationTypes.SET_LOGIN_STATUS}`, true)
            await getAuthUser()
            await router.push({name: 'Home'})
          }
        }
      }).catch(error => {
        let errors = _.get(error.response, 'data.error', {})
        if (Object.keys(errors).length === 0) {
          let message = _.get(error.response, 'data.message', '')
          eventBus.$emit('notify-error', message)
        }
        router.push({name: 'Login'})
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
