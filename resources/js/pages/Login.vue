<template>
  <div class="q-pa-md row items-start q-gutter-md justify-center">
    <div class="col-8 col-sm-8 loginWrapper">
      <q-card class="my-card">
        <q-card-section>
          <div class="row">
            <div class="col">
              <div><h4>Chào mừng bạn quay trở lại!</h4></div>
              <form class="q-gutter-md frmLogin">
                <q-input
                    ref="userNameRef"
                    v-model="userName"
                    @keydown.enter.prevent="handleLogin"
                    label="Tên đăng nhập/Email"
                    outlined
                    lazy-rules
                    :rules="userNameRules"
                />

                <q-input
                    ref="passwordRef"
                    v-model="password"
                    @keydown.enter.prevent="handleLogin"
                    label="Mật khẩu"
                    outlined
                    type="password"
                    lazy-rules
                    :rules="passwordRules"
                />

                <div>
                  <q-btn label="Đăng nhập" @click="handleLogin" type="button" color="primary"></q-btn>
                </div>
                <div>
                  <router-link class="textLink" :to='{ name: "Register" }'>Đăng ký</router-link>
                  nếu bạn chưa có tài khoản ?
                </div>
              </form>
            </div>
            <div class="col">
              <div class="text-center">
                <img class="loginImage" src="/images/login.png" alt="Login">
              </div>
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import {defineComponent, ref, watch} from 'vue'
import api from '../api'
import {useQuasar} from 'quasar'
import _ from 'lodash'
import {useStore} from 'vuex'
import {useRouter} from 'vue-router/dist/vue-router'


export default defineComponent({
  name: 'Login',
  setup() {
    const $q = useQuasar()
    const store = useStore()
    const router = useRouter()

    const userName = ref(null)
    const userNameRef = ref(null)
    const userNameRules = [
      val => (val && val.length > 0) || 'Tên đăng nhập không được bỏ trống'
    ]

    const password = ref(null)
    const passwordRef = ref(null)
    const passwordRules = [
      val => (val && val.length > 0) || 'Mật khẩu không được bỏ trống'
    ]

    const handleLogin = async () => {
      userNameRef.value.validate()
      passwordRef.value.validate()

      if (isValidate()) {
        $q.loading.show()

        const data = {
          user_name: userName.value,
          password: password.value,
        }

        api.login(data).then(async res => {
          if (res) {
            store.commit('auth/updateAccessToken', _.get(res, 'data.access_token'))
            store.commit('auth/updateLoginStatus', true)

            await getAuthUser()
            await router.push({name: 'Home'})
          }
        }).catch(() => {
          $q.notify({
            icon: 'report_problem',
            message: 'Tài khoản hoặc mật khẩu không chính xác !',
            color: 'negative',
            position: 'top-right'
          })
        }).finally(() => $q.loading.hide())
      }
    }

    const getAuthUser = async () => {
      let auth = {}
      await api.getAuthUser().then((res) => {
        auth = _.get(res, 'data', {})
        store.commit('auth/updateAuthUser', auth)
      })
      return auth
    }


    const isValidate = () => {
      let isCheck = true

      if (userNameRef.value.hasError) {
        isCheck = false
      }

      if (passwordRef.value.hasError) {
        isCheck = false
      }

      return isCheck
    }

    watch(userName, () => {
      userNameRef.value.resetValidation()
    })

    watch(password, () => {
      passwordRef.value.resetValidation()
    })

    return {
      userName,
      password,
      userNameRules,
      passwordRules,
      userNameRef,
      passwordRef,
      handleLogin,
    }
  },

})
</script>

<style scoped lang="scss">

.loginWrapper {
  margin-top: 5vw;

  .frmLogin {
    margin-top: 20px;

    .textLink {
      text-decoration: none;
      font-weight: bold;
      color: #3988D7;

      &:visited {
      }
    }
  }

  .loginImage {
    width: 100%;
  }
}

</style>