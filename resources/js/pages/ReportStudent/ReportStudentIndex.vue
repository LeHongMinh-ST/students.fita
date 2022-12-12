<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{ name: 'Home' }" />
            <q-breadcrumbs-el label="Phản ánh sinh viên" />
        </q-breadcrumbs>
        <q-slide-transition>
            <q-card class="filter-wrapper" v-if="isFilter">
                <div class="filter-wrapper-content">
                    <div class="filter-header">
                        <div class="filter-header-text">Lọc dữ liệu</div>
                        <div class="filter-header-button">
                            <q-btn round icon="fa-solid fa-xmark" @click="closeFilter" size="sm" />
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
                                            <span>
                                                <q-icon name="fa-solid fa-trash" class="q-mr-sm" size="xs"></q-icon>Xoá
                                                ({{ checkboxArray.length }} bản ghi)
                                            </span>
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
                                <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer" />
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                </div>
                <div class="table-wrapper-action">
                    <q-btn no-caps @click="redirectRouter('ReportStudentCreate')" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                        Tạo mới
                    </q-btn>
                    <q-btn no-caps  color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
                        Tải lại
                    </q-btn>
                </div>

            </q-card-section>
            <q-card-section>
                <q-markup-table class="role-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">STT</th>
                            <th class="text-left">Mã sinh viên</th>
                            <th class="text-left">Tên sinh viên</th>
                            <th class="text-left">Chủ đề</th>
                            <th class="text-left">Trạng thái báo cáo</th>
                            <th class="text-left">Người tạo</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="reports && reports.length > 0">
                            <tr v-for="(item, index) in reports" :key="index">
                               
                                <td class="text-center">
                                    {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
                                </td>
                                <td class="text-left">
                                    <span class="text-bold cursor-pointer text-link" @click="redirectRouter('ReportStudentDetail', {id: getValueLodash(item, 'id', 0)})">
                                        {{ getValueLodash(item, "title", "") ?? "Chưa cập nhật"}}
                                    </span>
                                </td>
                                <td class="text-left">
                                    <span class="text-bold cursor-pointer text-link" @click="redirectRouter('ReportStudentDetail', {id: getValueLodash(item, 'id', 0)})">
                                        {{ getValueLodash(item, "student.full_name", "") ?? "Chưa cập nhật"}}
                                    </span>
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "subject_text", "") ?? "Chưa cập nhật"}}
                                </td>
                                <td class="text-left" >
                                    <q-badge v-if="getValueLodash(item, 'status', 0) == reportStatusEnum.Pending " align="middle" color='orange'>
                                        {{ getValueLodash(item, "status_text", "") ?? "Chưa cập nhật"}}
                                     </q-badge>
                                     <q-badge v-if="getValueLodash(item, 'status', 0) == reportStatusEnum.Seen " align="middle" color='blue'>
                                        {{ getValueLodash(item, "status_text", "") ?? "Chưa cập nhật"}}
                                     </q-badge>
                                     <q-badge v-if="getValueLodash(item, 'status', 0) == reportStatusEnum.Approved " align="middle" color='green'>
                                        {{ getValueLodash(item, "status_text", "") ?? "Chưa cập nhật"}}
                                     </q-badge>
                                    
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "created_by.full_name", "Chưa cập nhật") ?? "Chưa cập nhật"}}
                                </td>
                                
                                <td class="text-center">
                                    <div class="inline cursor-pointer">
                                        <q-icon name="menu" size="sm"></q-icon>
                                        <q-menu touch-position>
                                            <q-list style="min-width: 100px">
                                                <q-item clickable v-close-popup
                                                    @click="redirectRouter('ReportStudentUpdate', {id: getValueLodash(item, 'id', 0) })">
                                                    <q-item-section>
                                                        <span>
                                                            <q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                size="xs"></q-icon>Chỉnh sửa
                                                        </span>
                                                    </q-item-section>
                                                </q-item>
                                                <q-item clickable v-close-popup
                              @click="openDialogDelete(getValueLodash(item, 'id', 0))">
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
                                <td colspan="11" class="text-center">
                                    <img class="imgEmpty" src="/images/empty.png" alt="" />
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <q-inner-loading :showing="loading" label-class="text-teal" label-style="font-size: 1.1em" />
                </q-markup-table>
                <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
                    <q-pagination v-model="page.currentPage" :max="page.total" direction-links :max-pages="10" />
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
    </div>
</template>

<script lang="ts">
    import _ from "lodash";
    import {
        useQuasar
    } from "quasar";
    import {
        defineComponent,
        onMounted,
        ref,
        watch
    } from "vue";
    import {
        useRouter
    } from "vue-router";
    import {
        useStore
    } from "vuex";
    import api from "../../apiStudent";
    import {
        IPage,
        StudentFilter
    } from "../../models/IPage";
    import IPaginate from "../../models/IPaginate";
    import {
        HomeMutationTypes
    } from "../../store/modules/home/mutation-types";
    import eventBus from "../../utils/eventBus";
    import {
        formatDate
    } from "../../utils/helpers";
    import {
        validationHelper
    } from "../../utils/validationHelper";

    import {ReportStatusEnum} from "../../enums/reportStatus.enum";

    // import DeleteDepartment from "./Delete.vue";

    export default defineComponent({
        name: "ReportStudentIndex",
        setup() {
            const $q = useQuasar();
            const store = useStore();
            const studentCode = ref < string > ("");
            const items = ref < Array < any >> ([]);
            const itemIDs = ref < Array < number >> ([]);
            const reports = ref<Array<any>>([])
            const reportId = ref<string>('')
            const {
                setValidationErrors,
                getValidationErrors,
                hasValidationErrors,
                resetValidateErrors
            } = validationHelper()
            const dialogDelete = ref<boolean>(false)
            const page = ref < IPage > ({
                currentPage: 1,
                total: 0,
                perPage: 10,
            });
            const loadingClasses = ref<boolean>(false)
            const currentPage = ref < number > (1);
            const loading = ref < boolean > (false);
            const isFilter = ref < boolean > (false);
            const router = useRouter();
            const checkboxArray = ref < string[] > ([])
            const search = ref < string > ('')
            const toggleFilter = (): void => {
                isFilter.value = !isFilter.value;
            };
            const closeFilter = (): void => {
                isFilter.value = false;
            };

            const handleFormatDate = (value: string): string => {
                return formatDate(value);
            };
           
            
            const openDialogDelete = (id: string): void => {
                dialogDelete.value = true
                reportId.value =id;
            }
            
            const closeDialog = (): void => {
                dialogDelete.value = false
                // dialogDeleteSelect.value = false
                reportId.value = ''
            }
            const handleDelete = () => {
                $q.loading.show()
                const data = {
                    id: checkboxArray.value
                }
                api.deleteStudentReport(reportId.value).then(() => {
                    getListReport()
                    closeDialog()
                    checkboxArray.value = []
                    $q.notify({
                    icon: 'check',
                    message: 'Xóa thành công phản ánh',
                    color: 'positive',
                    position: 'top-right'
                    })
                }).catch(() => {
                    $q.notify({
                    icon: 'report_problem',
                    message: 'Không xóa được phản ánh!',
                    color: 'negative',
                    position: 'top-right'
                    })
                }).finally(() => $q.loading.hide())
            }

            const getValueLodash = (res: object, data: string, d: any = null) => {
                return _.get(res, data, d);
            };

            const generateNotify = (message: string, isSuccess = false) => {
                isSuccess ? $q.notify({
                        icon: "check",
                        message: message,
                        color: "positive",
                        position: "top-right",
                    }) :
                    $q.notify({
                        icon: "report_problem",
                        message: message,
                        color: "negative",
                        position: "top-right"
                    })
            }

            const reportStatusEnum = ReportStatusEnum;
            const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
                router.push({
                    name: nameRoute,
                    params: params
                })
            }

            const gender = {
                "0": "Nam",
                "1": "Nữ"
            }

            const getListReport = (): void => {

                loadingClasses.value = true
                const payload = {
                    page: 1,
                }

                if (search.value) {
                    payload.q = search.value
                }

                payload.page = page?.value?.currentPage;

                api.getAllReportStudent<IPaginate<[]>>(payload).then(res => {
                reports.value = _.get(res, 'data.data.reports.data')

                console.log("aaaaaaaaaaa "+reports);
                page.value.currentPage = _.get(res, 'data.data.reports.current_page', 1)
                page.value.total = _.get(res, 'data.data.reports.last_page', 0)
                page.value.perPage = _.get(res, 'data.data.reports.per_page', 0)        
                }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải danh sách phản ánh!',
                    color: 'negative',
                    position: 'top-right'
                })
                }).finally(() => loadingClasses.value = false)
            }


            onMounted(() => {
                store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Phản ánh sinh viên");
                eventBus.$on("notify-success", (message: string) => {
                    generateNotify(message, true)
                });
                
                getListReport();
            });

            

            return {
                studentCode,
                isFilter,
                toggleFilter,
                closeFilter,
                handleFormatDate,
                getValidationErrors,
                hasValidationErrors,
                getValueLodash,
                currentPage,
                items,
                loading,
                page,
                gender,
                redirectRouter,
                resetValidateErrors,
                checkboxArray,
                search,
                openDialogDelete,
                dialogDelete,
                reports,
                reportStatusEnum,handleDelete,closeDialog
            };
        },
    });

</script>


<style scoped lang="scss">
.text-link{
    color: #337ab7 !important;
}

.table-wrapper-search {
          margin-top: 20px;
          width: 20vw;
        }
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
            color: #337ab7 !important;
          }
        }
      }
    }
  }
}

</style>

