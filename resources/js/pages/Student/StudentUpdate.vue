<template>
    <div class="student-wrapper">
      <q-breadcrumbs>
        <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
        <q-breadcrumbs-el label="Sinh viên" :to="{name: 'StudentIndex'}"/>
        <q-breadcrumbs-el label="Tạo mới"/>
      </q-breadcrumbs>
      <div class="main">
        <div class="row">
          <div class="col-9 q-pr-lg">
            <q-card class="main-form  meta-boxes">
              <q-card-section>
                <div class="widget-title text-bold">Thông tin chung</div>
              </q-card-section>
              <q-separator/>
              <q-card-section>
                <div class="row">
                  <div class="col-3 q-pr-md">
                    <div class="form-group">
                        <label class="text-bold">Ảnh sinh viên </label>
                        <div>
                            <q-file
                                v-model="image"
                                label="Chọn ảnh đại diện"
                                outlined
                                dense
                                @update:model-value="handleUpload()"
                            ></q-file>
                        </div>
                        <div class="avatar-wrapper q-mt-sm">
                            <q-img
                                class="avatar-student"
                                :src="imageUrl"
                                spinner-color="white"
                                style="height: 275px; width: 100%"
                            ></q-img>
                        </div>
                    </div>
                      <div class="form-group">
                          <label class="text-bold">Niên khóa <span class="required">*</span></label>
                          <q-input
                              outlined
                              dense
                              v-model="student.school_year"
                              :error-message="getValidationErrors('school_year')"
                              :error="hasValidationErrors('school_year')"
                              @update:model-value="() => resetValidateErrors('school_year')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Chương trình đào tạo</label>
                          <q-select
                              outlined
                              dense
                              :options="trainingTypeList"
                              label="Chọn chương trình đào tạo"
                              emit-value
                              map-options
                              v-model="student.training_type"
                              :error-message="getValidationErrors('training_type')"
                              :error="hasValidationErrors('training_type')"
                              @update:model-value="() => resetValidateErrors('training_type')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">CCCD/CMT</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.citizen_identification"
                              :error-message="getValidationErrors('citizen_identification')"
                              :error="hasValidationErrors('citizen_identification')"
                              @update:model-value="() => resetValidateErrors('citizen_identification')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Số điện thoại</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.phone"
                              :error-message="getValidationErrors('phone')"
                              :error="hasValidationErrors('phone')"
                              @update:model-value="() => resetValidateErrors('phone')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Email</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.email"
                              :error-message="getValidationErrors('email')"
                              :error="hasValidationErrors('email')"
                              @update:model-value="() => resetValidateErrors('email')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Quốc tịch</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.nationality"
                              :error-message="getValidationErrors('nationality')"
                              :error="hasValidationErrors('nationality')"
                              @update:model-value="() => resetValidateErrors('nationality')"
                          />
                      </div>
                  </div>
                  <div class="col-9">
                      <div class="form-group">
                          <label class="text-bold">Mã Sinh Viên <span class="required">*</span></label>
                          <q-input
                              outlined
                              dense
                              v-model="student.student_code"
                              :error-message="getValidationErrors('student_code')"
                              :error="hasValidationErrors('student_code')"
                              @update:model-value="() => resetValidateErrors('student_code')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Họ và tên <span class="required">*</span></label>
                          <q-input
                              outlined
                              dense
                              v-model="student.full_name"
                              :error-message="getValidationErrors('full_name')"
                              :error="hasValidationErrors('full_name')"
                              @update:model-value="() => resetValidateErrors('full_name')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Giới tính <span class="required">*</span></label>
                          <q-select
                              outlined
                              dense
                              :options="genderList"
                              label="Chọn giới tính"
                              v-model="student.gender"
                              emit-value
                              map-options
                              :error-message="getValidationErrors('gender')"
                              :error="hasValidationErrors('gender')"
                              @update:model-value="() => resetValidateErrors('gender')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Ngày sinh</label>
                          <q-input outlined dense v-model="student.dob"
                                   :error-message="getValidationErrors('dob')"
                                   :error="hasValidationErrors('dob')"
                                   @update:model-value="() => resetValidateErrors('dob')"
                          >
                              <template v-slot:append>
                                  <q-icon name="event" class="cursor-pointer">
                                      <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                          <q-date mask="DD/MM/YYYY" :locale="myLocale" v-model="student.dob">
                                              <div class="row items-center justify-end">
                                                  <q-btn v-close-popup label="Đóng" color="primary" flat/>
                                              </div>
                                          </q-date>
                                      </q-popup-proxy>
                                  </q-icon>
                              </template>
                          </q-input>
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Nơi sinh</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.pob"
                              :error-message="getValidationErrors('pob')"
                              :error="hasValidationErrors('pob')"
                              @update:model-value="() => resetValidateErrors('pob')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Quê quán</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.countryside"
                              :error-message="getValidationErrors('countryside')"
                              :error="hasValidationErrors('countryside')"
                              @update:model-value="() => resetValidateErrors('countryside')"
                          />
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Hộ khẩu thường trú</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.permanent_residence"
                              :error-message="getValidationErrors('permanent_residence')"
                              :error="hasValidationErrors('permanent_residence')"
                              @update:model-value="() => resetValidateErrors('permanent_residence')"
                          />
                      </div>
                      <div class="row">
                          <div class="col-6 q-pr-sm">
                              <div class="form-group">
                                  <label class="text-bold">Dân tộc</label>
                                  <q-input
                                      outlined
                                      dense
                                      v-model="student.ethnic"
                                      :error-message="getValidationErrors('ethnic')"
                                      :error="hasValidationErrors('ethnic')"
                                      @update:model-value="() => resetValidateErrors('ethnic')"
                                  />
                              </div>
                          </div>
                          <div class="col-6 q-pr-sm">
                              <div class="form-group">
                                  <label class="text-bold">Tôn giáo</label>
                                  <q-input
                                      outlined
                                      dense
                                      v-model="student.religion"
                                      :error-message="getValidationErrors('religion')"
                                      :error="hasValidationErrors('religion')"
                                      @update:model-value="() => resetValidateErrors('religion')"
                                  />
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="text-bold">Trình độ học vấn</label>
                          <q-input
                              outlined
                              dense
                              v-model="student.academic_level"
                              :error-message="getValidationErrors('academic_level')"
                              :error="hasValidationErrors('academic_level')"
                              @update:model-value="() => resetValidateErrors('academic_level')"
                          />
                      </div>

                      <div class="form-group">
                          <label class="text-bold">Lớp <span class="required">*</span></label>
                          <q-select
                              outlined
                              dense
                              fill-input
                              :options="classes"
                              option-label="name"
                              option-value="id"
                              label="Chọn lớp"
                              v-model="student.class_id"
                              emit-value
                              map-options
                              :error-message="getValidationErrors('class_id')"
                              :error="hasValidationErrors('class_id')"
                              @update:model-value="() => resetValidateErrors('class_id')"
                          />
                      </div>
                      <div class="family-wrapper q-mt-lg q-mb-lg">
                          <label class="text-bold label-family">Thông tin gia đình</label>
                          <div class="family-list q-pa-md">
                              <div v-for="(item, index) in student.families" class="row">
                                  <div class="col-2 q-mr-sm">
                                      <div class="form-group">
                                          <label class="text-bold">Quan hệ</label>
                                          <q-input
                                              v-model="item.relationship"
                                              outlined
                                              dense
                                              label="Vd: Bố, mẹ,..."
                                          />
                                      </div>
                                  </div>
                                  <div class="col-3 q-mr-sm">
                                      <div class="form-group">
                                          <label class="text-bold">Họ và tên</label>
                                          <q-input
                                              v-model="item.full_name"
                                              outlined
                                              dense
                                              label="Vd: Hoàng Văn A"
                                          />
                                      </div>
                                  </div>
                                  <div class="col-3 q-mr-sm">
                                      <div class="form-group">
                                          <label class="text-bold">Nghề nghiệp</label>
                                          <q-input
                                              v-model="item.job"

                                              outlined
                                              dense
                                              label="Vd: Giáo viên,..."
                                          />
                                      </div>
                                  </div>
                                  <div class="col-2 q-mr-sm">
                                      <div class="form-group">
                                          <label class="text-bold">Số điện thoại</label>
                                          <q-input
                                              v-model="item.phone"
                                              outlined
                                              dense
                                              label="Vd: 0974xxxxxx"
                                          />
                                      </div>
                                  </div>
                                  <div class="col-1 q-mr-sm">
                                      <div class="form-group">
                                          <label class="text-bold">&nbsp;&nbsp;</label>
                                          <q-btn color="red" class="q-mt-lg" @click="deleteFamily(index)">
                                              <q-icon name="fa-solid fa-trash" size="xs"></q-icon>
                                          </q-btn>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col">
                                      <q-btn color="secondary" @click="addFamily">
                                          <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"
                                          ></q-icon>
                                          Thêm mới
                                      </q-btn>
                                  </div>
                              </div>
                          </div>
                      </div>

                  </div>
                </div>
                  <div class="row">

                      <div class="col">
                          <div class="form-group">
                              <label class="text-bold">Đối tượng chính sách xã hội</label>
                              <q-select
                                  outlined
                                  dense
                                  label="Chọn đối tượng"
                                  :options="studentSocialPolicyObjectList"
                                  v-model="student.social_policy_object"
                                  emit-value
                                  map-options
                                  :error-message="getValidationErrors('social_policy_object')"
                                  :error="hasValidationErrors('social_policy_object')"
                                  @update:model-value="() => resetValidateErrors('social_policy_object')"
                              />
                          </div>


                          <div class="form-group">
                              <label class="text-bold">Ghi chú</label>
                              <q-input type="textarea" outlined dense id="description" v-model="student.note"></q-input>
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
                      <q-btn @click="handleUpdateStudent" no-caps color="secondary" class="q-mr-sm">
                          <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                          Lưu
                      </q-btn>
                      <q-btn @click="redirectRouter('StudentDetail', {id: student?.id})" no-caps color="warning"
                             class="q-mr-sm">
                          <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
                          Quay lại
                      </q-btn>
                  </q-card-section>
              </q-card>
              <q-card>
                  <q-card-section>
                      <div class="widget-title text-bold">Thiết lập sinh viên</div>
                  </q-card-section>
                  <q-separator/>
                  <q-card-section>
                      <div class="main-form">
                          <div class="form-group">
                              <label class="text-bold">Tình trạng sinh viên <span class="required">*</span></label>
                              <q-select
                                  outlined
                                  dense
                                  :options="studentStatusList"
                                  v-model="student.status"
                                  label="Chọn trạng thái"
                                  emit-value
                                  map-options
                                  :error-message="getValidationErrors('status')"
                                  :error="hasValidationErrors('status')"
                                  @update:model-value="() => resetValidateErrors('status')"
                              />
                          </div>

                          <div class="form-group">
                              <label class="text-bold">Vai trò <span class="required">*</span></label>
                              <q-select
                                  outlined
                                  dense
                                  v-model="student.role"
                                  :options="studentRoleList"
                                  label="Chọn vai trò"
                                  emit-value
                                  map-options
                                  :error-message="getValidationErrors('role')"
                                  :error="hasValidationErrors('role')"
                                  @update:model-value="() => resetValidateErrors('role')"
                              />
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
  import {useStore} from "vuex";
  import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
  import {useRoute, useRouter} from "vue-router";
  import {useQuasar} from "quasar";
  import {validationHelper} from "../../utils/validationHelper";
  import {convertTime} from "../../utils/helpers";
  import moment from "moment";
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
  import eventBus from "../../utils/eventBus";

  export default defineComponent({
      name: "StudentCreate",
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
          const route = useRoute()


          const {classes, getAllClasses} = useClass()

          const student = ref<IStudentResult>({
              full_name: "",
              student_code: "",
              dob: "",
              training_type: TrainingTypeEnum.FormalUniversity,
              social_policy_object: StudentSocialPolicyObjectEnum.None
          })

          const name = ref<string>('')

          const gender = ref<number | null>(null)
          const dob = ref<string>(moment().format('DD/MM/YYYY'))

          const image = ref<any | null>(null);
          const imageUrl = ref<string>('/images/User-Default.jpg');

          const handleUpload = () => {
              if (image.value) {
                  imageUrl.value = URL.createObjectURL(image.value);
              }
          }

          const studentCode = ref<any>("")

          onMounted(() => {
              store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý sinh viên')
              getAllClasses()

              studentCode.value = route.params.id
              if (studentCode.value) {
                  getStudent(parseInt(studentCode.value));
              }
          })

          const getStudent = (id: number) => {
              api.getStudentById<IStudentResult>(id).then(res => {
                  student.value = _.get(res, 'data.data.student', [])
              }).catch(() => {
                  $q.notify({
                      icon: 'report_problem',
                      message: 'Không lấy được thông tin sinh viên',
                      color: 'negative',
                      position: 'top-right'
                  })
              })
          }

          const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
              router.push({name: nameRoute, params: params})
          }

          const addFamily = () => {
              const families = student.value.families ?? []
              families.push({relationship: '', full_name: '', phone: '', job: ''})
              student.value.families = families
          }

          const deleteFamily = (id) => {
              student.value.families = student.value.families.filter((item, index) => index != id)
          }

          const isValidate = (): boolean => {
              let isCheck = true


              return isCheck
          }
          const isRequest = ref<boolean>(false)
          const handleUpdateStudent = () => {
              if (!isRequest.value) {
                  isRequest.value = true
                  const formData = new FormData()

                  Object.keys(student.value).map(function (objectKey) {
                      const value = student.value[objectKey];
                      formData.append(objectKey, value)
                  });

                  formData.append('image', image.value)
                  api.updateStudent<IStudentResult>(formData, parseInt(studentCode.value)).then(res => {
                      if (res) {
                          eventBus.$emit('notify-success', 'Cập nhật sinh viên thành công')
                          const id = _.get(res, 'data.data.student.id', '')
                          redirectRouter('StudentDetail', {id: id})
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
                  }).finally(()=> {
                      isRequest.value = false
                  })
              }

          }

          return {
              name,
              gender,
              redirectRouter,
              getValidationErrors,
              hasValidationErrors,
              handleUpdateStudent,
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
              addFamily,
              deleteFamily,
              student,
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
