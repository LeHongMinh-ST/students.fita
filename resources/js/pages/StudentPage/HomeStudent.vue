<template>
    <div class="student-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Hồ sơ sinh viên" icon="home" :to="{ name: 'HomeStudent' }" />
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-3 q-pr-lg">
                    <q-card class="main-form meta-boxes">
                        <q-card-section>
                            <div class="text-bold">Thông tin sinh viên</div>
                        </q-card-section>
                        <q-separator />
                        <q-card-section>
                            <div class="q-pa-md">
                                <div class="avatar">
                                    <q-img class="avatar-student"
                                        :src="`${auth.thumbnail_url}` ?? '/images/User-Default.jpg'" />
                                </div>

                                <div class="student-name text-center">
                                    <div class="text-bold"> {{ auth.full_name }}</div>
                                    <div>{{ auth.student_code }}</div>
                                </div>
                                <q-separator />
                                <div class="info-student q-pl-md  q-pb-md">

                                    <div class="item q-mt-md">
                                        <q-icon size="sm" class="fa-solid fa-envelope q-mr-sm"></q-icon>
                                        {{ auth?.email_edu ? `${auth?.email_edu}` : "Chưa cập nhật" }}
                                    </div>
                                    <div class="item q-mt-md">
                                        <q-icon size="sm" class="fa-solid fa-envelopes-bulk q-mr-sm"></q-icon>
                                        {{ auth?.email ?? "Chưa cập nhật" }}
                                    </div>
                                    <div class="item q-mt-md">
                                        <q-icon size="sm" class="fa-solid fa-phone q-mr-sm"></q-icon>
                                        {{ auth?.phone ?? "Chưa cập nhật" }}
                                    </div>
                                    <div class="item q-mt-md">

                                        <q-icon size="sm" class="fa-solid fa-location-dot q-mr-sm"></q-icon>
                                        {{ auth?.countryside ?? "Chưa cập nhật" }}
                                    </div>
                                </div>
                                <q-separator />
                                <div class="main-action q-mt-md text-center">
                                    <q-btn color="secondary" class="q-mr-sm q-mb-sm"
                                        @click="redirectRouter('StudentUpdateProfile')">
                                        <q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm" size="xs"></q-icon>
                                        Chỉnh sửa
                                    </q-btn>

                                    <q-btn color="green" class="q-mr-sm q-mb-sm" @click="handleOpenResetPassword">
                                        <q-icon name="fa-solid fa-lock" class="q-mr-sm" size="xs"></q-icon>
                                        Đặt lại mật khẩu
                                    </q-btn>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-9">
                    <q-card>
                        <q-tabs v-model="tab" class="text-grey" active-color="primary" indicator-color="primary"
                            align="justify" narrow-indicator>
                            <q-tab name="home" label="Thông tin chung" />
                            <q-tab name="learning_outcome" label="Kết quả học tập" />
                            <q-tab name="request" label="Danh sách yêu cầu" />
                        </q-tabs>
                        <q-separator />

                        <q-tab-panels v-model="tab" animated>
                            <q-tab-panel name="home">
                                <div class="content q-pl-lg">
                                    <div class="row">
                                        <div class="col">
                                            <div class="item q-mb-md">
                                                <strong>Mã sinh
                                                    viên: </strong>{{ auth?.student_code ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Lớp: </strong>{{
                                                        auth?.general_class?.name ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Chuyên ngành: </strong>{{ auth?.major ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>CMT/CCCD: </strong>{{
                                                        auth?.citizen_identification ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Nơi sinh: </strong>{{ auth.pob ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Quê quán: </strong>{{ auth.countryside ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Dân tộc: </strong>{{ auth.ethnic ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Tình trạng sinh viên: </strong>{{
                                                        auth.status_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Chức vụ: </strong>{{ auth.role_text ?? 'Chưa cập nhật' }}
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="item q-mb-md">
                                                <strong>Giới tính: </strong>{{
                                                        auth?.gender_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Niên khóa: </strong>{{ auth.countryside ?? 'Chưa cập nhật' }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Chương trình đào tạo: </strong>{{
                                                        auth.training_text ?? 'Chưa cập nhật'
                                                }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Ngày sinh: </strong>{{ auth.dob ?? 'Chưa cập nhật' }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Hộ khẩu thường
                                                    trú: </strong>{{ auth.permanent_residence ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Quốc tịch: </strong>{{ auth.nationality ?? 'Chưa cập nhật' }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Tôn giáo: </strong>{{ auth.religion ?? 'Chưa cập nhật' }}
                                            </div>

                                            <div class="item q-mb-md">
                                                <strong>Đối tượng chính sách xã
                                                    hội: </strong>{{
                                                            auth.social_policy_object_text ?? 'Chưa cập nhật'
                                                    }}
                                            </div>
                                            <div class="item q-mb-md">
                                                <strong>Ghi chú: </strong>{{ auth.note ?? 'Chưa cập nhật' }}
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
                                                    <div v-for="(item, index) in auth.families" :key="index"
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
                                    <q-btn no-caps @click="handleUpdateLearningOutcome" color="secondary"
                                        class="q-mr-sm">
                                        <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
                                        Cập nhật dữ liệu
                                    </q-btn>
                                </div>
                                <q-scroll-area v-if="auth?.learning_outcomes.length > 0" style="height: 100vh">
                                    <div class="learning_outcome-content q-pl-sm q-pr-sm">
                                        <div class="item row" v-for="(learningOutcome) in auth?.learning_outcomes ?? []"
                                            :key="learningOutcome.id">
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
                                                            <tr v-for="(item) in learningOutcome.detail ?? []"
                                                                :key="item.id">
                                                                <td class="text-center">{{ item.order }}</td>
                                                                <td class="text-left">{{ item.subject_code }}</td>
                                                                <td class="text-left">{{ item.subject_name }}</td>
                                                                <td class="text-left">{{ item.credits }}</td>
                                                                <td class="text-center">{{ item.diligence_point }}</td>
                                                                <td class="text-center">{{ item.progress_point }}</td>
                                                                <td class="text-center">{{ item.exam_point }}</td>
                                                                <td class="text-center">{{ item.total_point_number }}
                                                                </td>
                                                                <td class="text-center">{{ item.total_point_string }}
                                                                </td>
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
                                                <q-separator class="q-mb-md" />
                                            </div>
                                        </div>

                                    </div>
                                </q-scroll-area>
                                <div v-else class="text-center">
                                    <q-img src="/images/empty.png" width="500px"></q-img>
                                </div>
                                <q-inner-loading :showing="loading" label-class="text-teal"
                                    label-style="font-size: 1.1em" />
                            </q-tab-panel>
                            <q-tab-panel name="request">
                                <q-markup-table class="request-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center" width="5%">STT</th>
                                        <th class="text-center">Ngày tạo</th>
                                        <th class="text-left">Trạng thái</th>
                                        <th class="text-left">Lớp trưởng duyệt</th>
                                        <th class="text-left">Giáo viên duyệt</th>
                                        <th class="text-left">Ban chủ nhiệm duyệt</th>
                                        <th class="text-center">Tác vụ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <template v-if="myRequest.length > 0">
                                        <tr v-for="(request, index) in myRequest" :key="index">
                                            <td class="text-center">{{ index + 1 }}
                                            </td>
                                            <td class="text-center">{{ handleFormatDate(getValueLodash(request, 'created_at', '')) }}</td>

                                            <td class="text-left">
                                                <q-badge
                                                    v-if="getValueLodash(request, 'status_approved', 0) == studentStatusTempEnum.Pending"
                                                    align="middle"
                                                    color='deep-orange-5'>
                                                    {{ getValueLodash(request, "status_approved_text", "Chưa cập nhật") }}

                                                </q-badge>
                                                <q-badge
                                                    v-if="getValueLodash(request, 'status_approved', 0) == studentStatusTempEnum.ClassMonitorApproved "
                                                    align="middle"
                                                    color='blue'>
                                                    {{ getValueLodash(request, "status_approved_text", "Chưa cập nhật") }}

                                                </q-badge>
                                                <q-badge
                                                    v-if="getValueLodash(request, 'status_approved', 0) == studentStatusTempEnum.TeacherApproved "
                                                    align="middle"
                                                    color='teal'>
                                                    {{ getValueLodash(request, "status_approved_text", "Chưa cập nhật") }}

                                                </q-badge>
                                                <q-badge
                                                    v-if="getValueLodash(request, 'status_approved', 0) == studentStatusTempEnum.Approved "
                                                    align="middle"
                                                    color='green'>
                                                    {{ getValueLodash(request, "status_approved_text", "Chưa cập nhật") }}

                                                </q-badge>
                                                <q-badge
                                                    v-if="getValueLodash(request, 'status_approved', 0) == studentStatusTempEnum.Reject "
                                                    align="middle"
                                                    color='red'>
                                                    {{ getValueLodash(request, "status_approved_text", "Chưa cập nhật") }}

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
                                                            <q-item  clickable v-close-popup
                                                                    @click="redirectRouter('RoleUpdate', {id: getValueLodash(role, 'id', 0)})">
                                                                <q-item-section>
                                                    <span><q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                  size="xs"></q-icon>Chỉnh sửa</span>
                                                                </q-item-section>
                                                            </q-item>
                                                            <q-item  clickable v-close-popup
                                                                    @click="openDialogDelete(getValueLodash(request, 'id', 0))">
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
                                <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
                                    <q-pagination
                                        v-model="page.currentPage"
                                        :max="page.total"
                                        direction-links
                                        :max-pages="10"
                                    />
                                </div>

                            </q-tab-panel>

                        </q-tab-panels>
                    </q-card>
                </div>
            </div>
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
                                <label class="text-bold">Mật khẩu cũ <span class="required">*</span></label>
                                <q-input :type="isPwd ? 'password' : 'text'" outlined dense v-model="password_old"
                                    :error-message="getValidationErrors('password_old')"
                                    :error="hasValidationErrors('password_old')"
                                    @update:model-value="() => resetValidateErrors('password_old')">
                                    <template v-slot:append>
                                        <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                                            @click="isPwd = !isPwd" />
                                    </template>
                                </q-input>
                            </div>
                        </div>
                        <div class="col-6 q-pr-sm">
                            <div class="form-group">
                                <label class="text-bold">Mật khẩu mới <span class="required">*</span></label>
                                <q-input :type="isPwd ? 'password' : 'text'" outlined dense v-model="password"
                                    :error-message="getValidationErrors('password')"
                                    :error="hasValidationErrors('password')"
                                    @update:model-value="() => resetValidateErrors('password')">
                                    <template v-slot:append>
                                        <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                                            @click="isPwd = !isPwd" />
                                    </template>
                                </q-input>
                            </div>
                        </div>
                        <div class="col-6 q-pr-sm">
                            <div class="form-group">
                                <label class="text-bold">Xác nhận mật khẩu <span class="required">*</span></label>
                                <q-input :type="isPwd ? 'password' : 'text'" outlined dense v-model="password_confirm"
                                    :error-message="getValidationErrors('password_confirm')"
                                    :error="hasValidationErrors('password_confirm')"
                                    @update:model-value="() => resetValidateErrors('password_confirm')">
                                    <template v-slot:append>
                                        <q-icon :name="isPwd ? 'visibility_off' : 'visibility'" class="cursor-pointer"
                                            @click="isPwd = !isPwd" />
                                    </template>
                                </q-input>
                            </div>
                        </div>
                    </div>
                </q-card-section>
                <q-card-actions align="right" class="row">
                    <q-btn flat label="Đóng" color="primary" @click="() => isShowDialogResetPassword = false"
                        v-close-popup />
                    <q-btn label="Lưu" color="blue" @click="handleResetPassword" />
                </q-card-actions>
            </q-card>
        </q-dialog>
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
    </div>
</template>


<script lang="ts">
import {defineComponent, onMounted, ref, watch} from "vue";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useStore} from "vuex"
import {useRouter} from "vue-router";
import {useQuasar} from "quasar";
import apiStudent from "../../apiStudent";
import {IStudentResult} from "../../models/IStudentResult";
import _ from "lodash";
import IUserResult from "../../models/IUserResult";
import {AuthStudentMutationTypes} from "../../store/modules/auth_student/mutation-types";
import {validationHelper} from "../../utils/validationHelper";
import eventBus from "../../utils/eventBus";
import usePage from "../../uses/usePage";
import {StudentTempStatusEnum} from "../../enums/studentTempStatus.enum";
import {formatDate} from "../../utils/helpers";

export default defineComponent({
    name: "HomeStudent",
    setup() {
        const store = useStore()
        const loading = ref<boolean>(false)
        const router = useRouter()
        const tab = ref<string>('home')
        const $q = useQuasar()
        let auth = store.getters["authStudent/getAuthUserStudent"]
        const password = ref<string>('')
        const password_old = ref<string>('')
        const password_confirm = ref<string>('')
        const myRequest = ref([])
        const {page} = usePage()

        const studentStatusTempEnum = StudentTempStatusEnum

        const handleUpdateLearningOutcome = () => {
            loading.value = true
            apiStudent.updateLearningOutcome<IStudentResult>(auth.id).then(res => {
                getAuthUser()
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
        const { setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors } = validationHelper()

        const getAuthUser = async (): Promise<any> => {
            await apiStudent.getAuthUserStudent<IUserResult>().then((res) => {
                auth = _.get(res, 'data', {})
                store.commit(`authStudent/${AuthStudentMutationTypes.SET_AUTH_USER_STUDENT}`, auth)
            })
            return auth
        }
        const getMyRequest = (): void => {
            loading.value = true
            const payload = {
                page: 1
            }
            payload.page = page.value.currentPage

            apiStudent.getMyRequest(payload).then((res: any) => {
                myRequest.value = _.get(res, 'data.data.requests.data', [])
                page.value.currentPage = _.get(res, 'data.data.requests.current_page', 1)
                page.value.total = _.get(res, 'data.data.requests.last_page', 0)
                page.value.perPage = _.get(res, 'data.data.requests.per_page', 0)
            }).catch((err: any) => {

                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => loading.value = false)

        }

        const redirectRouter = (nameRoute: string, params: {}): void => {
            router.push({ name: nameRoute, params: params })
        }

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Hồ sơ sinh viên')
            eventBus.$on('notify-success', (message: string) => {
                $q.notify({
                    icon: 'check',
                    message: message,
                    color: 'positive',
                    position: 'top-right'
                })
            })
            getMyRequest();

        })

        watch(() => page.value.currentPage, () => getMyRequest())

        const isPwd = ref<boolean>(true);

        const handleOpenResetPassword = () => {
            resetValidateErrors('password')
            resetValidateErrors('password_old')
            resetValidateErrors('password_confirm')
            password.value = ''
            password_old.value = ''
            password_confirm.value = ''
            isShowDialogResetPassword.value = true
        }
        const isShowDialogResetPassword = ref<boolean>(false)

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

                apiStudent.resetMyPassword<IStudentResult>(data).then(res => {
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
        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d);
        };

        const handleFormatDate = (value: string): string => {
            return formatDate(value);
        }
        return {
            auth,
            loading,
            tab,
            redirectRouter,
            handleUpdateLearningOutcome,
            password,
            password_confirm,
            password_old,
            handleResetPassword,
            hasValidationErrors,
            getValidationErrors, isPwd,
            handleOpenResetPassword,
            isShowDialogResetPassword,
            resetValidateErrors,
            myRequest,
            studentStatusTempEnum,
            page,
            getValueLodash,
            handleFormatDate
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

