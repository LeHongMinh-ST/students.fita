<template>
    <div class="student-wrapper">
      <q-breadcrumbs>
        <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
        <q-breadcrumbs-el label="Báo cáo sinh viên" :to="{name: 'ReportStudentIndex'}"/>
        <q-breadcrumbs-el label="Cập nhật"/>
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
                              v-model="report.title"
                              :error-message="getValidationErrors('student_code')"
                              :error="hasValidationErrors('student_code')"
                              @update:model-value="() => resetValidateErrors('student_code')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Chủ đề <span class="required">*</span></label>
                          <q-input
                              outlined
                              dense
                              v-model="report.subjects"
                              :error-message="getValidationErrors('full_name')"
                              :error="hasValidationErrors('full_name')"
                              @update:model-value="() => resetValidateErrors('full_name')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Sinh viên <span class="required">*</span></label>
                          <q-select
                              outlined
                              dense
                              fill-input
                              :options="classes"
                              option-label="name"
                              option-value="id"
                              label="Chọn sinh viên"
                              v-model="report.class_id"
                              emit-value
                              map-options
                              :error-message="getValidationErrors('class_id')"
                              :error="hasValidationErrors('class_id')"
                              @update:model-value="() => resetValidateErrors('class_id')"
                          />
                      </div>
                      <div class="form-group">
                              <label class="text-bold">Nội dung</label>
                              <q-input type="textarea" outlined dense id="description" v-model="report.content"></q-input>
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
                      <q-btn @click="handleCreateReport" no-caps color="secondary" class="q-mr-sm">
                          <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                          Lưu
                      </q-btn>
                      <q-btn @click="redirectRouter('ReportStudentIndex')" no-caps color="warning" class="q-mr-sm">
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
  import {defineComponent, onMounted, ref} from "vue";
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
      TRAINING_TYPE_LIST
  } from "../../utils/constants";
  import {IStudentResult} from "../../models/IStudentResult";
  import useClass from "../../uses/useClass";
  import _ from "lodash";
  import api from "../../api"
  import {TrainingTypeEnum} from "../../enums/trainingType.enum";
  import {StudentSocialPolicyObjectEnum} from "../../enums/studentSocialPolicyObject.enum";
import { IReportStudent } from "../../models/IReportStudent";
  
  export default defineComponent({
      name: "ReportStudentUpdate",
      setup() {
          const store = useStore()
          const $q = useQuasar()
          const router = useRouter()
          const myLocale = MY_LOCALE
          const genderList = GENDER_LIST
          const studentStatusList = STUDENT_STATUS_LIST
          const studentRoleList = STUDENT_ROLE_LIST
          const trainingTypeList = TRAINING_TYPE_LIST
          const studentSocialPolicyObjectList = STUDENT_SOCIAL_POLICY_OBJECT_LIST
  
          const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()
  
  
          const {classes, getAllClasses} = useClass()
  
          const report = ref<IReportStudent>({
            title: "BC 1",
            subjects: "BC 1",
            content: "Nội dung báo cáo 1",
            class_id:0
          })
  
          const name = ref<string>('')
  
          const gender = ref<number | null>(null)
          const dob = ref<string>(moment().format('DD/MM/YYYY'))
  
          const image = ref<any | null>(null);
          const imageUrl = ref<string>('/images/User-Default.jpg');
  
          const handleUpload = () => {
              if (image.value) {
                  imageUrl.value = URL.createObjectURL(image.value);
                  console.log(image.value)
              }
          }
  
  
          onMounted(() => {
              store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý sinh viên')
              getAllClasses()
            //   report.value.f
          })
  
          const redirectRouter = (nameRoute: string): void => {
              router.push({name: nameRoute})
          }
  
          
  
          const isValidate = (): boolean => {
              let isCheck = true
  
  
              return isCheck
          }
  
          
          const handleCreateReport = () => {
            eventBus.$emit('notify-success', 'Cập nhật báo cáo thành công')
            redirectRouter('ReportStudent')
        }
  
          return {
              name,
              gender,
              redirectRouter,
              getValidationErrors,
              hasValidationErrors,
              handleCreateReport,
              genderList,
              dob,
              image,
              imageUrl,
              handleUpload,
              myLocale,
              studentStatusList,
              studentRoleList,
              studentSocialPolicyObjectList,
              classes,
              trainingTypeList,
              report,
              convertTime,
              resetValidateErrors
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
  
          .avatar-student {
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
  