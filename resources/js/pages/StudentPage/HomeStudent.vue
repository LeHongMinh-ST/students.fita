<template>
    <div class="student-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{ name: 'Home' }" />
            <q-breadcrumbs-el :to="{ name: 'StudentIndex' }" label="Sinh viên" />
            <q-breadcrumbs-el label="Thông tin sinh viên" />
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

                                    <q-btn color="green" class="q-mr-sm q-mb-sm"
                                        @click="redirectRouter('StudentUpdateProfile')">
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
                            <q-tab name="report" label="Báo cáo phản án" />
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
                            <q-tab-panel name="report">
                                <div class="text-h6">Báo cáo phản án</div>
                                <q-scroll-area class="q-px-lg q-pb-md" style="height: 100vh">
                                    <q-timeline class="q-pa-sm" color="secondary">


                                        <q-timeline-entry title="Event Title" subtitle="February 22, 1986">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor
                                                incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation
                                                ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in
                                                reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint
                                                occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>

                                        <q-timeline-entry title="Event Title" subtitle="February 21, 1986"
                                            icon="delete">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor
                                                incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation
                                                ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in
                                                reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint
                                                occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>


                                        <q-timeline-entry title="Event Title" subtitle="February 22, 1986"
                                            avatar="https://cdn.quasar.dev/img/avatar2.jpg">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in
                                                reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint
                                                occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>

                                        <q-timeline-entry title="Event Title" subtitle="February 22, 1986">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in
                                                reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint
                                                occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>

                                        <q-timeline-entry title="Event Title" subtitle="February 22, 1986"
                                            color="orange" icon="done_all">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in
                                                reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint
                                                occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>

                                        <q-timeline-entry title="Event Title" subtitle="February 22, 1986">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in
                                                reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint
                                                occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>

                                        <q-timeline-entry title="Event Title" subtitle="February 22, 1986">
                                            <div>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut
                                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                                                exercitation ullamco
                                                laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in reprehenderit in
                                                voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                                                sint occaecat cupidatat
                                                non proident, sunt in culpa qui officia deserunt mollit anim id est
                                                laborum.
                                            </div>
                                        </q-timeline-entry>
                                    </q-timeline>
                                </q-scroll-area>
                            </q-tab-panel>

                        </q-tab-panels>
                    </q-card>
                </div>
            </div>
        </div>
        <!-- <q-dialog v-model="dialogDelete" persistent>
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
        </q-dialog> -->
    </div>
</template>


<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { HomeMutationTypes } from "../../store/modules/home/mutation-types";
import { useStore } from "vuex"
import { useRouter } from "vue-router";
import { useQuasar } from "quasar";
import api from "../../api";
import { IStudentResult } from "../../models/IStudentResult";
import _ from "lodash";

export default defineComponent({
    name: "HomeStudent",
    setup() {
        const store = useStore()
        const loading = ref<boolean>(false)
        const router = useRouter()
        const tab = ref<string>('home')
        const $q = useQuasar()

        const handleUpdateLearningOutcome = () => {
            loading.value = true
            api.updateLearningOutcome<IStudentResult>(auth.value.id).then(res => {
                auth.value = _.get(res, 'data.data.student', {})
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

        const auth = store.getters["authStudent/getAuthUserStudent"]

        const redirectRouter = (nameRoute: string): void => {
            router.push({ name: nameRoute})
        }

        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Hồ sơ sinh viên')

        })

        return {
            auth, loading, tab, redirectRouter, handleUpdateLearningOutcome
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

