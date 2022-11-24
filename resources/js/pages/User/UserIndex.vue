<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
            <q-breadcrumbs-el label="Quản lý người dùng"/>
        </q-breadcrumbs>
        <q-slide-transition>
            <q-card class="filter-wrapper" v-if="isFilter">
                <div class="filter-wrapper-content">
                    <div class="filter-header">
                        <div class="filter-header-text">Lọc dữ liệu</div>
                        <div class="filter-header-button">
                            <q-btn round icon="fa-solid fa-xmark" @click="closeFilter" size="sm"/>
                        </div>
                    </div>
                </div>
                <q-card-section>
                    <q-btn color="primary" no-caps outline class="q-mr-sm">
                        Thêm bộ lọc
                    </q-btn>
                    <q-btn color="primary" no-caps class="q-mr-sm">
                        Áp dụng
                    </q-btn>
                </q-card-section>
            </q-card>
        </q-slide-transition>
        <q-card class="table-wrapper">
            <q-card-section class="table-wrapper-title">
                <div class="table-wrapper-filter">
                    <q-slide-transition>
                        <q-btn v-if="checkboxArray.length > 0" color="primary" no-caps outline class="q-mr-sm">
                            Hành động
                            <q-icon name="fa-solid fa-caret-down" class="q-ml-sm" size="xs"></q-icon>

                            <q-menu>
                                <q-list style="min-width: 100px">
                                    <q-item clickable v-close-popup @click="openDialogDeleteSelect">
                                        <q-item-section>
                      <span><q-icon name="fa-solid fa-trash" class="q-mr-sm"
                                    size="xs"></q-icon>Xoá ({{ checkboxArray.length }} bản ghi)</span>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-menu>
                        </q-btn>
                    </q-slide-transition>
                    <q-btn class="q-mr-sm" no-caps color="primary" @click="toggleFilter">
                        <q-icon name="fa-solid fa-filter" class="q-mr-sm" size="xs"></q-icon>
                        Lọc dữ liệu
                    </q-btn>

                    <div class="table-wrapper-search">
                        <q-input bottom-slots v-model="search" label="Nhập từ khóa để tìm kiếm" outlined dense>

                            <template v-slot:append>
                                <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer"/>
                                <q-icon name="search"/>
                            </template>
                        </q-input>
                    </div>
                </div>
                <div class="table-wrapper-action">
                    <q-btn no-caps @click="redirectRouter('UserCreate')" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                        Tạo mới
                    </q-btn>
                    <q-btn no-caps @click="getListUser" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
                        Tải lại
                    </q-btn>
                </div>

            </q-card-section>
            <q-separator inset/>
            <q-card-section>
                <q-markup-table class="role-table">
                    <thead>
                    <tr>
                        <th class="text-center" width="4%">
                            <q-checkbox v-model="checkboxAll"/>
                        </th>
                        <th class="text-center" width="5%">STT</th>
                        <th class="text-left">Họ và tên</th>
                        <th class="text-left">Tên tài khoản</th>
                        <th class="text-left">Email</th>
                        <th class="text-left">Số điện thoại</th>
                        <th class="text-left">Chức danh</th>
                        <th class="text-center">Tác vụ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="users.length > 0">
                        <tr v-for="(user, index) in users" :key="index">
                            <td class="text-center">
                                  <q-checkbox v-model="checkboxArray" :val="String(getValueLodash(user, 'id', ''))"/>
                            </td>
                            <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
                            <td class="text-left">
                                  <span @click="redirectRouter('UserUpdate', {id: user.id})"
                                        class="text-bold cursor-pointer text-link">
                                    {{ getValueLodash(user, 'full_name', '') }}
                                  </span>
                            </td>
                            <td class="text-left"> {{ getValueLodash(user, 'user_name', '') }}</td>
                            <td class="text-left">{{ getValueLodash(user, 'email', '') }}</td>
                            <td class="text-left">{{ getValueLodash(user, 'phone', '') }}</td>
                            <td class="text-left">
                                <q-badge v-if="getValueLodash(user, 'is_super_admin', 0) != 0" align="middle" color='blue'>Quản trị viên</q-badge>
                                <q-badge v-if="getValueLodash(user, 'is_teacher', 0) != 0" align="middle" color='green'>Giảng viên</q-badge>
                            </td>
                            <td class="text-center">
                                <div class="inline cursor-pointer">
                                    <q-icon name="menu" size="sm"></q-icon>
                                    <q-menu touch-position>
                                        <q-list style="min-width: 100px">
                                            <q-item clickable v-close-popup
                                                    @click="redirectRouter('UserUpdate', {id: getValueLodash(user, 'id', 0)})">
                                                <q-item-section>
                                                    <span><q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                  size="xs"></q-icon>Chỉnh sửa</span>
                                                </q-item-section>
                                            </q-item>
                                            <q-item clickable v-close-popup
                                                    @click="openDialogDelete(getValueLodash(user, 'id', 0))">
                                                <span><q-icon name="fa-solid fa-trash" class="q-mr-sm"
                                                              size="xs"></q-icon>Xoá</span>
                                            </q-item>
                                            <q-item clickable v-close-popup
                                                    @click="openDialogResetPassword()">
                                                <span><q-icon name="fa-solid fa-trash" class="q-mr-sm"
                                                              size="xs"></q-icon>Reset mật khẩu</span>
                                            </q-item>
                                        </q-list>
                                    </q-menu>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td colspan="7" class="text-center">
                                <img class="imgEmpty" src="/images/empty.png" alt="">
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <q-inner-loading
                        :showing="loadingRoles"
                        label-class="text-teal"
                        label-style="font-size: 1.1em"
                    />
                </q-markup-table>
                <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
                    <q-pagination
                        v-model="page.currentPage"
                        :max="page.total"
                        direction-links
                        :max-pages="10"
                    />
                </div>
            </q-card-section>
        </q-card>
        <q-dialog v-model="dialogDelete" persistent>
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar icon="fa-solid fa-trash" color="red" text-color="white"/>
                    <span class="q-ml-sm">Bạn có chắc chắn muốn xóa! Dữ liệu không thể phục hồi!</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Đóng" color="primary" @click="closeDialog" v-close-popup/>
                    <q-btn label="Đồng ý" color="red" @click="handleDelete" v-close-popup/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="popupResetPassword" persistent>
            <q-card style="width: 300px">
                <q-card-section>
                    <div class="text-h6">Reset Password</div>
                </q-card-section>
                <q-card-section class="q-pt-none" style="width: 100%">
                <label for="password" class="text-bold"
                  >Mật khẩu mới <span class="required">*</span></label
                >
                <q-input
                  outlined
                  dense
                  v-model="password"
                  id="password"
                  :ref="refPassword"
                  :rules="[(val) =>(val && val.length > 0) || 'Trường mật khẩu không được bỏ trống']"
                  :error-message="getValidationErrors('password')"
                  :error="hasValidationErrors('password')"
                  :type="isPwd ? 'password' : 'text'"
                >
                  <template v-slot:append>
                    <q-icon
                      :name="isPwd ? 'visibility_off' : 'visibility'"
                      class="cursor-pointer"
                      @click="isPwd = !isPwd"
                    />
                  </template>
                </q-input>

        </q-card-section>
        <q-card-actions align="right" class="row">
          <q-btn
            flat
            label="Đóng"
            color="primary"
            @click="closeDialog"
            v-close-popup
          />
          <q-btn
            label="Đồng ý"
            color="blue"
            @click="handleResetPassword"
          />
        </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="dialogDeleteSelect" persistent>
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar icon="fa-solid fa-trash" color="red" text-color="white"/>
                    <span class="q-ml-sm">Bạn có chắc chắn muốn xóa {{ checkboxArray.length }} bản ghi! Dữ liệu không thể phục hồi!</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Đóng" color="primary" @click="closeDialog" v-close-popup/>
                    <q-btn label="Đồng ý" color="red" @click="handleDeleteSelect" v-close-popup/>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script lang="ts">
    import {defineComponent, onMounted, ref, watch} from "vue";
    import {useStore} from "vuex";
    import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
    import {useRouter} from "vue-router/dist/vue-router";
    import api from "../../api";
    import eventBus from "../../utils/eventBus";
    import {useQuasar} from "quasar";
    import {formatDate} from "../../utils/helpers";
    import IUserResult from "../../models/IUserResult";
    import IPaginate from "../../models/IPaginate";
    import { IPage, IPayload } from "../../models/IPage";
    import _ from "lodash";
import { validationHelper } from "../../utils/validationHelper";

    export default defineComponent({
        name: "UserIndex",
        components: {
        },
        setup() {
            const $q = useQuasar()
            const store = useStore()
            const router = useRouter()
            const popupResetPassword = ref<boolean>(false)
            const search = ref<string>('')
            const dialogDelete = ref<boolean>(false)
            const dialogDeleteSelect = ref<boolean>(false)
            const userId = ref<string>('')
            const users = ref<IUserResult[]>([])
            const roleIds = ref<Array<string>>([])
            const userIds = ref<Array<string>>([]);
            const password = ref<string>("")
            const refPassword = ref<any>(null)
            const checkboxArray = ref<Array<string>>([])
            const checkboxAll = ref<boolean | string>(false)
            const page = ref<IPage>({
                currentPage: 1,
                total: 0,
                perPage: 10
            })

            const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
            } = validationHelper();
            const isPwd = ref(true);


            const currentPage = ref<number>(1)

            const loadingRoles = ref<boolean>(false)
            const isFilter = ref<boolean>(false)
            const toggleFilter = (): void => {
                isFilter.value = !isFilter.value
            }
            const closeFilter = (): void => {
                isFilter.value = false
            }

            const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
                router.push({name: nameRoute, params: params})
            }

            const handleFormatDate = (value: string): string => {
                return formatDate(value)
            }

            const getListUser = (): void => {

                loadingRoles.value = true
                const payload: IPayload = {
                    page: 1,
                }

                if (search.value) {
                    payload.q = search.value
                }

                payload.page = page.value.currentPage

                api.getUsers<IPaginate<IUserResult[]>>(payload).then(res => {
                    users.value = _.get(res, 'data.data.users.data')
                    userIds.value = users.value.map(user => user.id.toString());
                    page.value.currentPage = _.get(res, 'data.data.users.current_page', 1)
                    page.value.total = _.get(res, 'data.data.users.last_page', 0)
                    page.value.perPage = _.get(res, 'data.data.users.per_page', 0)
                }).catch(() => {
                    $q.notify({
                        icon: 'report_problem',
                        message: 'Không tải được danh sách người dùng!',
                        color: 'negative',
                        position: 'top-right'
                    })
                }).finally(() => loadingRoles.value = false)
            }

            const openDialogDelete = (id: string): void => {
                dialogDelete.value = true
                userId.value = id
            }
            const openDialogDeleteSelect = (id: string): void => {
                dialogDeleteSelect.value = true
            }

            const openDialogResetPassword = (): void => {
                popupResetPassword.value = !popupResetPassword.value;
            }

            const handleResetPassword = (): void => {

            }

            const closeDialog = (): void => {
                dialogDelete.value = false
                dialogDeleteSelect.value = false
                userId.value = ''
            }

            const handleDelete = (): void => {
                $q.loading.show()
                api.deleteUser(userId.value).then(() => {
                    getListUser()
                    closeDialog()
                    $q.notify({
                        icon: 'check',
                        message: 'Xóa thành công nhóm vai trò',
                        color: 'positive',
                        position: 'top-right'
                    })
                }).catch(() => {
                    $q.notify({
                        icon: 'report_problem',
                        message: 'Không xóa được nhóm vai trò!',
                        color: 'negative',
                        position: 'top-right'
                    })
                }).finally(() => $q.loading.hide())
            }

            const getValueLodash = (res: object, data: string, d: any = null) => {
                return _.get(res, data, d)
            }

            const handleGetRoleIds = (): void => {
                api.getAllRoleId<number[]>().then(res => roleIds.value = _.get(res, 'data.data.roles', []))
            }

            const handleDeleteSelect = () => {
                $q.loading.show()
                const data = {
                    user_id: checkboxArray.value
                }
                api.deleteUserSelected(data).then(() => {
                    getListUser()
                    closeDialog()
                    checkboxArray.value = []
                    $q.notify({
                        icon: 'check',
                        message: 'Xóa thành công nhóm vai trò',
                        color: 'positive',
                        position: 'top-right'
                    })
                }).catch(() => {
                    $q.notify({
                        icon: 'report_problem',
                        message: 'Không xóa được nhóm vai trò!',
                        color: 'negative',
                        position: 'top-right'
                    })
                }).finally(() => $q.loading.hide())
            }

            watch(() => page.value.currentPage, () => getListUser())
            watch(() => search.value, () => getListUser())
            watch(() => checkboxAll.value, (value) => {
                if (value === true) {
                    checkboxArray.value = userIds.value;
                }

                if (value === false) {
                    checkboxArray.value = []
                }

            })

            watch(() => checkboxArray.value, (value) => {
                if (value.length < userIds.value.length) {
                    checkboxAll.value = 'maybe'
                }

                if (value.length == 0) {
                    checkboxAll.value = false
                }
            })

            onMounted((): void => {
                store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý người dùng')
                eventBus.$on('notify-success', (message: string) => {
                    $q.notify({
                        icon: 'check',
                        message: message,
                        color: 'positive',
                        position: 'top-right'
                    })
                })
                getListUser()
                handleGetRoleIds()
            })

            return {
                search,
                isFilter,
                toggleFilter,
                closeFilter,
                handleFormatDate,
                redirectRouter,
                getValueLodash,
                handleDelete,
                handleDeleteSelect,
                currentPage,
                users,
                loadingRoles,
                getListUser,
                page,
                dialogDelete,
                openDialogDelete,
                openDialogDeleteSelect,
                dialogDeleteSelect,
                closeDialog,
                checkboxArray,
                checkboxAll,
                openDialogResetPassword,
                popupResetPassword,
                handleResetPassword,
                password,
                refPassword,
                setValidationErrors,
                getValidationErrors,
                hasValidationErrors,
                resetValidateErrors,
                isPwd
            }
        }
    })
</script>

<style scoped lang="scss">
    .role-wrapper {
        .filter-wrapper {
            margin-top: 20px;
            margin-bottom: 20px;

            .filter-wrapper-content {
                padding: 10px 20px;

                .filter-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
            }
        }

        .table-wrapper {
            margin-top: 20px;

            .table-wrapper-title {
                padding: 0px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;

                .table-wrapper-filter {
                    width: 75%;
                    display: flex;
                    justify-content: flex-start;
                    align-items: center;

                    .q-btn {
                        height: 35px;
                    }

                    .table-wrapper-search {
                        margin-top: 20px;
                        width: 20vw;
                    }
                }
            }

            .role-table {
                tr {
                    th {
                        text-transform: uppercase;
                        font-weight: bold;
                        color: #949597;
                    }

                    td {
                        .text-link {
                            color: #337ab7;
                        }
                    }
                }
            }
        }
    }
</style>
