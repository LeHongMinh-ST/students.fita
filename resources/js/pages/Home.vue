<template>
    <div class="dashboard-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{ name: 'Home' }" />
            <q-breadcrumbs-el label="Bảng điều khiển" />
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col q-pr-sm">
                    <q-card>
                        <q-card-section vert class="text-white vert bg-light-blue-8">
                            <div class="row">
                                <div class="col-10">
                                    <div class="text-h6">Sinh viên</div>
                                    <div class="text-h5">{{dashboardObject.studentCount}}</div>
                                </div>
                                <div class="col-2 items-center row">
                                    <q-icon class="fa-solid fa-address-card" size="xl"></q-icon>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>

                </div>
<!--                <div v-if="" class="col q-pr-sm q-pl-sm">-->
<!--                    <q-card>-->
<!--                        <q-card-section vert class="text-white vert bg-deep-orange-8">-->
<!--                            <div class="row">-->
<!--                                <div class="col-10">-->
<!--                                    <div class="text-h6">Giảng viên</div>-->
<!--                                    <div class="text-h5">{{dashboardObject.teacherCount}}</div>-->
<!--                                </div>-->
<!--                                <div class="col-2  items-center row">-->
<!--                                    <q-icon class="fa-solid fa-chalkboard-user" size="xl"></q-icon>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </q-card-section>-->
<!--                    </q-card>-->

<!--                </div>-->
                <div class="col q-pr-sm q-pl-sm">
                    <q-card>
                        <q-card-section vert class="text-white vert bg-teal-8">
                            <div class="row">
                                <div class="col-10">
                                    <div class="text-h6">Lớp học</div>
                                    <div class="text-h5">{{dashboardObject.classCount}}</div>
                                </div>
                                <div class="col-2 items-center row">
                                    <q-icon class="fa-solid fa-users" size="xl"></q-icon>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>

                </div>
                <div class="col  q-pl-sm">
                    <q-card>
                        <q-card-section vert class="text-white vert bg-orange-8">
                            <div class="row">
                                <div class="col-10">
                                    <div class="text-h6">Phản ánh</div>
                                    <div class="text-h5">{{dashboardObject.reportCount}}</div>
                                </div>
                                <div class="col-2 items-center row">
                                    <q-icon class="fa-solid fa-flag" size="xl"></q-icon>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>

                </div>
            </div>
            <div class="row q-mt-md">
                <div class="col-6 q-pr-sm">
                    <q-card>
                        <q-card-section>
                            <div class="text-bold header-title">
                                Phản ánh mới nhất
                            </div>
                        </q-card-section>
                        <q-card-section>
                            <q-markup-table class="role-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">STT</th>
                                        <th class="text-left">Tên nhóm</th>
                                        <th class="text-left">Mô tả</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-left">Được tạo bởi</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="roles?.length ?? [].length > 0">
                                        <tr v-for="(role, index) in roles" :key="index">

                                            <td class="text-center">{{
                                                    index + +1 + +pagePhanAnh['perPage'] * (pagePhanAnh['currentPage'] - 1)
                                            }}
                                            </td>
                                            <td class="text-left">
                                                <span @click="redirectRouter('RoleUpdate', { id: role.id })"
                                                    class="text-bold cursor-pointer text-link">
                                                    {{ getValueLodash(role, 'name', '') }}
                                                </span>
                                            </td>
                                            <td class="text-left"> {{ getValueLodash(role, 'description', '') }}</td>
                                            <td class="text-center">
                                                {{ this.handleFormatDate(getValueLodash(role, 'created_at', '')) }}
                                            </td>
                                            <td class="text-left">{{ getValueLodash(role, 'create_by.full_name', '') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="inline cursor-pointer">
                                                    <q-icon name="menu" size="sm"></q-icon>
                                                    <q-menu touch-position>
                                                        <q-list style="min-width: 100px">
                                                            <q-item clickable v-close-popup
                                                                @click="redirectRouter('RoleUpdate', { id: getValueLodash(role, 'id', 0) })">
                                                                <q-item-section>
                                                                    <span><q-icon name="fa-solid fa-pen-to-square"
                                                                            class="q-mr-sm" size="xs"></q-icon>Chỉnh
                                                                        sửa</span>
                                                                </q-item-section>
                                                            </q-item>
                                                            <q-item clickable v-close-popup
                                                                @click="this.openDialogDelete(getValueLodash(role, 'id', 0))">
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
                                                <img class="imgEmpty" src="/images/empty2.png" alt="">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>

                            </q-markup-table>

                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-6 q-pl-sm">
                    <q-card>
                        <q-card-section>
                            <div class="text-bold header-title">
                                Yêu cầu duyệt thông tin
                            </div>
                        </q-card-section>
                        <q-card-section>
                            <q-markup-table class="role-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">STT</th>
                                        <th class="text-left">Tên nhóm</th>
                                        <th class="text-left">Mô tả</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-left">Được tạo bởi</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="roles?.length ?? [].length > 0">
                                        <tr v-for="(role, index) in roles" :key="index">

                                            <td class="text-center">{{
                                                    index + +1 + +pageYcDuyetTT['perPage'] * (pageYcDuyetTT['currentPage'] - 1)
                                            }}
                                            </td>
                                            <td class="text-left">
                                                <span @click="redirectRouter('RoleUpdate', { id: role.id })"
                                                    class="text-bold cursor-pointer text-link">
                                                    {{ getValueLodash(role, 'name', '') }}
                                                </span>
                                            </td>
                                            <td class="text-left"> {{ getValueLodash(role, 'description', '') }}</td>
                                            <td class="text-center">
                                                {{ this.handleFormatDate(getValueLodash(role, 'created_at', '')) }}
                                            </td>
                                            <td class="text-left">{{ getValueLodash(role, 'create_by.full_name', '') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="inline cursor-pointer">
                                                    <q-icon name="menu" size="sm"></q-icon>
                                                    <q-menu touch-position>
                                                        <q-list style="min-width: 100px">
                                                            <q-item clickable v-close-popup
                                                                @click="redirectRouter('RoleUpdate', { id: getValueLodash(role, 'id', 0) })">
                                                                <q-item-section>
                                                                    <span><q-icon name="fa-solid fa-pen-to-square"
                                                                            class="q-mr-sm" size="xs"></q-icon>Chỉnh
                                                                        sửa</span>
                                                                </q-item-section>
                                                            </q-item>
                                                            <q-item clickable v-close-popup
                                                                @click="this.openDialogDelete(getValueLodash(role, 'id', 0))">
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
                                                <img class="imgEmpty" src="/images/empty3.png" alt="">
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>

                            </q-markup-table>

                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, ref } from 'vue'
import { HomeMutationTypes } from "../store/modules/home/mutation-types"
import { useStore } from "vuex"
import { useQuasar } from "quasar";
import { useRouter } from "vue-router";
import api from "../api";
import _ from "lodash";
import { formatDateFM } from '../utils/helpers';

export default defineComponent({
    name: "Home",
    setup() {
        const $q = useQuasar()
        const store = useStore()
        const router = useRouter()

        const loading = ref<boolean>(false)

        const roles = ref<any>([]);

        const pagePhanAnh = ref<Object>({
            currentPage: 1,
            total: 0,
            perPage: 10
        })

        const pageYcDuyetTT = ref<Object>({
            currentPage: 1,
            total: 0,
            perPage: 10
        })

        const handleFormatDate = (date: any): string => {
            if(date != null && date != undefined && date != ""){
                return formatDateFM(date, 'dd/MM/yyyy');
            }
            return "Chưa có dữ liệu";
        }

        const openDialogDelete = (id: number): void => {
            $q.dialog({
                title: 'Xác nhận',
                message: 'Bạn có chắc chắn muốn xoá nhóm vai trò này?',
                cancel: true,
                persistent: true
            }).onOk(() => {
                api.deleteRole(id.toString()).then((res: any) => {
                    $q.notify({
                        icon: 'check_circle',
                        message: 'Xoá nhóm vai trò thành công!',
                        color: 'positive',
                        position: 'top-right'
                    })
                    getDataRole();
                }).catch((err: any) => {
                    $q.notify({
                        icon: 'report_problem',
                        message: 'Xoá nhóm vai trò thất bại!',
                        color: 'negative',
                        position: 'top-right'
                    })
                })
            })
        }

        const getDataRole = (): void => {
            loading.value = true;
            console.log('loading');

            api.getRoles().then((res: any) => {
                console.log(res.data);
            }).catch((err: any) => {
                console.log(err);
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được danh sách nhóm vai trò!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => loading.value = false)

        }

        const dashboardObject = ref<Object>({
            classCount: 0,
            reportCount: 0,
            studentCount: 0,
            teacherCount: 0,
        })

        const getDataDashBoard = (): void => {
            loading.value = true;

            api.getDashboard().then((res: any) => {
                dashboardObject.value.classCount = _.get(res, 'data.data.classCount', 0)
                dashboardObject.value.reportCount = _.get(res, 'data.data.reportCount', 0)
                dashboardObject.value.studentCount = _.get(res, 'data.data.studentCount', 0)
                dashboardObject.value.teacherCount = _.get(res, 'data.data.teacherCount', 0)
            }).catch((err: any) => {
                console.log(err);
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => loading.value = false)

        }

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Bảng điều khiển');
            getDataDashBoard();
        })

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
            router.push({ name: nameRoute, params: params })
        }
        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d)
        }

        return {
            redirectRouter,
            getValueLodash,
            getDataDashBoard,
            roles,
            pagePhanAnh,
            pageYcDuyetTT,
            dashboardObject
        }
    }
})
</script>

<style scoped lang="scss">
.dashboard-wrapper {
    .main {
        margin-top: 20px;

        .header-title {
            font-size: 18px;
        }
    }
}
</style>
