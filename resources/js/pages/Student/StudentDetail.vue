<template>
    <div class="student-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
            <q-breadcrumbs-el :to="{name: 'StudentIndex'}" label="Sinh viên"/>
            <q-breadcrumbs-el label="Thông tin sinh viên"/>
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-3 q-pr-lg">
                    <q-card class="main-form meta-boxes">
                        <q-card-section>
                            <div class="text-bold">Thông tin sinh viên</div>
                        </q-card-section>
                        <q-separator/>
                        <q-card-section>
                            <div class="q-pa-md">
                                <div class="avatar">
                                    <q-img class="avatar-student"
                                           :src="`${student.thumbnail_url}` ?? '/images/User-Default.jpg'"/>
                                </div>

                                <div class="student-name text-center">
                                    <div class="text-bold"> {{ student.full_name }}</div>
                                    <div>{{ student.student_code }}</div>
                                </div>
                                <q-separator/>
                                <div class="info-student q-pl-md  q-pb-md">

                                    <div class="item q-mt-md">
                                        <q-icon size="sm" class="fa-solid fa-envelope q-mr-sm"></q-icon>
                                        {{ student?.email_edu ? `${student?.email_edu}` : "Chưa cập nhật" }}
                                    </div>
                                    <div class="item q-mt-md">
                                        <q-icon size="sm" class="fa-solid fa-envelopes-bulk q-mr-sm"></q-icon>
                                        {{ student?.email ?? "Chưa cập nhật" }}
                                    </div>
                                    <div class="item q-mt-md">
                                        <q-icon size="sm" class="fa-solid fa-phone q-mr-sm"></q-icon>
                                        {{ student?.phone ?? "Chưa cập nhật" }}
                                    </div>
                                    <div class="item q-mt-md">

                                        <q-icon size="sm" class="fa-solid fa-location-dot q-mr-sm"></q-icon>
                                        {{ student?.countryside ?? "Chưa cập nhật" }}
                                    </div>

                                </div>
                                <q-separator/>
                                <div class="main-action q-mt-md text-center">
                                    <q-btn v-if="checkPermission('student-update')" color="secondary" class="q-mr-sm q-mb-sm"
                                           @click="redirectRouter('StudentUpdate',{id: student?.id})"
                                    >
                                        <q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                size="xs"></q-icon>
                                        Chỉnh sửa
                                    </q-btn>



                                    <q-btn v-if="checkPermission('student-delete')" color="red"  class="q-mb-sm"  @click="dialogDelete = true">
                                        <q-icon name="fa-solid fa-trash " class="q-mr-sm"
                                                size="xs"></q-icon>
                                        Xóa
                                    </q-btn>

                                    <q-btn v-if="checkPermission('student-update')" color="green" class="q-mr-sm q-mb-sm"
                                           @click="openDialogResetPassword"
                                    >
                                        <q-icon name="fa-solid fa-lock" class="q-mr-sm"
                                                size="xs"></q-icon>
                                        Đặt lại mật khẩu
                                    </q-btn>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-9">
                    <q-card>
                        <q-tabs
                            v-model="tab"
                            class="text-grey"
                            active-color="primary"
                            indicator-color="primary"
                            align="justify"
                            narrow-indicator
                        >
                            <q-tab name="home" label="Thông tin chung"/>
                            <q-tab name="learning_outcome" label="Kết quả học tập"/>
                        </q-tabs>

                        <q-separator/>

                        <q-tab-panels v-model="tab" animated>
                            <q-tab-panel name="home">
                                <div class="content q-pl-lg">
                                    <div class="row">
                                        <div class="col">
                                            <div class="item q-mb-md">
                                                <strong>Mã sinh
                                                    viên: </strong>{{ student?.student_code ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Lớp: </strong>{{
                                                    student?.general_class?.name ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Chuyên ngành: </strong>{{ student?.major ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>CMT/CCCD: </strong>{{
                                                    student?.citizen_identification ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Nơi sinh: </strong>{{ student.pob ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Quê quán: </strong>{{ student.countryside ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Dân tộc: </strong>{{ student.ethnic ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Tình trạng sinh viên: </strong>{{
                                                    student.status_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Chức vụ: </strong>{{ student.role_text ?? 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="item q-mb-md">
                                                <strong>Giới tính: </strong>{{
                                                    student?.gender_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Niên khóa: </strong>{{ student.countryside ?? 'Chưa cập nhật' }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Chương trình đào tạo: </strong>{{
                                                    student.training_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Ngày sinh: </strong>{{ student.dob ?? 'Chưa cập nhật' }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Hộ khẩu thường
                                                    trú: </strong>{{ student.permanent_residence ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Nơi ở hiện tại: </strong>{{ student.address ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Quốc tịch: </strong>{{ student.nationality ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Tôn giáo: </strong>{{ student.religion ?? 'Chưa cập nhật' }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Đối tượng chính sách xã
                                                    hội: </strong>{{
                                                    student.social_policy_object_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Ghi chú: </strong>{{ student.note ?? 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="family-wrapper q-mt-lg q-mb-lg">
                                                <label class="text-bold label-family">Thông tin gia đình</label>
                                                <div class="family-list q-pa-md">
                                                    <div class="row">
                                                        <div class="col-2 q-mr-sm">
                                                            <div class="form-group">

                                                            </div>
                                                        </div>
                                                        <div class="col-3 q-mr-sm">
                                                            <div class="form-group">
                                                                <label class="text-bold">Họ và tên</label>

                                                            </div>
                                                        </div>
                                                        <div class="col-3 q-mr-sm">
                                                            <div class="form-group">
                                                                <label class="text-bold">Nghề nghiệp</label>

                                                            </div>
                                                        </div>
                                                        <div class="col-2 q-mr-sm">
                                                            <div class="form-group">
                                                                <label class="text-bold">Số điện thoại</label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-for="(item, index) in student.families" :key="index"
                                                         class="row">
                                                        <div class="col-2 q-mr-sm">
                                                            <div class="form-group q-mt-sm">
                                                                <div class="text-bold">{{ item.relationship }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 q-mr-sm">
                                                            <div class="form-group q-mt-sm">
                                                                <div>{{ item.full_name }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 q-mr-sm">
                                                            <div class="form-group q-mt-sm">
                                                                <div>{{ item.job ?? 'Chưa cập nhật' }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 q-mr-sm">
                                                            <div class="form-group q-mt-sm">
                                                                <div>{{ item.phone ?? 'Chưa cập nhật' }}</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </q-tab-panel>

                            <q-tab-panel name="learning_outcome">
                                <div class="learning_outcome-header text-right">
                                    <q-btn no-caps @click="handleUpdateLearningOutcome" color="secondary" class="q-mr-sm">
                                        <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
                                        Cập nhật dữ liệu
                                    </q-btn>
                                </div>
                                <q-scroll-area v-if="student?.learning_outcomes.length > 0" style="height: 100vh">
                                    <div class="learning_outcome-content q-pl-sm q-pr-sm">
                                        <div class="item row"
                                             v-for="(learningOutcome, index) in student?.learning_outcomes ?? []">
                                            <div class="col">
                                                <div class="item-header q-mb-md">
                                                    <strong>Học kỳ {{ learningOutcome.semester }} - Năm học
                                                        {{
                                                            learningOutcome.year_school_start
                                                        }}-{{ learningOutcome.year_school_end }}</strong>
                                                </div>
                                                <div class="item-body">
                                                    <q-markup-table>
                                                        <thead>
                                                        <tr>
                                                            <th class="text-center">STT</th>
                                                            <th class="text-left">Mã môn học</th>
                                                            <th class="text-left">Tên môn học</th>
                                                            <th class="text-left">TC</th>
                                                            <th class="text-center">Điểm chuyên cần</th>
                                                            <th class="text-center">Điểm quá trình</th>
                                                            <th class="text-center">Điểm Thi</th>
                                                            <th class="text-center">Tổng kết(Điểm hệ 10)</th>
                                                            <th class="text-center">Tổng kết(Điểm chữ)</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(item, index) in learningOutcome.detail ?? []">
                                                            <td class="text-center">{{ item.order }}</td>
                                                            <td class="text-left">{{ item.subject_code }}</td>
                                                            <td class="text-left">{{ item.subject_name }}</td>
                                                            <td class="text-left">{{ item.credits }}</td>
                                                            <td class="text-center">{{ item.diligence_point }}</td>
                                                            <td class="text-center">{{ item.progress_point }}</td>
                                                            <td class="text-center">{{ item.exam_point }}</td>
                                                            <td class="text-center">{{ item.total_point_number }}</td>
                                                            <td class="text-center">{{ item.total_point_string }}</td>
                                                        </tr>
                                                        </tbody>
                                                    </q-markup-table>
                                                </div>
                                                <div class="item-footer">
                                                    <div class="q-mb-sm q-mt-md text-bold row">
                                                        <div class="col-3">Điểm trung bình học kỳ hệ 10/100</div>
                                                        <div class="col-9">
                                                            {{ learningOutcome.semester_average_10 ?? 'Chưa cập nhật' }}
                                                        </div>
                                                    </div>
                                                    <div class="q-mb-sm q-mt-md text-bold row">
                                                        <div class="col-3">Điểm trung bình học kỳ hệ 4:</div>
                                                        <div class="col-9">
                                                            {{ learningOutcome.semester_average_4 ?? 'Chưa cập nhật' }}
                                                        </div>
                                                    </div>
                                                    <div class="q-mb-sm q-mt-md text-bold row">
                                                        <div class="col-3">Điểm trung bình tích lũy:</div>
                                                        <div class="col-9">
                                                            {{
                                                                learningOutcome.cumulative_average_10 ?? 'Chưa cập nhật'
                                                            }}
                                                        </div>
                                                    </div>

                                                    <div class="q-mb-sm q-mt-md text-bold row">
                                                        <div class="col-3">Điểm trung bình tích lũy hệ 4:</div>
                                                        <div class="col-9">
                                                            {{
                                                                learningOutcome.cumulative_average_4 ?? 'Chưa cập nhật'
                                                            }}
                                                        </div>
                                                    </div>


                                                    <div class="q-mb-sm q-mt-md text-bold row">
                                                        <div class="col-3">Số tín chỉ đạt:</div>
                                                        <div class="col-9">{{
                                                                learningOutcome.credits ?? 'Chưa cập nhật'
                                                            }}
                                                        </div>
                                                    </div>


                                                    <div class="q-mb-sm q-mt-md text-bold row">
                                                        <div class="col-3">Số tín chỉ tích lũy:</div>
                                                        <div class="col-9">
                                                            {{ learningOutcome.cumulative_credits ?? 'Chưa cập nhật' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <q-separator class="q-mb-md"/>
                                            </div>
                                        </div>

                                    </div>
                                </q-scroll-area>
                                <div v-else class="text-center">
                                    <q-img src="/images/empty.png" width="500px"></q-img>
                                </div>
                                <q-inner-loading
                                    :showing="loading"
                                    label-class="text-teal"
                                    label-style="font-size: 1.1em"
                                />
                            </q-tab-panel>


                        </q-tab-panels>
                    </q-card>
                </div>
            </div>
        </div>
        <q-dialog v-model="dialogDelete" persistent>
            <q-card>
                <q-card-section class="row items-center">
                    <q-avatar icon="fa-solid fa-trash" color="red" text-color="white"/>
                    <span class="q-ml-sm">Bạn có chắc chắn muốn xóa! Dữ liệu không thể phục hồi!</span>
                </q-card-section>

                <q-card-actions align="right">
                    <q-btn flat label="Đóng" color="primary" @click="dialogDelete = false" v-close-popup/>
                    <q-btn label="Đồng ý" color="red" @click="handleDelete" v-close-popup/>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog v-model="popupResetPassword" persistent>
            <q-card style="width: 300px">
                <q-card-section>
                    <div class="text-h6">Đặt lại mật khẩu</div>
                </q-card-section>
                <q-card-section class="q-pt-none" style="width: 100%">
                    <label class="text-bold"
                    >Mật khẩu mới <span class="required">*</span></label
                    >
                    <q-input
                        outlined
                        dense
                        v-model="password"
                        id="passwordInput"
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
                        @click="popupResetPassword = false"
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

    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref} from "vue";
import useStudent from "../../uses/useStudent";
import {useRoute} from "vue-router";
import api from '../../api'
import {IStudentResult} from "../../models/IStudentResult";
import {useQuasar} from "quasar";
import {useRouter} from "vue-router/dist/vue-router";
import eventBus from "../../utils/eventBus";
import {validationHelper} from "../../utils/validationHelper";
import _ from "lodash";
import {permissionHelper} from "../../utils/permissionHelper";

export default defineComponent({
    name: "StudentDetail",
    setup() {
        const route = useRoute()
        const router = useRouter()
        const {student, getStudent} = useStudent()
        const userId = ref<string>('')
        const tab = ref<string>('home')
        const $q = useQuasar()
        const {checkPermission} = permissionHelper()

        const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
        } = validationHelper();

        const dialogDelete = ref<boolean>(false)

        onMounted(() => {
            userId.value = <string>route.params.id
            getStudent(parseInt(userId.value))
            eventBus.$on('notify-success', message => {
                $q.notify({
                    icon: 'check',
                    message: message,
                    color: 'positive',
                    position: 'top-right'
                })
            })
        })

        const loading = ref<boolean>(false)
        const handleUpdateLearningOutcome = () => {
            loading.value = true
            api.updateLearningOutcome<IStudentResult>(student.value.id).then(res => {
                student.value = _.get(res, 'data.data.student', {})
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không thể cập nhật dữ liệu',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => {
                loading.value = false
            })
        }

        const popupResetPassword = ref<boolean>(false)
        const isPwd = ref<boolean>(true)
        const password = ref<string>("")
        const refPassword = ref<any>(null)

        const openDialogResetPassword = (): void => {
            password.value = ""
            popupResetPassword.value = true;
        }

        const isRequest = ref<boolean>(false)
        const handleResetPassword = (): void => {
            if (!isRequest.value) {
                isRequest.value = true
                $q.loading.show()
                const data = {password: password.value}
                api.resetStudentPassword(parseInt(userId.value), data).then(res => {
                    $q.notify({
                        icon: 'check',
                        message: 'Đặt lại mật khẩu thành công',
                        color: 'positive',
                        position: 'top-right'
                    })
                    popupResetPassword.value = false
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

        const handleDelete = (): void => {
            $q.loading.show()
            api.deleteStudent(parseInt(student.value.id)).then((res) => {
                if (res) {
                    eventBus.$emit('notify-success', 'Xóa sinh viên thành công')
                    redirectRouter('StudentIndex')
                }
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không xóa được sinh viên!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
            router.push({name: nameRoute, params: params})
        }
        return {
            student,
            tab,
            handleUpdateLearningOutcome,
            loading,
            redirectRouter,
            dialogDelete,
            handleDelete,
            popupResetPassword,
            password,
            refPassword, openDialogResetPassword,
            isPwd,
            getValidationErrors,
            hasValidationErrors,
            handleResetPassword,
            checkPermission
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

            .avatar {
                text-align: center;

                .avatar-student {
                    height: 200px;
                    width: 200px;
                    border-radius: 5px;
                }
            }

            .student-name {
                margin: 10px;
                font-size: 20px;
            }
        }


        .family-wrapper {
            border: 1px solid #000000;
            border-radius: 5px;
            position: relative;

            .label-family {
                position: absolute;
                top: 0px;
                left: 5px;
                padding: 0px 10px;
                transform: translateY(-50%);
                background-color: #ffffff;
            }
        }

        .item {
            .item-header {
                font-size: 18px;
            }
        }
    }


}


</style>
