<template>
  <div class="student-wrapper">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el :to="{name: 'RequestStudentIndex'}" label="Yêu cầu chỉnh sửa"/>
      <q-breadcrumbs-el label="Chi tiết"/>
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
                    <span class="text-bold">Trạng thái: </span>
                    <q-badge
                        v-if="student?.status_approved == studentStatusTempEnum.Pending"
                        align="middle"
                        color='deep-orange-5'>
                      {{ student?.status_approved_text ?? "Chưa cập nhật" }}
                    </q-badge>
                    <q-badge
                        v-if="student?.status_approved == studentStatusTempEnum.ClassMonitorApproved "
                        align="middle"
                        color='blue'>
                      {{ student?.status_approved_text ?? "Chưa cập nhật" }}
                    </q-badge>
                    <q-badge
                        v-if="student?.status_approved == studentStatusTempEnum.TeacherApproved "
                        align="middle"
                        color='teal'>
                      {{ student?.status_approved_text ?? "Chưa cập nhật" }}
                    </q-badge>
                    <q-badge
                        v-if="student?.status_approved == studentStatusTempEnum.Approved "
                        align="middle"
                        color='green'>
                      {{ student?.status_approved_text ?? "Chưa cập nhật" }}
                    </q-badge>
                    <q-badge
                        v-if="student?.status_approved == studentStatusTempEnum.Reject "
                        align="middle"
                        color='red'>
                      {{ student?.status_approved_text ?? "Chưa cập nhật" }}
                    </q-badge>
                  </div>
                  <div class="item q-mt-md">
                    <span class="text-bold">Lớp trưởng duyệt:</span>

                    {{ student?.student_approved?.full_name ?? "Chưa duyệt" }}
                  </div>
                  <div class="item q-mt-md">
                    <span class="text-bold">Giáo viên duyệt:</span>
                    {{ student?.teacher_approved?.full_name ?? "Chưa duyệt" }}
                  </div>
                  <div class="item q-mt-md">
                    <span class="text-bold">Ban chủ nhiệm duyệt:</span>
                    {{ student?.admin_approved?.full_name ?? "Chưa duyệt" }}
                  </div>
                  <div v-if="student?.status_approved == studentStatusTempEnum.Reject" class="item q-mt-md">
                    <span class="text-bold">Người từ chối:</span>
                    {{ `${student?.rejectable?.full_name} (${student.reject_role_text})`  }}
                  </div>
                </div>
                <q-separator/>
                <div class="main-action q-mt-md text-center">
                  <q-btn v-if="checkPermission('student-update') && checkStatusApproved(student)"
                         color="secondary" class="q-mr-sm q-mb-sm"
                         @click="handleChangeStatus(checkTeacher() ? studentStatusTempEnum.TeacherApproved : studentStatusTempEnum.Approved)">
                    <q-icon name="fa-solid fa-check" class="q-mr-sm"
                            size="xs"></q-icon>
                    Duyệt
                  </q-btn>


                  <q-btn v-if="checkPermission('student-update') && checkStatusReject(student)"
                         color="red" class="q-mb-sm"
                         @click="handleChangeStatus(studentStatusTempEnum.Reject)">
                    <q-icon name="fa-solid fa-xmark " class="q-mr-sm"
                            size="xs"></q-icon>
                    Từ chối
                  </q-btn>

                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="col-9">
          <q-card>
            <q-card-section>
              <div class="text-bold">Thông tin yêu cầu chỉnh sửa</div>
            </q-card-section>
            <q-separator/>

            <q-card-section>
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
            </q-card-section>

          </q-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref} from "vue";
import useStudent from "../../uses/useStudent";
import {useRoute} from "vue-router";
import api from '../../apiStudent'
import {IStudentResult} from "../../models/IStudentResult";
import {useQuasar} from "quasar";
import {useRouter} from "vue-router/dist/vue-router";
import eventBus from "../../utils/eventBus";
import {validationHelper} from "../../utils/validationHelper";
import _ from "lodash";
import {permissionHelper} from "../../utils/permissionHelper";
import {StudentTempStatusEnum} from "../../enums/studentTempStatus.enum";
import {useStore} from "vuex";

export default defineComponent({
  name: "RequestDetail",
  setup() {
    const route = useRoute()
    const store = useStore()
    const router = useRouter()
    const {student, getStudentTempStudent} = useStudent()
    const userId = ref<string>('')
    const tab = ref<string>('home')
    const $q = useQuasar()
    const {checkPermission, checkTeacher} = permissionHelper()

    const {
      setValidationErrors,
      getValidationErrors,
      hasValidationErrors,
      resetValidateErrors,
    } = validationHelper();

    const dialogDelete = ref<boolean>(false)
    const studentStatusTempEnum = StudentTempStatusEnum
    onMounted(() => {
      userId.value = <string>route.params.id
      getStudentTempStudent(parseInt(userId.value))
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

    const checkStatusReject = (item) => {
      const auth = store.getters["authStudent/getAuthUserStudent"]

      if (item.status_approved == studentStatusTempEnum.Approved) {
        return false
      }

      if (item.status_approved == studentStatusTempEnum.Reject) {
        return false
      }

      if (auth.is_teacher && !auth.is_super_admin) {
        if (item.status_approved == studentStatusTempEnum.TeacherApproved) {
          return false
        }
      }

      return true
    }

    const checkStatusApproved = (item) => {
      const auth = store.getters["authStudent/getAuthUserStudent"]

      if (item.status_approved == studentStatusTempEnum.Approved) return false

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

    const popupResetPassword = ref<boolean>(false)
    const isPwd = ref<boolean>(true)
    const password = ref<string>("")
    const refPassword = ref<any>(null)

    const openDialogResetPassword = (): void => {
      password.value = ""
      popupResetPassword.value = true;
    }

    const isRequest = ref<boolean>(false)


    const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
      router.push({name: nameRoute, params: params})
    }

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

    const handleChangeStatus = async (status) => {
      if (!isRequest.value) {
        isRequest.value = true
        try {
          const data = {
            status: status
          }
          const res = await api.changeStatusRequest(parseInt(userId.value), data)
          if (res) {
            await getStudentTempStudent(parseInt(userId.value))
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

    return {
      student,
      tab,
      handleUpdateLearningOutcome,
      loading,
      redirectRouter,
      dialogDelete,
      popupResetPassword,
      password,
      refPassword, openDialogResetPassword,
      isPwd,
      getValidationErrors,
      hasValidationErrors,
      checkPermission,
      studentStatusTempEnum,
      checkStatusApproved,
      checkStatusReject,
      handleChangeStatus,
      checkTeacher
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
