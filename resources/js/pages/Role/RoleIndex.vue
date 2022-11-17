<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
            <q-breadcrumbs-el label="Nhóm và phân quyền"/>
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
                    <q-btn no-caps @click="redirectRouter('RoleCreate')" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                        Tạo mới
                    </q-btn>
                    <q-btn no-caps @click="getListRole" color="secondary" class="q-mr-sm">
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
                        <th class="text-left">Tên nhóm</th>
                        <th class="text-left">Mô tả</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-left">Được tạo bởi</th>
                        <th class="text-center">Tác vụ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="roles.length > 0">
                        <tr v-for="(role, index) in roles" :key="index">
                            <td class="text-center">
                                <q-checkbox v-model="checkboxArray" :val="getValueLodash(role, 'id', 0)"/>
                            </td>
                            <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
                            <td class="text-left">
                  <span @click="redirectRouter('RoleUpdate', {id: role.id})"
                        class="text-bold cursor-pointer text-link">
                    {{ getValueLodash(role, 'name', '') }}
                  </span>
                            </td>
                            <td class="text-left"> {{ getValueLodash(role, 'description', '') }}</td>
                            <td class="text-center">{{ handleFormatDate(getValueLodash(role, 'created_at', '')) }}</td>
                            <td class="text-left">{{ getValueLodash(role, 'create_by.full_name', '') }}</td>
                            <td class="text-center">
                                <div class="inline cursor-pointer">
                                    <q-icon name="menu" size="sm"></q-icon>
                                    <q-menu touch-position>
                                        <q-list style="min-width: 100px">
                                            <q-item clickable v-close-popup
                                                    @click="redirectRouter('RoleUpdate', {id: getValueLodash(role, 'id', 0)})">
                                                <q-item-section>
                                                    <span><q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                  size="xs"></q-icon>Chỉnh sửa</span>
                                                </q-item-section>
                                            </q-item>
                                            <q-item clickable v-close-popup
                                                    @click="openDialogDelete(getValueLodash(role, 'id', 0))">
                                                <span><q-icon name="fa-solid fa-trash" class="q-mr-sm"
                                                              size="xs"></q-icon>Xoá</span>
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
import IRoleResult from "../../models/IRoleResult";
import IPaginate from "../../models/IPaginate";
import usePage from "../../uses/usePage"

export default defineComponent({
    name: "RoleIndex",
    setup() {
        const $q = useQuasar()
        const store = useStore()
        const router = useRouter()
        const {page} = usePage()

        const search = ref<string>('')
        const dialogDelete = ref<boolean>(false)
        const dialogDeleteSelect = ref<boolean>(false)
        const roleId = ref<string>('')
        const roles = ref<IRoleResult[]>([])
        const roleIds = ref<string[]>([])

        const checkboxArray = ref<string[]>([])
        const checkboxAll = ref<boolean | string>(false)

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

        const getListRole = (): void => {

            loadingRoles.value = true
            const payload = {
                page: 1,
            }

            if (search.value) {
                payload.q = search.value
            }

            payload.page = page.value.currentPage

            api.getRoles<IPaginate<IRoleResult[]>>(payload).then(res => {
                roles.value = _.get(res, 'data.data.roles.data')
                page.value.currentPage = _.get(res, 'data.data.roles.current_page', 1)
                page.value.total = _.get(res, 'data.data.roles.last_page', 0)
                page.value.perPage = _.get(res, 'data.data.roles.per_page', 0)
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được danh sách nhóm vai trò!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => loadingRoles.value = false)
        }

        const openDialogDelete = (id: string): void => {
            dialogDelete.value = true
            roleId.value = id
        }
        const openDialogDeleteSelect = (id: string): void => {
            dialogDeleteSelect.value = true
        }

        const closeDialog = (): void => {
            dialogDelete.value = false
            dialogDeleteSelect.value = false
            roleId.value = ''
        }

        const handleDelete = (): void => {
            $q.loading.show()
            api.deleteRole(roleId.value).then(() => {
                getListRole()
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
                role_id: checkboxArray.value
            }
            api.deleteSelected(data).then(() => {
                getListRole()
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

        watch(() => page.value.currentPage, () => getListRole())
        watch(() => search.value, () => getListRole())
        watch(() => checkboxAll.value, (value) => {
            if (value === true) {
                checkboxArray.value = roleIds.value
            }

            if (value === false) {
                checkboxArray.value = []
            }

        })

        watch(() => checkboxArray.value, (value) => {
            if (value.length < roleIds.value.length) {
                checkboxAll.value = 'maybe'
            }

            if (value.length == 0) {
                checkboxAll.value = false
            }
        })

        onMounted((): void => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý phân quyền')
            eventBus.$on('notify-success', message => {
                $q.notify({
                    icon: 'check',
                    message: message,
                    color: 'positive',
                    position: 'top-right'
                })
            })
            getListRole()
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
            roles,
            loadingRoles,
            getListRole,
            page,
            dialogDelete,
            openDialogDelete,
            openDialogDeleteSelect,
            dialogDeleteSelect,
            closeDialog,
            checkboxArray,
            checkboxAll,
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
