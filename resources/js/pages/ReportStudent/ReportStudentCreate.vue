<template>
  <div class="report-wrapper">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el label="Báo cáo sinh viên" :to="{name: 'ReportStudentIndex'}"/>
      <q-breadcrumbs-el label="Tạo mới"/>
    </q-breadcrumbs>
    <div class="main">
      <div class="row">
        <div class="col-9 q-pr-lg">
          <q-card class="main-form  meta-boxes">
            <q-card-section>
              <div class="widget-title text-bold">Tạo mới báo cáo</div>
            </q-card-section>
            <q-separator/>
            <q-card-section>
              <div class="row">
                <div class="col-9">
                  <div class="form-group">
                    <label class="text-bold">Tiêu đề<span class="required">*</span></label>
                    <q-input
                        outlined
                        dense
                        v-model="title"
                        :error-message="getValidationErrors('title')"
                        :error="hasValidationErrors('title')"
                        @update:model-value="() => resetValidateErrors('title')"
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Chủ đề <span class="required">*</span></label>
                    <q-select
                        outlined
                        dense
                        fill-input
                        :options="subjectList"
                        label="Chọn chủ đề"
                        v-model="subjects"
                        emit-value
                        map-options
                        option-value="value"
                        option-label="label"
                        :error-message="getValidationErrors('report.subjects')"
                        :error="hasValidationErrors('report.subjects')"
                        @update:model-value="() => resetValidateErrors('report.subjects')"
                    />
                  </div>

                  <div class="form-group">
                    <label class="text-bold">Sinh viên <span class="required">*</span></label>
                    <q-select
                        outlined
                        dense
                        fill-input
                        :options="students"
                        option-label="full_name"
                        option-value="id"
                        label="Chọn sinh viên"
                        v-model="student_id"
                        emit-value
                        map-options
                        :error-message="getValidationErrors('student_id')"
                        :error="hasValidationErrors('student_id')"
                        @update:model-value="() => resetValidateErrors('student_id')"
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Nội dung</label>
                    <q-input type="textarea" outlined dense id="description" v-model="content"
                             :error-message="getValidationErrors('content')"
                             :error="hasValidationErrors('content')"
                             @update:model-value="() => resetValidateErrors('content')"
                    ></q-input>
                  </div>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="col-3 right-sidebar">
          <q-card class="widget meta-boxes action-horizontal q-mb-md">
            <q-card-section>
              <div class="widget-title text-bold">Tác vụ</div>
            </q-card-section>
            <q-separator/>
            <q-card-section>
              <q-btn :disable="isRequest" @click="handleCreateReport" no-caps color="secondary" class="q-mr-sm">
                <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                Lưu
              </q-btn>
              <q-btn @click="redirectRouter('ReportStudent')" no-caps color="warning" class="q-mr-sm">
                <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
                Quay lại
              </q-btn>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, reactive, ref} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useRouter} from "vue-router";
import {useQuasar} from "quasar";
import {validationHelper} from "../../utils/validationHelper";
import {convertTime} from "../../utils/helpers";
import moment from "moment";
import eventBus from "../../utils/eventBus"
import {
  GENDER_LIST,
  MY_LOCALE,
  STUDENT_ROLE_LIST,
  STUDENT_SOCIAL_POLICY_OBJECT_LIST,
  STUDENT_STATUS_LIST,
  SUBJECTS,
  TRAINING_TYPE_LIST
} from "../../utils/constants";
import {IReportResult} from "../../models/IReportResult";
import useClass from "../../uses/useClass";
import _ from "lodash";
import api from "../../apiStudent"
import {TrainingTypeEnum} from "../../enums/trainingType.enum";
import {StudentSocialPolicyObjectEnum} from "../../enums/studentSocialPolicyObject.enum";
import useStudent from "../../uses/useStudent";


export default defineComponent({
  name: "ReportStudentCreate",
  setup() {
    const store = useStore()
    const $q = useQuasar()
    const router = useRouter()

    const subjectList = SUBJECTS

    const {students, getStudentClasses} = useStudent()

    const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()


    const student_id = ref<number | null>(null)
    const title = ref<string | null>("")
    const content = ref<string | null>("")
    const status = ref(1)
    const status_approve = ref(1)
    const subjects = ref(1)
    const class_id = ref(1)

    const report: any = {
      student_id: student_id,
      title: title,
      subjects: subjects,
      content: content,
      status: status,
      status_approve: status_approve,
      class_id: class_id,
    }

    const rule = {
      student_id: [
        (val: any) => val || "Vui lòng chọn sinh viên!",
      ],
      title: [
        (val: any) => val || "Vui lòng nhập tiêu đề !",
      ],
      subjects: [
        (val: any) => val || "Vui lòng nhập!",
      ],
      content: [
        (val: any) => val || "Vui lòng nhập!",
      ],
    };

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý sinh viên')
      getStudentClasses()
    })

    const redirectRouter = (nameRoute: string): void => {
      router.push({name: nameRoute})
    }


    const payload = reactive({...report})
    const isRequest = ref<boolean>(false)

    const isValidate = (): boolean => {
      let isCheck = true


      return isCheck
    }

    const handleCreateReport = () => {

      const data = _.cloneDeep(payload);
      if (!isRequest.value) {
        $q.loading.show()
        isRequest.value = true

        api.createStudentReport(data).then(res => {
          if (res) {
            eventBus.$emit('notify-success', 'Thêm mới báo cáo thành công')
            redirectRouter('ReportStudent')
          }
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
          isRequest.value = false
          $q.loading.hide()
        })
      }
    }

    return {
      name,
      redirectRouter,
      getValidationErrors,
      hasValidationErrors,
      handleCreateReport,
      subjectList,
      report,
      convertTime,
      getStudentClasses,
      resetValidateErrors,
      students,
      isRequest,
      student_id,
      title,
      content,
      rule,
      subjects
    }
  }
})
</script>

<style scoped lang="scss">
.report-wrapper {
  .main {
    margin-top: 20px;

    .main-form {
      margin-bottom: 15px;

      .form-group {
        margin-bottom: 15px;
      }
    }

    .avatar-wrapper {
      height: 275px;
      width: 100%;
      border-radius: 5px;
      border: 1px solid #8f8f8f;
      margin-bottom: 40px;

      .avatar-report {
        border-radius: 5px;
        object-fit: cover;
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
  }
}

</style>
