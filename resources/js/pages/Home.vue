<template>
    <div class="dashboard-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el
                label="Bảng điều khiển"
                icon="home"
                :to="{ name: 'Home' }"
            />
            <q-breadcrumbs-el label="Bảng điều khiển"/>
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col q-pr-sm">
                    <q-card>
                        <q-card-section vert class="text-white vert bg-light-blue-8">
                            <div class="row">
                                <div class="col-10">
                                    <div class="text-h6">Sinh viên</div>
                                    <div class="text-h5">160</div>
                                </div>
                                <div class="col-2 items-center row">
                                    <q-icon class="fa-solid fa-chalkboard-user" size="xl"></q-icon>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>

                </div>
                <div class="col q-pr-sm q-pl-sm">
                    <q-card>
                        <q-card-section vert class="text-white vert bg-deep-orange-8">
                            <div class="row">
                                <div class="col-10">
                                    <div class="text-h6">Giảng viên</div>
                                    <div class="text-h5">160</div>
                                </div>
                                <div class="col-2  items-center row">
                                    <q-icon class="fa-solid fa-chalkboard-user" size="xl"></q-icon>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>

                </div>
                <div class="col q-pr-sm q-pl-sm">
                    <q-card>
                        <q-card-section vert class="text-white vert bg-teal-8">
                            <div class="row">
                                <div class="col-10">
                                    <div class="text-h6">Lớp</div>
                                    <div class="text-h5">160</div>
                                </div>
                                <div class="col-2 items-center row">
                                    <q-icon class="fa-solid fa-chalkboard-user" size="xl"></q-icon>
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
                                    <div class="text-h5">160</div>
                                </div>
                                <div class="col-2 items-center row">
                                    <q-icon class="fa-solid fa-chalkboard-user" size="xl"></q-icon>
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
                                <template v-if="roles?.length ?? [] > 0">
                                    <tr v-for="(role, index) in roles" :key="index">

                                        <td class="text-center">{{
                                                index + +1 + +page.perPage * (page.currentPage - 1)
                                            }}
                                        </td>
                                        <td class="text-left">
                                          <span @click="redirectRouter('RoleUpdate', {id: role.id})"
                                                class="text-bold cursor-pointer text-link">
                                            {{ getValueLodash(role, 'name', '') }}
                                          </span>
                                        </td>
                                        <td class="text-left"> {{ getValueLodash(role, 'description', '') }}</td>
                                        <td class="text-center">
                                            {{ handleFormatDate(getValueLodash(role, 'created_at', '')) }}
                                        </td>
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
                                <template v-if="roles?.length ?? [] > 0">
                                    <tr v-for="(role, index) in roles" :key="index">

                                        <td class="text-center">{{
                                                index + +1 + +page.perPage * (page.currentPage - 1)
                                            }}
                                        </td>
                                        <td class="text-left">
                                          <span @click="redirectRouter('RoleUpdate', {id: role.id})"
                                                class="text-bold cursor-pointer text-link">
                                            {{ getValueLodash(role, 'name', '') }}
                                          </span>
                                        </td>
                                        <td class="text-left"> {{ getValueLodash(role, 'description', '') }}</td>
                                        <td class="text-center">
                                            {{ handleFormatDate(getValueLodash(role, 'created_at', '')) }}
                                        </td>
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

                            </q-markup-table>

                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted} from 'vue'
import {HomeMutationTypes} from "../store/modules/home/mutation-types"
import {useStore} from "vuex"
import {useQuasar} from "quasar";
import {useRouter} from "vue-router";

export default defineComponent({
    name: "Home",
    setup() {
        const $q = useQuasar()
        const store = useStore()
        const router = useRouter()

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Bảng điều khiển')
        })

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
            router.push({name: nameRoute, params: params})
        }
        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d)
        }

        return {
            redirectRouter,
            getValueLodash
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
