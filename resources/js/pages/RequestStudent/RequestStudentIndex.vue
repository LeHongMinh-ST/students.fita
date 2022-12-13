`
<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{ name: 'Home' }"/>
            <q-breadcrumbs-el label="Yêu cầu duyệt thông tin"/>
        </q-breadcrumbs>
        <q-slide-transition>
            <q-card class="filter-wrapper" v-if="isFilter">
                <div class="filter-wrapper-content">
                    <div class="filter-header">
                        <div class="filter-header-text text-bold">Lọc dữ liệu</div>
                        <div class="filter-header-button">
                            <q-btn round icon="fa-solid fa-xmark" @click="closeFilter" size="sm"/>
                        </div>
                    </div>
                </div>
                <q-card-section>
                    <div class="row">
                        <div class="col-4 q-mr-sm">
                            <div class="form-group">
                                <label class="text-bold">Trạng thái</label>
                                <q-select
                                    outlined
                                    dense
                                    v-model="filter.status_approved"
                                    :options="requestStatusArray"
                                    option-label="label"
                                    option-value="value"
                                    map-options
                                    emit-value
                                />
                            </div>
                        </div>
                    </div>
                </q-card-section>
                <q-card-section>
                    <q-btn color="secondary" no-caps class="q-mr-sm" @click="handleFilter">
                        <q-icon class="fa-solid fa-check q-mr-sm" size="sm"></q-icon>
                        Áp dụng
                    </q-btn>

                    <q-btn color="deep-orange-5" no-caps class="q-mr-sm" @click="clearFilter">
                        <q-icon class="fa-solid fa-redo q-mr-sm" size="sm"></q-icon>
                        Đặt lại
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
                                    <q-item clickable v-close-popup
                                            @click="handleChangeStatusSelect(checkTeacher() ? studentStatusTempEnum.TeacherApproved : studentStatusTempEnum.Approved)">
                                        <q-item-section>
                                          <span>
                                              <q-icon name="fa-solid fa-list-check" class="q-mr-sm" size="xs"></q-icon>Duyệt toàn bộ
                                              ({{ checkboxArray.length }} bản ghi)
                                          </span>
                                        </q-item-section>
                                    </q-item>
                                    <q-item clickable v-close-popup
                                            @click="handleChangeStatusSelect(studentStatusTempEnum.Reject)">
                                        <q-item-section>
                                          <span>
                                              <q-icon name="fa-solid fa-xmark" class="q-mr-sm" size="xs"></q-icon>Từ chối
                                              ({{ checkboxArray.length }} bản ghi)
                                          </span>
                                        </q-item-section>
                                    </q-item>
                                    <q-item clickable v-close-popup @click="dialogDeleteSelect = true">
                                        <q-item-section>
                                          <span>
                                              <q-icon name="fa-solid fa-trash" class="q-mr-sm" size="xs"></q-icon>Xoá toàn bộ
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
                        <q-input bottom-slots v-model="search" name="search" id="search"
                                 label="Nhập từ khóa để tìm kiếm" outlined
                                 dense>
                            <template v-slot:append>
                                <q-icon v-if="search !== ''" name="close" @click="search = ''" class="cursor-pointer"/>
                                <q-icon name="search"/>
                            </template>
                        </q-input>
                    </div>
                </div>
                <div class="table-wrapper-action">
                    <q-btn no-caps color="secondary" @click="handleGetRequestStudentTemp" class="q-mr-sm">
                        <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
                        Tải lại
                    </q-btn>
                </div>

            </q-card-section>
            <q-card-section>
                <q-markup-table class="role-table">
                    <thead>
                    <tr>
                        <th class="text-center" width="4%">
                            <q-checkbox v-model="checkboxAll"/>
                        </th>
                        <th class="text-center" width="5%">STT</th>
                        <th class="text-left">Mã sinh viên</th>
                        <th class="text-left">Họ và tên</th>
                        <th class="text-center">Ngày tạo</th>
                        <th class="text-left">Trạng thái yêu cầu</th>

                        <th class="text-left">Lớp trưởng duyệt</th>
                        <th class="text-left">Giáo viên duyệt</th>
                        <th class="text-left">Ban chủ nhiệm duyệt</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="requests && requests.length > 0">
                        <tr v-for="(item, index) in requests" :key="index">
                            <td class="text-center">
                                <q-checkbox v-model="checkboxArray" :val="getValueLodash(item, 'id', 0)"/>
                            </td>
                            <td class="text-center">
                                {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
                            </td>
                            <td class="text-left">
                                <span class="text-bold cursor-pointer text-link"
                                      @click="redirectRouter('RequestStudentDetail', {id: getValueLodash(item, 'id', 0)})">
                                    {{ getValueLodash(item, "student.student_code", "") ?? "Chưa cập nhật" }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="text-bold cursor-pointer text-link"
                                      @click="redirectRouter('RequestStudentDetail', {id: getValueLodash(item, 'id', 0)})">
                                    {{ getValueLodash(item, "student.full_name", "") ?? "Chưa cập nhật" }}
                                </span>
                            </td>
                            <td class="text-center">{{ handleFormatDate(getValueLodash(item, 'created_at', '')) }}</td>
                            <td class="text-left">
                                <q-badge
                                    v-if="getValueLodash(item, 'status_approved', 0) == studentStatusTempEnum.Pending"
                                    align="middle"
                                    color='deep-orange-5'>
                                    {{ getValueLodash(item, "status_approved_text", "Chưa cập nhật") }}

                                </q-badge>
                                <q-badge
                                    v-if="getValueLodash(item, 'status_approved', 0) == studentStatusTempEnum.ClassMonitorApproved "
                                    align="middle"
                                    color='blue'>
                                    {{ getValueLodash(item, "status_approved_text", "Chưa cập nhật") }}

                                </q-badge>
                                <q-badge
                                    v-if="getValueLodash(item, 'status_approved', 0) == studentStatusTempEnum.TeacherApproved "
                                    align="middle"
                                    color='teal'>
                                    {{ getValueLodash(item, "status_approved_text", "Chưa cập nhật") }}

                                </q-badge>
                                <q-badge
                                    v-if="getValueLodash(item, 'status_approved', 0) == studentStatusTempEnum.Approved "
                                    align="middle"
                                    color='green'>
                                    {{ getValueLodash(item, "status_approved_text", "Chưa cập nhật") }}

                                </q-badge>
                                <q-badge
                                    v-if="getValueLodash(item, 'status_approved', 0) == studentStatusTempEnum.Reject "
                                    align="middle"
                                    color='red'>
                                    {{ getValueLodash(item, "status_approved_text", "Chưa cập nhật") }}

                                </q-badge>
                            </td>
                            <td class="text-left">
                                {{ getValueLodash(item, "student_approved.full_name", "Chưa có") }}
                            </td>
                            <td class="text-left">
                                {{ getValueLodash(item, "teacher_approved.full_name", "Chưa có") }}
                            </td>
                            <td class="text-left">
                                {{ getValueLodash(item, "admin_approved.full_name", "Chưa có") }}
                            </td>
                            <td class="text-center">
                                <div class="inline cursor-pointer">
                                    <q-icon name="menu" size="sm"></q-icon>
                                    <q-menu touch-position>
                                        <q-list style="min-width: 100px">
                                            <q-item v-if="checkPermission('student-update')" clickable v-close-popup
                                                    @click="redirectRouter('RequestStudentDetail', {id: getValueLodash(item, 'id', 0)})">
                                                <q-item-section>
                                                    <span><q-icon name="fa-solid fa-eye" class="q-mr-sm"
                                                                  size="xs"></q-icon>Chi tiết</span>
                                                </q-item-section>
                                            </q-item>
                                            <q-item
                                                v-if="checkPermission('student-update') && checkStatusApproved(item)"
                                                clickable v-close-popup
                                                @click="handleChangeStatus(getValueLodash(item, 'id', 0), checkTeacher() ? studentStatusTempEnum.TeacherApproved : studentStatusTempEnum.Approved)">
                                                <span><q-icon name="fa-solid fa-check" class="q-mr-sm"
                                                              size="xs"></q-icon>Duyệt yêu cầu</span>
                                            </q-item>
                                            <q-item
                                                v-if="checkPermission('student-update') && checkStatusReject(item)"
                                                clickable v-close-popup
                                                @click="handleChangeStatus(getValueLodash(item, 'id', 0), studentStatusTempEnum.Reject)">
                                                <span><q-icon name="fa-solid fa-xmark" class="q-mr-sm"
                                                              size="xs"></q-icon>Từ chối yêu cầu</span>
                                            </q-item>
                                            <q-item v-if="checkPermission('student-update')" clickable v-close-popup
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
                                <img class="imgEmpty" src="/images/empty.png" alt=""/>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                    <q-inner-loading :showing="loading" label-class="text-teal" label-style="font-size: 1.1em"/>
                </q-markup-table>
                <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
                    <q-pagination v-model="page.currentPage" :max="page.total" direction-links :max-pages="10"/>
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
                    <q-btn flat label="Đóng" @click="dialogDelete = false" color="primary" v-close-popup/>
                    <q-btn label="Đồng ý" @click="handleDelete" color="red" v-close-popup/>
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
                    <q-btn flat label="Đóng" color="primary" @click="dialogDeleteSelect = false" v-close-popup/>
                    <q-btn label="Đồng ý" color="red" @click="handleDeleteSelect" v-close-popup/>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<script lang="ts">
import _ from "lodash";
import {useQuasar} from "quasar";
import {defineComponent, onMounted, ref, watch} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import {IPage} from "../../models/IPage";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import eventBus from "../../utils/eventBus";
import {formatDate} from "../../utils/helpers";
import {validationHelper} from "../../utils/validationHelper";
import api from "../../api";
import {permissionHelper} from "../../utils/permissionHelper";
import {StudentTempStatusEnum} from "../../enums/studentTempStatus.enum";
import {REQUEST_STATUS} from "../../utils/constants";

export default defineComponent({
    name: "RequestStudentIndex",
    setup() {
        const $q = useQuasar();
        const store = useStore();
        const studentCode = ref<string>("");
        const requests = ref<Array<any>>([]);
        const requestId = ref([])
        const {checkPermission, checkTeacher} = permissionHelper()
        const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors
        } = validationHelper()

        const studentStatusTempEnum = StudentTempStatusEnum

        const dialogDelete = ref<boolean>(false)

        const page = ref<IPage>({
            currentPage: 1,
            total: 0,
            perPage: 10,
        });

        const checkStatusReject = (item) => {
            const auth = store.getters["auth/getAuthUser"]
            if (item.status_approved == studentStatusTempEnum.Approved) {
                return false
            }

            if (auth.is_teacher && !auth.is_super_admin) {
                if(item.status_approved == studentStatusTempEnum.TeacherApproved) {
                    return false
                }
            }

            return true
        }

        const checkStatusApproved = (item) => {
            const auth = store.getters["auth/getAuthUser"]
            if (auth.is_teacher && !auth.is_super_admin) {
                return item.status_approved == studentStatusTempEnum.ClassMonitorApproved
            }

            if (!auth.is_teacher) {
                return item.status_approved == studentStatusTempEnum.TeacherApproved
            }
            if (auth.is_super_admin) {
                return true
            }
            return false
        }

        const currentPage = ref<number>(1);
        const loading = ref<boolean>(false);
        const isFilter = ref<boolean>(false);
        const router = useRouter();
        const checkboxArray = ref<string[]>([])
        const checkboxAll = ref<boolean | string>(false)
        const search = ref<string>('')
        const dialogDeleteSelect = ref<boolean>(false)
        const selectId = ref('')

        const toggleFilter = (): void => {
            isFilter.value = !isFilter.value;
        }
        const closeFilter = (): void => {
            isFilter.value = false;
        }

        const requestStatusArray = [{value: 0, label: 'Tất cả'}, ...REQUEST_STATUS]

        const clearFilter = () => {
            filter.value = _.cloneDeep({
                status_approved: 0,
            })
            handleGetRequestStudentTemp()
            handleGetRequestId()
        }

        const handleFilter = () => {
            handleGetRequestId()
            handleGetRequestStudentTemp()
        }

        const handleFormatDate = (value: string): string => {
            return formatDate(value);
        }


        const openDialogDelete = (id: string): void => {
            selectId.value = id
            dialogDelete.value = true
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

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
            router.push({
                name: nameRoute,
                params: params
            })
        }

        const filter = ref<object>({
            status_approved: 0
        })

        const handleAprove = (() => {
            eventBus.$emit('notify-success', 'Duyệt thành công');
        });

        const handleStop = (() => {
            eventBus.$emit('notify-success', 'Từ chối thành công');
        })

        watch(() => search.value, () => {
            handleGetRequestStudentTemp()
            handleGetRequestId()
        })

        const handleGetRequestStudentTemp = async (): Promise<void> => {
            loading.value = true
            const payload = {
                page: 1,
            }

            if (filter.value.status_approved) {
                payload.status_approved = filter.value.status_approved
            }

            if (search.value) {
                payload.q = search.value
            }

            payload.page = page.value.currentPage

            try {
                const res = await api.getRequestStudent(payload)
                requests.value = _.get(res, 'data.data.requests.data', [])
                page.value.currentPage = _.get(res, 'data.data.requests.current_page', 1)
                page.value.total = _.get(res, 'data.data.requests.last_page', 0)
                page.value.perPage = _.get(res, 'data.data.requests.per_page', 0)
            } catch (error) {
                generateNotify('Không thể tải dữ liệu')
            }
            loading.value = false
        }

        const handleGetRequestId = async (): Promise<void> => {
            try {
                const payload = {}
                if (filter.value.status_approved) {
                    payload.status_approved = filter.value.status_approved
                }

                if (search.value) {
                    payload.q = search.value
                }
                const res = await api.getRequestStudent(payload)
                const requestsArr = _.get(res, 'data.data.requests', [])
                requestId.value = requestsArr.map(item => item.id)
            } catch (error) {
                generateNotify('Không thể tải dữ liệu')
            }
        }

        const isRequest = ref<boolean>(false)

        const handleDeleteSelect = () => {
            if (!isRequest.value) {
                isRequest.value = true
                $q.loading.show()

                const data = {
                    request_ids: checkboxArray.value
                }

                api.deleteRequestSelected(data).then(() => {
                    handleGetRequestStudentTemp()
                    handleGetRequestId()
                    dialogDeleteSelect.value = false
                    checkboxArray.value = []
                    $q.notify({
                        icon: 'check',
                        message: 'Xóa thành công yêu cầu',
                        color: 'positive',
                        position: 'top-right'
                    })
                }).catch(() => {
                    $q.notify({
                        icon: 'report_problem',
                        message: 'Không xóa được yêu cầu',
                        color: 'negative',
                        position: 'top-right'
                    })
                }).finally(() => {
                    $q.loading.hide()
                    isRequest.value = false
                })
            }

        }

        const handleDelete = async () => {
            if (!isRequest.value) {
                isRequest.value = true
                try {
                    const res = await api.deleteRequest(parseInt(selectId.value))
                    if (res) {
                        generateNotify('Xóa thành công yêu cầu', true)
                    }
                } catch (error) {
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
                }
                isRequest.value = false
            }
        }

        const handleChangeStatus = async (id, status) => {
            if (!isRequest.value) {
                isRequest.value = true
                try {
                    const data = {
                        status: status
                    }
                    const res = await api.changeStatusRequest(id, data)
                    if (res) {
                        generateNotify('Cập nhật thành công', true)
                    }
                } catch (error) {
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
                }
                isRequest.value = false
            }


        }

        const handleChangeStatusSelect = async (status) => {
            if (!isRequest.value) {
                isRequest.value = true
                try {
                    const data = {
                        status: status,
                        request_ids: checkboxArray.value
                    }
                    const res = await api.changeStatusRequestSelect(data)
                    if (res) {
                        generateNotify('Cập nhật thành công', true)
                    }
                } catch (error) {
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
                }
                isRequest.value = false
            }


        }

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Phản ánh sinh viên");
            eventBus.$on("notify-success", (message: string) => {
                generateNotify(message, true)
            });

            handleGetRequestStudentTemp()
            handleGetRequestId()
        })

        watch(() => checkboxAll.value, (value) => {
            if (value === true) {
                checkboxArray.value = requestId.value
            }

            if (value === false) {
                checkboxArray.value = []
            }
        })

        watch(() => checkboxArray.value, (value) => {
            if (value.length < requestId.value.length) {
                checkboxAll.value = 'maybe'
            }

            if (value.length == 0) {
                checkboxAll.value = false
            }
        })

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
            requests,
            loading,
            page,
            redirectRouter,
            resetValidateErrors,
            checkboxArray,
            search,
            openDialogDelete,
            dialogDelete,
            handleAprove,
            handleStop,
            checkboxAll,
            requestId,
            checkPermission,
            handleGetRequestStudentTemp,
            studentStatusTempEnum,
            filter,
            dialogDeleteSelect,
            handleDeleteSelect,
            handleDelete,
            handleChangeStatus,
            checkTeacher,
            handleChangeStatusSelect,
            checkStatusApproved,
            requestStatusArray,
            clearFilter,
            handleFilter,
            checkStatusReject
        }
    },
})

</script>


<style scoped lang="scss">

.aprrove {
    height: 10px !important;
}

.text-link {
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

`
