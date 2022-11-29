<template>
    <div class="classes-wrapper">
    <q-dialog v-model="isShow" @hide="closeDialog">
      <q-card style="width: 300px">
        <q-card-section>
          <div class="text-h6">Thêm sinh viên</div>
        </q-card-section>
        <q-card-section class="row items-center" style="width: 100%">
            <label for="code" class="text-bold"
                >Mã sinh viên <span class="required">*</span></label
            >
            <q-badge color="secondary" multi-line>
        Model: "{{ model }}"
      </q-badge>
      <br/>

      <q-select
        filled
        emit-value
        map-options
        :options="selectOptionFilter"
        option-label="full_name"
        option-value="id"
        v-model="model"
        use-input
        dense
        label="Simple filter"
        @filter="filterFn"
        behavior="menu"
        style="width: 100%"
         multiple
         :hide-selected="true"
      >
        <template v-slot:option="{ itemProps, opt, selected, toggleOption }">
          <q-item v-bind="itemProps">
            <q-avatar color="red" text-color="white" icon="directions" />
            <q-item-section>
              <q-item-label v-html="opt.full_name"></q-item-label>
            </q-item-section>
            <q-item-section side>
              <q-checkbox :model-value="selected" @update:model-value="toggleOption(opt)"></q-checkbox>
            </q-item-section>
          </q-item>
        </template>
      </q-select>
        </q-card-section>
        <q-card-actions align="right" class="row">
          <q-btn
            flat
            label="Đóng"
            color="primary"
            @click="closeDialog"
            v-close-popup
          />
          <q-btn
            label="Đồng ý"
            color="blue"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el label="Chi tiết lớp học"/>
    </q-breadcrumbs>
    <q-card class="table-wrapper">
        <q-card-section style="display: flex; justify-content:end">
            <q-btn  no-caps color="blue-9" class="q-mr-sm">
                <q-icon name="fa-solid fa-upload" class="q-mr-sm" size="xs"></q-icon>
                Import File
            </q-btn>
            <q-btn no-caps color="secondary" class="q-mr-sm" @click="setShow">
                <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                Thêm sinh viên
            </q-btn>
            <q-btn  no-caps color="warning" class="q-mr-sm">
                <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
                Quay lại
            </q-btn>
        </q-card-section>
        <q-separator inset />
      <q-card-section class="table-wrapper-title" style="justify-content: center !important">
        <div class="q-px-md q-py-sm" style="border: 1px solid black; margin: 10px; border-radius: 10px; width: 60%">
        <span style="position: absolute; top: 0; background-color: white; font-weight: bold;" class="q-px-sm">Thông tin lớp học</span>
          <div style="display: flex; padding-top: 10px; gap: 20px;">
            <div style="line-height:30px; width: 20%">
            <span style="font-weight: bold;" class="">Mã lớp</span>
            <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">{{ getValueLodash(classes, 'name', '') }}</p>
          </div>
          <div style="line-height:30px; width: 20%">
            <span style="font-weight: bold;" class="">Tên lớp</span>
            <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">{{ getValueLodash(classes, 'class_code', '') }}</p>
          </div>
          <div style="line-height:30px; width: 30%">
            <span style="font-weight: bold;" class="">Bộ môn</span>
            <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">{{ getValueLodash(classes, 'department_id', '') }}</p>
          </div>
          <div style="line-height:30px; width: 30%">
            <span style="font-weight: bold;" class="">Giáo viên</span>
            <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">Nguyễn thị hồng nhung mai</p>
          </div>
          </div>
        </div>
      </q-card-section>
      <q-separator inset/>
      <q-card-section>Danh sách sinh viên</q-card-section>
      <q-card-section>
        <!-- <q-table  class="my-sticky-header-table"
        title="Treats"
        :rows="students"
        :columns="columnsTable"
        row-key="name"
        flat
        bordered
        /> -->
        <q-markup-table class="role-table" >
          <thead>
          <tr>
            <th class="text-center" width="5%">STT</th>
            <th class="text-left">Mã SV</th>
            <th class="text-left">Họ và tên</th>
            <th class="text-left">Giới tính</th>
            <th class="text-left">Ngày sinh</th>
            <th class="text-left">SĐT</th>
            <th class="text-center">Tác vụ</th>
          </tr>
          </thead>
          <tbody >
            <template v-if="students.length > 0">
              <tr v-for="(student, index) in students" :key="index">
                <td class="text-center">
                  {{index + +1 + +page.perPage * (page.currentPage - 1)}}
                </td>
                <td class="text-left">
                  <span
                    @click="
                      redirectRouter('DepartmentUpdate', { id: student.id })
                    "
                    class="text-bold cursor-pointer text-link"
                  >
                    {{ getValueLodash(student, "student_code", "") }}
                  </span>
                </td>
                <td class="text-left">
                  {{ getValueLodash(student, "full_name", "") }}
                </td>
                <td class="text-left">
                  {{ getValueLodash(student, "major", "") }}
                </td>
                <td class="text-left">
                  {{
                    handleFormatDate(
                      getValueLodash(student, "dob", "")
                    )
                  }}
                </td>
                <td class="text-left">
                  {{ getValueLodash(student, "phone", "") }}
                </td>
                <td class="text-center">
                  <div class="inline cursor-pointer">
                    <q-icon name="menu" size="sm"></q-icon>
                    <q-menu touch-position>
                      <q-list style="min-width: 100px">
                        <q-item
                          clickable
                          v-close-popup
                        >
                          <q-item-section>
                            <span
                              ><q-icon
                                name="fa-solid fa-pen-to-square"
                                class="q-mr-sm"
                                size="xs"
                              ></q-icon
                              >Chỉnh sửa</span
                            >
                          </q-item-section>
                        </q-item>
                        <q-item
                          clickable
                          v-close-popup
                        >
                          <span
                            ><q-icon
                              name="fa-solid fa-trash"
                              class="q-mr-sm"
                              size="xs"
                            ></q-icon
                            >Xoá</span
                          >
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
                  <img class="imgEmpty" src="/images/empty.png" alt="" />
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
      </q-card-section>
    </q-card>


  </div>
</template>
<style lang="css" src="./index.scss"></style>
<script lang="ts">
import {defineComponent, onMounted, ref, watch} from "vue";
import {useStore} from "vuex";
import api from "../../api";
import {useQuasar} from "quasar";
import _ from "lodash";
import { useRoute } from "vue-router";
import { IStudentResult } from "resources/js/models/IStudentResult";
import { formatDate } from "../../utils/helpers";
import { IPage } from "resources/js/models/IPage";
import IPaginate from "resources/js/models/IPaginate";

export default defineComponent({
    name: "ClassesDetail",
    setup() {
      const $q = useQuasar()
      const store = useStore()
      const router = useRoute()
      const idClass = ref("")
      const studentIds = ref<Array<string>>([]);
      const studentId = ref("")
      const classes: any = {
        class_code: ref<string>(),
        name: ref<string>(),
        teacher_id: ref<string>(),
        department_id: ref<string>(),
      };
      const optionSelectStudent = ref<IStudentResult[]>([])
      const students= ref<IStudentResult[]>([])
      const isShow = ref(false)
      const page = ref<IPage>({
        currentPage: 1,
        total: 0,
        perPage: 10
        })
        const checkboxArray = ref<Array<string>>([])
        const checkboxAll = ref<boolean | string>(false)

        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d)
        }
        const handleGetClass = (id: string): void => {
            $q.loading.show()
            api.getClass<{}>(id).then(res => {
                const data = _.get(res, 'data.data.class', '')
                for (const key in classes) {
                    classes[key].value = data[key]
                }
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu lớp học !',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }

        const getAllStudent = (full_name: string = ""): void => {
            $q.loading.show()
            const payload: any = {
                page: page.value.currentPage,
                limit: 9999999999999,
            };
            if(full_name) payload.
            console.log('payload', payload)
            $q.loading.show()
            api.getAllStudent<IPaginate<IStudentResult[]>>(payload).then(res => {
                optionSelectStudent.value = _.get(res, 'data.data.students.data', '')
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu lớp học !',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }

        const handleDeleteInClass = () => {
                $q.loading.show()
                const data = {
                    student_id: checkboxArray.value
                }
            }

            const handleDelete = (): void => {
                $q.loading.show()
            }

        const handleGetStudnet = (id: string): void => {
            $q.loading.show()
            const payload: any = {
                class_id: 1,
                page: page.value.currentPage
            };
            console.log('payload', payload)
            $q.loading.show()
            api.getAllStudent<IPaginate<IStudentResult[]>>(payload).then(res => {
                students.value = _.get(res, 'data.data.students.data', '')
                page.value.currentPage = _.get(res, 'data.data.students.current_page', 1)
                page.value.total = _.get(res, 'data.data.students.last_page', 0)
                page.value.perPage = _.get(res, 'data.data.students.per_page', 0)
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu lớp học !',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
            payload.limit = 99999999
            console.log('payload', payload)
            $q.loading.show()
            api.getAllStudent<IPaginate<IStudentResult[]>>(payload).then(res => {
                const listStudent: any = _.get(res, 'data.data.students.data', '')
                selectModel.value = listStudent.value.map((student: any) => student.id.toString());
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu lớp họcq !',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }
        const handleFormatDate = (value: string): string => {
            if(!value) return ""
            return formatDate(value);
        };
        onMounted((): void => {
        getAllStudent();

        idClass.value = <string> router.params.id

        if(idClass.value) {
            handleGetClass(idClass.value);
            handleGetStudnet(idClass.value);
        }
      })

    watch(() => page.value.currentPage, () => handleGetStudnet(idClass.value))

    const closeDialog = (): void => {
        isShow.value = false;
    }

    const setShow = (): void => {
        isShow.value = true;
    }

        const selectModel = ref([])

      const selectOptionFilter = ref<any[]>([])

      const filterFn = (val: any, update: any) => {
        if (val === '') {
          update(() => {
            selectOptionFilter.value = optionSelectStudent.value
          })
          return
        }

        update(() => {
          const needle = val.toLowerCase()
            selectOptionFilter.value = optionSelectStudent.value.filter(v => v.full_name.toLowerCase().indexOf(needle) > -1)
        })
      }
      return {
        selectModel,
        filterFn,
        selectOptionFilter,
        handleFormatDate,
        handleGetClass,
        idClass,
        classes,
        getValueLodash,
        students,
        isShow,
        closeDialog,
        setShow,
        page,
        optionSelectStudent
      }
    }
  })
</script>



