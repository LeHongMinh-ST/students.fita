<template>
    <div class="student-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
            <q-breadcrumbs-el label="Tài khoản"/>
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-9 q-pr-lg">
                    <q-card class="main-form  meta-boxes">
                        <q-card-section>
                            <div class="widget-title text-bold">Thông tin tài khoản</div>
                        </q-card-section>
                        <q-separator/>
                        <q-card-section>
                            <div class="row">
                                <div class="col-3 q-pr-md">
                                    <div class="form-group">
                                        <label class="text-bold">Ảnh sinh viên </label>
                                        <div>
                                            <q-file
                                                v-model="image"
                                                label="Chọn ảnh đại diện"
                                                outlined
                                                dense
                                                @update:model-value="handleUpload()"
                                            ></q-file>
                                        </div>
                                        <div class="avatar-wrapper q-mt-sm">
                                            <q-img
                                                class="avatar-student"
                                                :src="imageUrl"
                                                spinner-color="white"
                                                style="height: 275px; width: 100%"
                                            ></q-img>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-6 q-pr-sm">
                                            <div class="form-group">
                                                <label class="text-bold">Tên tài khoản <span
                                                    class="required">*</span></label>
                                                <q-input
                                                    outlined
                                                    dense
                                                    v-model="profile.user_name"
                                                    :error-message="getValidationErrors('student_code')"
                                                    :error="hasValidationErrors('student_code')"
                                                    @update:model-value="() => resetValidateErrors('student_code')"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-6 q-pr-sm">
                                            <div class="form-group">
                                                <label class="text-bold">Họ và tên <span
                                                    class="required">*</span></label>
                                                <q-input
                                                    outlined
                                                    dense
                                                    v-model="profile.full_name"
                                                    :error-message="getValidationErrors('full_name')"
                                                    :error="hasValidationErrors('full_name')"
                                                    @update:model-value="() => resetValidateErrors('full_name')"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 q-pr-sm">
                                            <div class="form-group">
                                                <label class="text-bold">Email <span class="required">*</span></label>
                                                <q-input
                                                    outlined
                                                    dense
                                                    v-model="profile.email"
                                                    :error-message="getValidationErrors('email')"
                                                    :error="hasValidationErrors('email')"
                                                    @update:model-value="() => resetValidateErrors('email')"
                                                />
                                            </div>
                                        </div>
                                        <div class="col-6 q-pr-sm">
                                            <div class="form-group">
                                                <label class="text-bold">Số điện thoại <span
                                                    class="required">*</span></label>
                                                <q-input
                                                    outlined
                                                    dense
                                                    v-model="profile.phone"
                                                    :error-message="getValidationErrors('phone')"
                                                    :error="hasValidationErrors('phone')"
                                                    @update:model-value="() => resetValidateErrors('phone')"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6 q-pr-sm">
                                            <div v-if="profile.is_teacher" class="form-group">
                                                <label class="text-bold">Mã giảng viên <span class="required">*</span></label>
                                                <q-input
                                                    outlined
                                                    dense
                                                    v-model="profile.teacher_code"
                                                    :error-message="getValidationErrors('teacher_code')"
                                                    :error="hasValidationErrors('teacher_code')"
                                                    @update:model-value="() => resetValidateErrors('teacher_code')"
                                                />
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>
                        </q-card-section>

                    </q-card>

                </div>
                <div class="col-3 right-sidebar">
                    <q-card class="widget meta-boxes action-horizontal q-mb-md">
                        <q-card-section>
                            <div class="widget-title text-bold">Tác vụ</div>
                        </q-card-section>
                        <q-separator/>
                        <q-card-section>
                            <q-btn @click="handleUpdateProfile" no-caps color="secondary" class="q-mr-sm">
                                <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                                Lưu
                            </q-btn>
                            <q-btn @click="handleOpenResetPassword" no-caps color="green" class="q-mr-sm">
                                <q-icon name="fa-solid fa-lock" class="q-mr-sm" size="xs"></q-icon>
                                Đổi mật khẩu
                            </q-btn>

                        </q-card-section>
                    </q-card>
                    <q-card>
                        <q-card-section>
                            <div class="widget-title text-bold">Liên kết tài khoản</div>
                        </q-card-section>
                        <q-separator/>
                        <q-card-section>
                            <div class="social-card">
                                <div class="microsoft social-btn q-btn text-white">
                                    <span><q-icon class="social-icon" name="fa-brands fa-windows" size="md"/><span class="social-text">Liên kết với tài khoản Microsoft</span></span>
                                </div>

                                <div v-if="socialGoogle.id === null" class="google social-btn q-btn text-white" @click="getUrlSocial('google')">
                                        <span>
                                            <q-icon class="social-icon" name="fa-brands fa-google-plus-g" size="md"/>
                                            <span class="social-text">Liên kết với tài khoản Google</span>
                                        </span>
                                </div>

                                <div v-else class="google social-btn q-btn text-white">
                                        <span>
                                            <q-icon class="social-icon" name="fa-brands fa-google-plus-g" size="md"/>
                                            <span class="social-text">{{ socialGoogle.email}}</span>
                                        </span>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <q-dialog v-model="isShowDialogResetPassword" @hide="() => isShowDialogResetPassword = false">
                    <q-card>
                        <q-card-section>
                            <div class="text-h6">Đổi mật khẩu</div>
                        </q-card-section>
                        <q-card-section class="row items-center" style="width: 100%">
                            <div class="row">
                                <div class="col-12 q-pr-sm">
                                    <div class="form-group">
                                        <label class="text-bold">Mật khẩu cũ <span
                                            class="required">*</span></label>
                                        <q-input
                                            :type="isPwd ? 'password' : 'text'"
                                            outlined
                                            dense
                                            v-model="password_old"
                                            :error-message="getValidationErrors('password_old')"
                                            :error="hasValidationErrors('password_old')"
                                            @update:model-value="() => resetValidateErrors('password_old')"
                                        >
                                            <template v-slot:append>
                                                <q-icon
                                                    :name="isPwd ? 'visibility_off' : 'visibility'"
                                                    class="cursor-pointer"
                                                    @click="isPwd = !isPwd"
                                                />
                                            </template>
                                        </q-input>
                                    </div>
                                </div>
                                <div class="col-6 q-pr-sm">
                                    <div class="form-group">
                                        <label class="text-bold">Mật khẩu mới <span
                                            class="required">*</span></label>
                                        <q-input
                                            :type="isPwd ? 'password' : 'text'"

                                            outlined
                                            dense
                                            v-model="password"
                                            :error-message="getValidationErrors('password')"
                                            :error="hasValidationErrors('password')"
                                            @update:model-value="() => resetValidateErrors('password')"
                                        >
                                            <template v-slot:append>
                                                <q-icon
                                                    :name="isPwd ? 'visibility_off' : 'visibility'"
                                                    class="cursor-pointer"
                                                    @click="isPwd = !isPwd"
                                                />
                                            </template>
                                        </q-input>
                                    </div>
                                </div>
                                <div class="col-6 q-pr-sm">
                                    <div class="form-group">
                                        <label class="text-bold">Xác nhận mật khẩu <span
                                            class="required">*</span></label>
                                        <q-input
                                            :type="isPwd ? 'password' : 'text'"
                                            outlined
                                            dense
                                            v-model="password_confirm"
                                            :error-message="getValidationErrors('password_confirm')"
                                            :error="hasValidationErrors('password_confirm')"
                                            @update:model-value="() => resetValidateErrors('password_confirm')"
                                        >
                                            <template v-slot:append>
                                                <q-icon
                                                    :name="isPwd ? 'visibility_off' : 'visibility'"
                                                    class="cursor-pointer"
                                                    @click="isPwd = !isPwd"
                                                />
                                            </template>
                                        </q-input>
                                    </div>
                                </div>
                            </div>
                        </q-card-section>
                        <q-card-actions align="right" class="row">
                            <q-btn
                                flat
                                label="Đóng"
                                color="primary"
                                @click="() => isShowDialogResetPassword = false"
                                v-close-popup
                            />
                            <q-btn
                                label="Lưu"
                                color="blue"
                                @click="handleResetPassword"
                            />
                        </q-card-actions>
                    </q-card>
                </q-dialog>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useRouter} from "vue-router";
import {useQuasar} from "quasar";
import {validationHelper} from "../../utils/validationHelper";
import {convertTime} from "../../utils/helpers";
import {IStudentResult} from "../../models/IStudentResult";
import _ from "lodash";
import api from "../../api"
import eventBus from "../../utils/eventBus";
import IUserResult from "../../models/IUserResult";
import {AuthMutationTypes} from "../../store/modules/auth/mutation-types";
import IRedirectSocialResult from "../../models/IRedirectSocialResult";
import {ISocialResult} from "../../models/ISocialResult";

export default defineComponent({
    name: "Profile",
    setup() {
        const store = useStore()
        const $q = useQuasar()
        const router = useRouter()

        const profile = ref<IUserResult>({full_name: "", id: 0, user_name: ""});

        const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()

        const isShowDialogResetPassword = ref<boolean>(false)


        const image = ref<any | null>(null);
        const imageUrl = ref<string>('/images/User-Default.jpg');

        const handleUpload = () => {
            if (image.value) {
                imageUrl.value = URL.createObjectURL(image.value);
            }
        }

        const password = ref<string>('')
        const password_old = ref<string>('')
        const password_confirm = ref<string>('')

        const isPwd = ref<boolean>(true);

        const socialGoogle = ref<ISocialResult>({
            id: null,
            email: "",
            social_id: "",
            social_provider: "",
            socialable_id: 0,
            socialable_type: ""
        })

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Thông tin tài khoản')
            profile.value = store.getters['auth/getAuthUser']
            if (profile.value.thumbnail) {
                imageUrl.value = profile.value.thumbnail_url
            }

            socialGoogle.value = profile.value.socials.find(item => item.social_provider === 'google')
        })

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
            router.push({name: nameRoute, params: params})
        }

        const handleUpdateProfile = () => {
            if (!isRequest.value) {
                isRequest.value = true
                $q.loading.show()
                const formData = new FormData()

                Object.keys(profile.value).map(function (objectKey) {
                    const value = profile.value[objectKey] ;
                    if (value == null) {
                        formData.append(objectKey, "")
                    } else  {
                        formData.append(objectKey, value)

                    }
                });

                formData.append('image', image.value)
                api.updateProfile<IStudentResult>(formData).then(res => {
                    api.getAuthUser<IUserResult>().then((res) => {
                        const auth = _.get(res, 'data', {})
                        store.commit(`auth/${AuthMutationTypes.SET_AUTH_USER}`, auth)
                    })
                    $q.notify({
                        icon: 'check',
                        message: 'Cập nhật thông tin thành công',
                        color: 'positive',
                        position: 'top-right'
                    })
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
                }).finally(()=> {
                    $q.loading.hide()
                    isRequest.value = false
                })

            }
        }

        const handleOpenResetPassword = () => {
            password.value = ''
            password_old.value = ''
            password_confirm.value = ''
            isShowDialogResetPassword.value = true
        }

        const isRequest = ref<boolean>(false)

        const handleResetPassword = () => {

            if (!isRequest.value) {
                isRequest.value = true
                $q.loading.show()

                const data = {
                    password: password.value,
                    password_old: password_old.value,
                    password_confirmation: password_confirm.value
                }

                api.resetMyPassword<IStudentResult>(data).then(res => {
                    isShowDialogResetPassword.value = false
                    $q.notify({
                        icon: 'check',
                        message: 'Đổi mật khẩu thành công',
                        color: 'positive',
                        position: 'top-right'
                    })
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
                }).finally(() => {
                    $q.loading.hide()

                    isRequest.value = false
                })
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

        return {
            redirectRouter,
            getValidationErrors,
            hasValidationErrors,
            image,
            imageUrl,
            handleUpload,
            convertTime,
            resetValidateErrors,
            profile,
            isShowDialogResetPassword,
            handleOpenResetPassword,
            password,
            password_old,
            password_confirm,
            handleResetPassword,
            isPwd,
            handleUpdateProfile,
            getUrlSocial,
            socialGoogle
        }
    }
})
</script>

<style scoped lang="scss">
.student-wrapper {
    .main {
        margin-top: 20px;

        .main-form {
            margin-bottom: 15px;

            .form-group {
                margin-bottom: 15px;
            }
        }

        .avatar-wrapper {
            height: 275px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #8f8f8f;
            margin-bottom: 40px;

            .avatar-student {
                border-radius: 5px;
                object-fit: cover;
            }
        }

        .social-card {
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
    }
}

</style>
