<template>
  <div class="q-pa-md row items-start q-gutter-md justify-center">
    <div class="col-8 col-sm-8 loginWrapper">
      <q-card class="my-card">
        <q-card-section>
          <div class="row">
            <div class="col">
              <div class="logoLogin">
                <img src="/images/FITA.png" alt="">
              </div>
              <div class="textLoginHeader"><h4 class="textHeader text-center">Hệ thống quản lý sinh viên trực tuyến</h4></div>
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
                  <q-btn label="Đăng nhập" @click="handleLogin" icon-right="fa-solid fa-right-to-bracket" type="button"
                         color="primary"></q-btn>
                </div>
                <div>
                  <router-link class="textLink" :to='{ name: "Register" }'>Đăng ký</router-link>
                  nếu bạn chưa có tài khoản ?
                </div>
                <div class="separator">hoặc</div>
                <div class="social-card">
                  <div class="microsoft social-btn q-btn text-white">
                    <span><q-icon class="social-icon" name="fa-brands fa-windows" size="md"/><span class="social-text">Đăng nhập với tài khoản Microsoft</span></span>
                  </div>

                  <div class="google social-btn q-btn text-white" @click="getUrlSocial('google')">
                    <span><q-icon class="social-icon" name="fa-brands fa-google-plus-g" size="md"/><span
                        class="social-text">Đăng nhập với tài khoản Google</span></span>
                  </div>
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

<script lang="ts">
import {defineComponent, ref, watch} from 'vue'
import api from '../api'
import {useQuasar} from 'quasar'
import _ from 'lodash'
import {useStore} from 'vuex'
import {useRouter} from 'vue-router/dist/vue-router'
import {AuthMutationTypes} from "../store/modules/auth/mutation-types"
import ILoginResult from "../models/ILoginResult";
import IUserResult from "../models/IUserResult";
import IRedirectSocialResult from "../models/IRedirectSocialResult";

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

    const handleLogin = async (): Promise<void> => {
      userNameRef.value.validate()
      passwordRef.value.validate()

      if (isValidate()) {
        $q.loading.show()

        const data = {
          user_name: userName.value,
          password: password.value,
        }

        api.login<ILoginResult>(data).then(async res => {
          if (res) {
            store.commit(`auth/${AuthMutationTypes.SET_ACCESS_TOKEN}`, _.get(res, 'data.access_token'))
            store.commit(`auth/${AuthMutationTypes.SET_LOGIN_STATUS}`, true)
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

    const getUrlSocial = (provider: string): void => {
      api.getRedirectSocial<IRedirectSocialResult>(provider).then(res => {
        const url = _.get(res, 'data.data.url')
        if (url) {
          window.open(url, '_self');
        }
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Lỗi không thể kết nối !',
          color: 'negative',
          position: 'top-right'
        })
      })
    }

    const getAuthUser = async (): Promise<any> => {
      let auth = {}
      await api.getAuthUser<IUserResult>().then((res) => {
        auth = _.get(res, 'data', {})
        store.commit(`auth/${AuthMutationTypes.SET_AUTH_USER}`, auth)
      })
      return auth
    }

    const isValidate = (): boolean => {
      let isCheck = true

      if (userNameRef.value.hasError) {
        isCheck = false
      }

      if (passwordRef.value.hasError) {
        isCheck = false
      }

      return isCheck
    }

    watch(userName, (): void => {
      userNameRef.value.resetValidation()
    })

    watch(password, (): void => {
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
      getUrlSocial
    }
  },

})
</script>

<style scoped lang="scss">

.loginWrapper {
  margin-top: 5vw;

  .logoLogin {
    width: 4vw;
    margin: 0 auto;

    img {
      width: 100%;
    }
  }

  .textLoginHeader {
    .textHeader {
      margin: 10px;
    }
  }

  .frmLogin {
    margin-top: 20px;

    .textLink {
      text-decoration: none;
      font-weight: bold;
      color: #3988D7;

      &:visited {
      }
    }

    .social-card {
      width: 60%;
      margin: 20px auto;

      .social-btn {
        margin-bottom: 10px;
        padding: 5px;
        cursor: pointer;
        width: 100%;
        border-radius: 3px;

        .social-text {
          font-size: 0.75vw;
          margin-left: 10px;
        }

        .social-icon {
          font-size: 1.5vw !important;
        }
      }

      .microsoft {
        background-color: #00A1F1;
      }

      .google {
        background-color: #EA4335;
      }

    }

    .separator {
      display: flex;
      align-items: center;
      text-align: center;
    }

    .separator::before,
    .separator::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #868686;
    }

    .separator:not(:empty)::before {
      margin-right: .25em;
    }

    .separator:not(:empty)::after {
      margin-left: .25em;
    }
  }

  .loginImage {
    width: 100%;
  }


}

</style>
