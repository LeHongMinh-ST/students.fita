<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
          <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
          <q-breadcrumbs-el label="Nhóm và phân quyền" />
            <q-breadcrumbs-el label="Chỉnh sửa"/>
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-9 q-pr-lg">
                    <q-card class="main-form">
                        <div class="form-group">
                            <label for="name" class="text-bold">Tên <span class="required">*</span></label>
                            <q-input
                                outlined
                                dense
                                v-model="name"
                                id="name"
                                ref="nameRef"
                                :rules="nameRules"
                                :error-message="getValidationErrors('name')"
                                :error="hasValidationErrors('name')"
                            />
                        </div>
                        <div class="form-group">
                            <label for="description" class="text-bold">Mô tả</label>
                            <q-input type="textarea" outlined dense id="description" v-model="description"></q-input>
                        </div>
                    </q-card>

                    <q-card class="widget meta-boxes">
                        <q-card-section>
                            <div class="widget-title text-bold">Cờ đánh dấu quyền hạn</div>
                        </q-card-section>
                        <q-separator/>
                        <q-slide-transition>

                            <q-card-section>
                                <div class="widget-body">
                                    <q-tree v-if="permissionArray.length > 0" class="col-12 col-sm-6"
                                            :nodes="permissionArray"
                                            v-model:ticked="ticked"
                                            node-key="code"
                                            label-key="name"
                                            tick-strategy="leaf"
                                            children-key="permissions"
                                            default-expand-all
                                    />
                                    <q-inner-loading
                                        :showing="loadingPermission"
                                        label-class="text-teal"
                                        label-style="font-size: 1.1em"
                                    />
                                </div>
                            </q-card-section>
                        </q-slide-transition>

                    </q-card>
                </div>
                <div class="col-3 right-sidebar">
                    <q-card class="widget meta-boxes action-horizontal">
                        <q-card>
                            <q-card-section>
                                <div class="widget-title text-bold">Tác vụ</div>
                            </q-card-section>
                            <q-separator/>
                            <q-card-section>

                                <q-btn @click="handleUpdateRole" no-caps color="secondary" class="q-mr-sm">
                                    <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                                    Lưu
                                </q-btn>
                                <q-btn @click="handleCreateRole" no-caps color="amber" class="q-mr-sm">
                                  <q-icon name="fa-solid fa-copy" class="q-mr-sm" size="xs"></q-icon>
                                  Nhân bản
                                </q-btn>
                                <q-btn @click="redirectRouter('Role')" no-caps color="warning" class="q-mr-sm">
                                    <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
                                    Quay lại
                                </q-btn>
                            </q-card-section>
                        </q-card>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref, watch} from "vue"
import api from "../../api"
import {HomeMutationTypes} from "../../store/modules/home/mutation-types"
import {useStore} from "vuex"
import {useQuasar} from "quasar"
import {useRoute, useRouter} from "vue-router/dist/vue-router"
import eventBus from "../../utils/eventBus"
import {validationHelper} from "../../utils/validationHelper"
import _ from "lodash";
import IRoleResult from "../../models/IRoleResult";
import IGroupPermissionResult from "../../models/IGroupPermissionResult";


export default defineComponent({
    name: "RoleUpdate",
    setup() {
        const store = useStore()
        const $q = useQuasar()
        const router = useRouter()
        const route = useRoute()
        const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()

        const roleId = ref<string>('')

        const name = ref<string>('')
        const nameRef = ref<any>(null)
        const nameRules = [
            val => (val && val.length > 0) || 'Trường tên vai trò không được bỏ trống!'
        ]

        const description = ref<string>('')

        const ticked = ref<any>([])
        const loadingPermission = ref<boolean>(false)
        const permissionArray = ref<any>([])

        const getPermissionGroup = (): void => {
            loadingPermission.value = true
            api.getPermissionGroups<IGroupPermissionResult>().then(res => {
                const permissions = {
                    name: 'Tất cả quyền hạn',
                    code: 'all',
                    permissions: _.get(res, 'data.data.groupPermission')
                }
                permissionArray.value.push(permissions)
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được danh sách quyền hạn!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => loadingPermission.value = false)
        }

        const handleGetRole = (id: string): void => {
            $q.loading.show()
            api.getRole<IRoleResult>(id).then(res => {
                name.value = _.get(res, 'data.data.role.name', '')
                description.value = _.get(res, 'data.data.role.description', '')
                let permission = _.get(res, 'data.data.role.permissions')
                ticked.value = permission ? permission.map(item => item.code) : []
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu vai trò!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }

        const handleCreateRole = (): void => {
            nameRef.value.validate()

            if (isValidate()) {
                $q.loading.show()
                let data = {
                    name: name.value + '(Copy)',
                    description: description.value,
                    permission_ids: ticked.value
                }

                api.createRole<IRoleResult>(data).then(res => {
                    if (res) {
                        eventBus.$emit('notify-success', 'Nhân bản thành công')
                        const id = _.get(res, 'data.data.role.id')
                        redirectRouter('RoleUpdate', {id: id})
                    }
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

        const handleUpdateRole = (): void => {
            nameRef.value.validate()

            if (isValidate()) {
                $q.loading.show()
                let data = {
                    name: name.value,
                    description: description.value,
                    permission_ids: ticked.value
                }

                api.updateRole(data, roleId.value).then(res => {
                    if (res) {
                        eventBus.$emit('notify-success', 'Cập nhật nhóm vai trò thành công')
                        redirectRouter('Role')
                    }
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

        const isValidate = (): boolean => {
            let isCheck = true

            if (nameRef.value.hasError) {
                isCheck = false
            }

            return isCheck
        }

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
            router.push({name: nameRoute, params: params})
        }

        watch(name, (): void => {
            resetValidateErrors('name')
            nameRef.value.resetValidation()
        })

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý phân quyền')
            roleId.value = <string>route.params.id
            handleGetRole(roleId.value)
            getPermissionGroup()
            eventBus.$on('notify-success', message => {
                $q.notify({
                    icon: 'check',
                    message: message,
                    color: 'positive',
                    position: 'top-right'
                })
            })
        })
        return {
            name,
            nameRef,
            nameRules,
            description,
            ticked,
            permissionArray,
            loadingPermission,
            redirectRouter,
            handleUpdateRole,
            getValidationErrors,
            hasValidationErrors,
            handleCreateRole
        }
    }
})
</script>

<style scoped lang="scss">
.role-wrapper {
    .main {
        margin-top: 20px;

        .main-form {
            padding: 10px;
            margin-bottom: 15px;

            .form-group {
                margin-bottom: 15px;
            }
        }
    }
}
</style>
