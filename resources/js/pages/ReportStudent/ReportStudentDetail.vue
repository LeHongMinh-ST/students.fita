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
    import {
        defineComponent,
        onMounted,
        ref
    } from "vue";
    import useStudent from "../../uses/useStudent";
    import {
        useRoute
    } from "vue-router";
    import api from '../../api'
    import {
        IStudentResult
    } from "../../models/IStudentResult";
    import {
        useQuasar
    } from "quasar";

    export default defineComponent({
        name: "ReportStudentDetail",
        setup() {
            const route = useRoute()
            const {
                student,
                getStudent
            } = useStudent()
            const userId = ref < string > ('')
            const tab = ref < string > ('home')
            const $q = useQuasar()
            onMounted(() => {
                userId.value = < string > route.params.id
                getStudent(parseInt(userId.value))
            })
            const loading = ref < boolean > (false)
            const handleUpdateLearningOutcome = () => {
                loading.value = true
                api.updateLearningOutcome < IStudentResult > (student.value.id).then(res => {
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

            return {
                student,
                tab,
                handleUpdateLearningOutcome,
                loading
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
