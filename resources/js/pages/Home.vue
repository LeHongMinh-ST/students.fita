<template>
    <div class="dashboard-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home"  />
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col q-pr-sm">
                    <q-card class="cursor-pointer" @click="redirectRouter('StudentIndex')">
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
                  <q-card class="cursor-pointer" @click="redirectRouter('Classes')">

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
                    <q-card class="cursor-pointer" @click="redirectRouter('ReportStudent')">
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
              <div class="col-5 q-pr-sm">
                <q-card>
                  <q-card-section>
                    <div class="text-bold header-title">
                      Yêu cầu duyệt thông tin
                    </div>
                  </q-card-section>
                  <q-card-section>
                    <q-markup-table class="request-table">
                      <thead>
                      <tr>
                        <th class="text-center" width="5%">STT</th>
                        <th class="text-left">Họ và tên</th>
                        <th class="text-left">Mã sinh viên</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-center">Tác vụ</th>
                      </tr>
                      </thead>
                      <tbody>
                      <template v-if="requests?.length ?? [].length > 0">
                        <tr v-for="(request, index) in requests" :key="index">
                          <td class="text-center">{{ index + 1 }}
                          </td>
                          <td class="text-left">
                                                <span @click="redirectRouter('RequestStudentDetail', { id: request.id })"
                                                      class="text-bold cursor-pointer text-link">
                                                    {{ getValueLodash(request, 'student.full_name', '') }}
                                                </span>
                          </td>
                          <td class="text-left">
                                                  <span @click="redirectRouter('RequestStudentDetail', { id: request.id })"
                                                        class="text-bold cursor-pointer text-link">
                                                      {{ getValueLodash(request, 'student_code', '') }}
                                                  </span>
                          </td>
                          <td class="text-center">
                            {{ handleFormatDate(getValueLodash(request, 'created_at', '')) }}
                          </td>

                          <td class="text-center">
                                                  <span @click="redirectRouter('RequestStudentDetail', { id: request.id })"
                                                        class="text-bold cursor-pointer text-link">
                                                        Chi tiết
                                                  </span>
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

              <div class="col-7 q-pl-sm">
                    <q-card>
                        <q-card-section>
                            <div class="text-bold header-title">
                                Phản ánh mới nhất
                            </div>
                        </q-card-section>
                        <q-card-section>
                            <q-markup-table class="report-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">STT</th>
                                        <th class="text-left">Tiêu đề</th>
                                        <th class="text-left">Chủ đề</th>
                                        <th class="text-center">Sinh viên</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-left">Được tạo bởi</th>
                                        <th class="text-left">Trạng thái</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="reports?.length ?? [].length > 0">
                                        <tr v-for="(report, index) in reports" :key="index">

                                            <td class="text-center">{{ index + 1 }}
                                            </td>
                                            <td class="text-left">
                                                <span @click="redirectRouter('RoleUpdate', { id: report.id })"
                                                    class="text-bold cursor-pointer text-link">
                                                    {{ getValueLodash(report, 'titile', '') }}
                                                </span>
                                            </td>
                                            <td class="text-left"> {{ getValueLodash(report, 'subject_text', '') }}</td>
                                            <td class="text-left"> {{ getValueLodash(report, 'student.full_name', '') }}</td>
                                            <td class="text-center">
                                                {{ this.handleFormatDate(getValueLodash(report, 'created_at', '')) }}
                                            </td>
                                            <td class="text-left">{{ getValueLodash(report, 'create_by.full_name', '') }}
                                            </td>
                                            <td class="text-left">{{ getValueLodash(report, 'status_text', '') }}
                                            </td>
                                            <td class="text-center">
                                                <span @click="redirectRouter('ReportStudent', { id: report.id })"
                                                      class="text-bold cursor-pointer text-link">
                                                      Chi tiết
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr>
                                            <td colspan="8" class="text-center">
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
import {formatDate, formatDateFM} from '../utils/helpers';

export default defineComponent({
    name: "Home",
    setup() {
        const $q = useQuasar()
        const store = useStore()
        const router = useRouter()

        const loading = ref<boolean>(false)

        const handleFormatDate = (date: any): string => {
            return formatDate(date)
        }

        const reports = ref([])
        const requests = ref([])

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
                reports.value = _.get(res, 'data.data.reports', [])
                requests.value = _.get(res, 'data.data.requests', [])
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
            dashboardObject,
            reports,
            requests,
            handleFormatDate
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
</style>
