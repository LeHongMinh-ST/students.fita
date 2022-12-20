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
                          :disable="true"
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
                          :disable="true"
                          label="Chọn chủ đề"
                          v-model="subjects"
                          emit-value
                          map-options
                          option-value="value"
                          option-label="label"
                          :error-message="getValidationErrors('subjects')"
                          :error="hasValidationErrors('subjects')"
                          @update:model-value="() => resetValidateErrors('subjects')"
                      />
                    </div>
  
                    <div class="form-group">
                      <label class="text-bold">Sinh viên <span class="required">*</span></label>
                      <q-select
                          outlined
                          dense
                          fill-input
                          :disable="true"
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
                               :disable="true"
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
                <q-btn :disable="isRequest" @click="handleChangeStatusReport" no-caps color="secondary" class="q-mr-sm">
                  <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                  Duyệt
                </q-btn>
                <q-btn @click="redirectRouter('ReportStudentAdmin')" no-caps color="warning" class="q-mr-sm">
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
   
    import {
        defineComponent,
        onMounted,
        reactive,
        ref
    } from "vue";
    import useStudent from "../../uses/useStudent";
    import {
        useRoute, useRouter
    } from "vue-router";
    import api from '../../api'
    import {
        IStudentResult
    } from "../../models/IStudentResult";
    import {
        useQuasar
    } from "quasar";
    import eventBus from "../../utils/eventBus"
import _ from "lodash"
import {ReportStatusEnum} from "../../enums/reportStatus.enum";
import {validationHelper} from "../../utils/validationHelper";
import { SUBJECTS } from "../../utils/constants";


    export default defineComponent({
        name: "ReportStudentAdminDetail",
        setup() {
            const route = useRoute()
            const tab = ref < string > ('home')
            const subjectList = SUBJECTS
            const $q = useQuasar()
            const idReport = ref("")
            const reportStatusEnum = ReportStatusEnum;
            const router = useRouter()
            const isRequest = ref<boolean>(false)
            const student_id = ref<number | null>(null)
            const title = ref<string | null>("")
            const content = ref<string | null>("")
            const status = ref(reportStatusEnum.Pending)
            const status_approve = ref(1)
            const subjects = ref(1)
            const class_id = ref(1)
            const {students, getStudentClasses} = useStudent()
            const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()
            const report: any = {
                student_id: student_id,
                title: title,
                subjects: subjects,
                content: content,
                status: status,
                status_approve: status_approve,
                class_id: class_id,
            }
            
            onMounted (async() => {
                
                idReport.value = < string > route.params.id
                await getStudentClasses()
                await getReport (parseInt(idReport.value));
                if(report['status'].value == reportStatusEnum.Pending){
                    await handleUpdateStatusReport(reportStatusEnum.Seen);
                }
            })

            const loading = ref < boolean > (false)
            const redirectRouter = (nameRoute: string): void => {
                router.push({name: nameRoute})
            }

            const handleChangeStatusReport = () => {
                const data = {
                    "status":reportStatusEnum.Approved
                }
                if (!isRequest.value) {
                    $q.loading.show()
                    isRequest.value = true

                    api.changeStatusReport(data, idReport.value).then(res => {
                    if (res) {
                        eventBus.$emit('notify-success', 'Duyệt phản ánh thành công')
                        redirectRouter('ReportStudentAdmin')
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

            const getReport= async (id:number)=>{
                await api.getReport<{}>(parseInt(idReport.value)).then((res) => {
                    const data = _.get(res, 'data.data.report', {})
                    for(const key in report){
                        report[key].value = data[key]
                    }
                }).catch(() => {
                 
                }).finally(()=> {

                })
            }
            const handleUpdateStatusReport = (status:number) => {
                
                const data = {
                    "status":status
                }
                api.changeStatusReport(data, idReport.value).then(res => {
                if (res) {
                    
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
                }).finally(() => {
                    $q.loading.hide()
                })
            }

            return {
                tab,
                handleUpdateStatusReport,
                loading,
                isRequest,
                getValidationErrors,
                hasValidationErrors,
                resetValidateErrors,
                handleChangeStatusReport,
                student_id,
                title,
                content,
                subjects,
                redirectRouter,
                subjectList,
                students
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
