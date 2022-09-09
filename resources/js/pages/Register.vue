<template>
  <div class="q-pa-md row items-start q-gutter-md justify-center">
    <div class="col-8 col-sm-8 loginWrapper">
      <q-card class="my-card">
        <q-card-section>
          <div class="row">
            <div class="col">
              <div class="text-center">
                <img class="loginImage" src="/images/login.png" alt="Login">
              </div>
            </div>
            <div class="col">
              <div><h4>Đăng ký tài khoản</h4></div>
              <form class="q-gutter-md frmLogin">
                <q-input
                    ref="fullNameRef"
                    label="Họ và tên"
                    v-model="fullName"
                    @keydown.enter.prevent="handleRegister"
                    outlined
                    lazy-rules
                    :rules="fullNameRules"
                    :error-message="getValidationErrors('full_name')"
                    :error="hasValidationErrors('full_name')"
                />
                <q-input
                    ref="userNameRef"
                    v-model="userName"
                    @keydown.enter.prevent="handleRegister"
                    label="Tên đăng nhập"
                    outlined
                    lazy-rules
                    :rules="userNameRules"
                    :error-message="getValidationErrors('user_name')"
                    :error="hasValidationErrors('user_name')"
                />
                <q-input
                    ref="emailRef"
                    label="Email"
                    v-model="email"
                    @keydown.enter.prevent="handleRegister"
                    outlined
                    lazy-rules
                    :rules="emailRules"
                    :error-message="getValidationErrors('email')"
                    :error="hasValidationErrors('email')"
                />
                <q-input
                    ref="passwordRef"
                    v-model="password"
                    label="Mật khẩu"
                    @keydown.enter.prevent="handleRegister"
                    outlined
                    lazy-rules
                    type="password"
                    :rules="passwordRules"
                    :error-message="getValidationErrors('password')"
                    :error="hasValidationErrors('password')"
                />
                <q-input
                    ref="passwordConfirmRef"
                    v-model="passwordConfirm"
                    label="Nhập lại mật khẩu"
                    @keydown.enter.prevent="handleRegister"
                    outlined
                    type="password"
                    lazy-rules
                    :rules="passwordConfirmRules"
                />
                <div>
                  <q-btn label="Đăng ký" type="button" @click="handleRegister" color="primary"></q-btn>
                </div>
                <div>
                  <router-link class="textLink" :to='{ name: "Login" }'>Đăng nhập</router-link>
                  nếu bạn đã có tài khoản ?
                </div>
              </form>
            </div>

          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import {ref, watch} from 'vue'
import api from '../api'
import {useQuasar} from 'quasar'
import _ from 'lodash'
import {useRouter} from 'vue-router/dist/vue-router'
import {isValidEmail} from '../utils/helpers'
import {validationHelper} from '../utils/validationHelper'

export default {
  name: "Register",
  setup() {
    const $q = useQuasar()
    const router = useRouter()
    const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()

    const userName = ref(null)
    const userNameRef = ref(null)
    const userNameRules = [
      val => (val && val.length > 0) || 'Tên đăng nhập không được bỏ trống'
    ]

    const fullName = ref(null)
    const fullNameRef = ref(null)
    const fullNameRules = [
      val => (val && val.length > 0) || 'Họ và tên không được bỏ trống'
    ]

    const email = ref(null)
    const emailRef = ref(null)
    const emailRules = [
      val => (val && val.length > 0) || 'Email không được bỏ trống',
      val => isValidEmail(val) || 'Không đúng đinh dạng email'
    ]

    const password = ref(null)
    const passwordRef = ref(null)
    const passwordRules = [
      val => (val && val.length > 0) || 'Mật khẩu không được bỏ trống',
      val => val.length >= 6 || 'Mật khẩu phải lớn hơn hoặc bằng 6 ký tự'
    ]

    const passwordConfirm = ref(null)
    const passwordConfirmRef = ref(null)
    const passwordConfirmRules = [
      val => (val && val.length > 0) || 'Xác nhận mật không được bỏ trống',
      val => val === password.value || 'Xác nhận mật khẩu không trùng khớp'
    ]

    const handleRegister = () => {
      userNameRef.value.validate()
      fullNameRef.value.validate()
      emailRef.value.validate()
      passwordRef.value.validate()
      passwordConfirmRef.value.validate()

      if (isValidate()) {
        $q.loading.show()

        const data = {
          user_name: userName.value,
          full_name: fullName.value,
          email: email.value,
          password: password.value,
          password_confirmation: passwordConfirm.value
        }

        api.register(data).then(res => {
          $q.notify({
            icon: 'report_problem',
            message: _.get(res, 'message', 'Đăng ký thành công !'),
            color: 'teal',
            position: 'top-right'
          })

          router.push({name: 'Login'})
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            $q.notify({
              icon: 'report_problem',
              message,
              color: 'negative',
              position: 'top-right'
            })
          }
          if (Object.keys(errors).length > 0) {
            setValidationErrors(errors)
          }
        }).finally(() => $q.loading.hide())
      }
    }

    const isValidate = () => {
      let isCheck = true
      if (fullNameRef.value.hasError) isCheck = false
      if (userNameRef.value.hasError) isCheck = false
      if (emailRef.value.hasError) isCheck = false
      if (passwordRef.value.hasError) isCheck = false
      if (passwordConfirmRef.value.hasError) isCheck = false
      return isCheck
    }

    watch(fullName, () => {
      resetValidateErrors('full_name')
      fullNameRef.value.resetValidation()
    })

    watch(userName, () => {
      resetValidateErrors('user_name')
      userNameRef.value.resetValidation()
    })

    watch(email, () => {
      resetValidateErrors('email')
      emailRef.value.resetValidation()
    })

    watch(password, () => {
      resetValidateErrors('password')
      passwordRef.value.resetValidation()
    })

    watch(passwordConfirm, () => {
      passwordConfirmRef.value.resetValidation()
    })

    return {
      email,
      emailRef,
      emailRules,
      password,
      passwordRef,
      passwordRules,
      passwordConfirm,
      passwordConfirmRef,
      passwordConfirmRules,
      userName,
      userNameRef,
      userNameRules,
      fullName,
      fullNameRef,
      fullNameRules,
      handleRegister,
      getValidationErrors,
      hasValidationErrors
    }
  }
}
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