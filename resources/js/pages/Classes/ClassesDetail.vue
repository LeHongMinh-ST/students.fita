<template>
  <div class="classes-wrapper">
    <q-dialog v-model="dialogDeleteSelect" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="fa-solid fa-trash" color="red" text-color="white"/>
          <span class="q-ml-sm">Bạn có chắc chắn muốn xóa {{ checkboxArray.length }} bản ghi! Dữ liệu không thể phục hồi!</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Đóng" color="primary" @click="closeDialog" v-close-popup/>
          <q-btn label="Đồng ý" color="red" @click="handleDeleteSelect" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dialogDelete" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="fa-solid fa-trash" color="red" text-color="white"/>
          <span class="q-ml-sm">Bạn có chắc chắn muốn xóa sinh viên ra khỏi lớp!</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Đóng" color="primary" @click="closeDialogDelete" v-close-popup/>
          <q-btn label="Đồng ý" color="red" @click="handleDelete" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dialogAdd" @hide="closeDialog">
      <q-card style="width: 500px">
        <q-card-section>
          <div class="text-h6">Thêm sinh viên</div>
        </q-card-section>
        <q-card-section class="row items-center" style="width: 100%">
          <label class="text-bold"
          >Mã sinh viên <span class="required">*</span></label
          >
          <br/>
          <q-select
              filled
              emit-value
              map-options
              :options="optionSelectStudent"
              option-label="full_name"
              option-value="id"
              v-model="optionSelectStudentActive"
              use-input
              id="optionSelectStudentActive"
              use-chips
              multiple
              input-debounce="0"
              @new-value="createValue"
              dense
              @filter="filterFn"
              behavior="menu"
              style="width: 100%"
              :rules="ruleSelect"
              :error-message="getValidationErrors('optionSelectStudentActive')"
              :error="hasValidationErrors('optionSelectStudentActive')"
              ref="refSelect"
          >
            <template v-slot:option="{ itemProps, opt}">
              <q-item v-bind="itemProps">
                <q-avatar v-if="opt?.thumbnail">
                  <img :src="opt?.thumbnail">
                </q-avatar>
                <q-avatar v-else icon="fa-solid fa-user" color="blue-grey-1" text-color="primary"/>
                <q-item-section class="q-pl-lg">
                  <q-item-label v-html="opt.full_name" class="text-weight-bold"></q-item-label>
                  <q-item-label v-html="opt.student_code"></q-item-label>
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
              @click="handleAddStudent"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-dialog v-model="dialogImportDialog" @hide="closeDialog">
      <q-card style="width: 500px;">
        <q-card-section>
          <div class="text-h6">Import Excel</div>
        </q-card-section>
        <q-card-section style="width: 100%">
          <upload @change-file="uploadFileExcel"/>
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
              color="green"
              @click="handleUploadExcel"
          >
            <q-icon name="fa-solid fa-upload" class="q-mr-sm" size="xs"></q-icon>
            Tải lên
          </q-btn>
        </q-card-actions>
      </q-card>
    </q-dialog>

    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el label="Chi tiết lớp học"/>
    </q-breadcrumbs>

    <q-card class="table-wrapper">
      <q-card-section style="display: flex; justify-content:end">
        <q-btn no-caps color="green-9" class="q-mr-sm" @click="openImportExcelDialog">
          <q-icon name="fa-solid fa-file-excel" class="q-mr-sm" size="xs"></q-icon>
          Import Excel
        </q-btn>
        <q-btn no-caps color="secondary" class="q-mr-sm" @click="setShow">
          <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
          Thêm sinh viên vào lớp
        </q-btn>
        <q-btn no-caps color="warning" class="q-mr-sm" @click="redirectRouter('Classes')">
          <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
          Quay lại
        </q-btn>
      </q-card-section>
      <q-separator inset/>
      <q-card-section class="table-wrapper-title" style="justify-content: center !important">
        <div class="q-px-md q-py-sm" style="border: 1px solid black; margin: 10px; border-radius: 10px; width: 60%">
          <span style="position: absolute; top: 0; background-color: white; font-weight: bold;" class="q-px-sm">Thông tin lớp học</span>
          <div style="display: flex; padding-top: 10px; gap: 20px;">
            <div style="line-height:30px; width: 20%">
              <span style="font-weight: bold;" class="">Mã lớp</span>
              <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                {{ class_code ?? 'Đang cập nhật' }}</p>
            </div>
            <div style="line-height:30px; width: 20%">
              <span style="font-weight: bold;" class="">Tên lớp</span>
              <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                {{ class_name ?? 'Đang cập nhật' }}</p>
            </div>
            <div style="line-height:30px; width: 30%">
              <span style="font-weight: bold;" class="">Bộ môn</span>
              <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                {{ department_id ?? 'Đang cập nhật' }}</p>
            </div>
            <div style="line-height:30px; width: 30%">
              <span style="font-weight: bold;" class="">Giáo viên</span>
              <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                {{ teacher_id ?? 'Đang cập nhật' }}</p>
            </div>
          </div>
        </div>
      </q-card-section>
      <q-separator inset/>
      <q-card-section class="table-wrapper-title">
        <div class="table-wrapper-filter">
          <q-slide-transition>
            <q-btn v-if="checkboxArray.length > 0" color="primary" no-caps outline class="q-mr-sm">
              Hành động
              <q-icon name="fa-solid fa-caret-down" class="q-ml-sm" size="xs"></q-icon>

              <q-menu>
                <q-list style="min-width: 100px">
                  <q-item clickable v-close-popup @click="openDialogDeleteSelect">
                    <q-item-section>
                    <span><q-icon name="fa-solid fa-trash" class="q-mr-sm"
                                  size="xs"></q-icon>Xoá ({{ checkboxArray.length }} bản ghi)</span>
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </q-slide-transition>
          <!--          <q-btn class="q-mr-sm" no-caps color="primary" @click="toggleFilter">-->
          <!--            <q-icon name="fa-solid fa-filter" class="q-mr-sm" size="xs"></q-icon>-->
          <!--            Lọc dữ liệu-->
          <!--          </q-btn>-->

          <div class="table-wrapper-search">
            <q-input bottom-slots v-model="search" label="Nhập từ khóa để tìm kiếm" outlined dense>

              <template v-slot:append>
                <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer"/>
                <q-icon name="search"/>
              </template>
            </q-input>
          </div>
        </div>
      </q-card-section>
      <q-card-section>
        <q-markup-table class="role-table">
          <thead>
          <tr>
            <th class="text-center" width="4%">
              <q-checkbox v-model="checkboxAll"/>
            </th>
            <th class="text-center" width="5%">STT</th>
            <th class="text-left">Mã Sinh Viên</th>
            <th class="text-left">Họ và tên</th>
            <th class="text-left">Giới tính</th>
            <th class="text-left">Ngày sinh</th>
            <th class="text-left">SĐT</th>
            <th class="text-center">Tác vụ</th>
          </tr>
          </thead>
          <tbody>
          <template v-if="students.length > 0">
            <tr v-for="(student, index) in students" :key="index">
              <td class="text-center">
                <q-checkbox v-model="checkboxArray" :val="String(getValueLodash(student, 'id', 0))"/>
              </td>
              <td class="text-center">
                {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
              </td>
              <td class="text-left">
                   <span class="cursor-pointer text-bold text-primary"
                         @click="redirectRouter('StudentDetail',{id: student?.id})">
                         {{ getValueLodash(student, "student_code", "") ?? "Chưa cập nhật" }}
                  </span>
              </td>
              <td class="text-left">
                  <span class="cursor-pointer text-bold text-primary"
                        @click="redirectRouter('StudentDetail',{id: student?.id})">
                      {{ getValueLodash(student, "full_name", "") ?? "Chưa cập nhật" }}
                  </span>

              </td>
              <td class="text-left">
                {{ getValueLodash(student, "gender_text", "") }}
              </td>
              <td class="text-left">
                {{ getValueLodash(student, "dob", "") }}
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
                          @click="openDialogDelete(student)"
                      >
                          <span
                          ><q-icon
                              name="fa-solid fa-trash"
                              class="q-mr-sm"
                              size="xs"
                          ></q-icon
                          >Xoá sinh viên khỏi lớp</span
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
              <td colspan="8" class="text-center">
                <img class="imgEmpty" src="/images/empty.png" alt=""/>
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
import api from "../../api";
import {useQuasar} from "quasar";
import _ from "lodash";
import {useRoute, useRouter} from "vue-router";
import {IStudentResult} from "resources/js/models/IStudentResult";
import {formatDate} from "../../utils/helpers";
import {IPage} from "resources/js/models/IPage";
import IPaginate from "resources/js/models/IPaginate";
import {validationHelper} from "../../utils/validationHelper";
import {useStore} from "vuex";
import Upload from '../../components/Upload.vue'

export default defineComponent({
  name: "ClassesDetail",
  components: {Upload},
  setup() {
    const {
      setValidationErrors,
      getValidationErrors,
      hasValidationErrors,
      resetValidateErrors,
    } = validationHelper();
    const store = useStore()
    const $q = useQuasar()
    const route = useRoute()
    const idClass = ref("")
    const studentFocuse = ref<IStudentResult>()
    const class_code = ref<string>()
    const class_name = ref<string>()
    const teacher_id = ref<string>()
    const department_id = ref<string>()
    const optionSelectStudentActive = ref<[]>([]);
    const optionSelectStudent = ref<IStudentResult[]>([])
    const students = ref<IStudentResult[]>([])
    const dialogAdd = ref(false)
    const dialogDelete = ref(false)
    const dialogDeleteSelect = ref(false)
    const checkboxAll = ref<boolean | string>(false)
    const checkboxArray = ref<Array<string>>([])
    const listIdStudent = ref<Array<string>>([])
    const isFilter = ref<boolean>(false)
    const search = ref<string>('')
    const router = useRouter();

    const toggleFilter = (): void => {
      isFilter.value = !isFilter.value
    }
    const closeFilter = (): void => {
      isFilter.value = false
    }
    const isRequest = ref<boolean>(false)

    const page = ref<IPage>({
      currentPage: 1,
      total: 0,
      perPage: 10
    })
    const refSelect = ref<any>(null);

    const ruleSelect = [
      (val: any) => {
        return (val && val.length > 0) || 'Trường này không được bỏ trống!'
      }
    ]

    const redirectRouter = (nameRoute: string): void => {
      router.push({name: nameRoute});
    };

    const getValueLodash = (res: object, data: string, d: any = null) => {
      return _.get(res, data, d)
    }
    const handleGetClass = async (id: string) => {
      $q.loading.show()
      if (!isRequest.value) isRequest.value = true
      try {
        const data = await api.getClass(id)
        class_code.value = _.get(data, 'data.data.class.class_code', '')
        class_name.value = _.get(data, 'data.data.class.name', '')
        const teacherId = _.get(data, 'data.data.class.teacher_id', '')
        department_id.value = _.get(data, 'data.data.class.department.name', '')
        const user = store.getters["auth/getAuthUser"]
        teacher_id.value = _.get(user, 'full_name', '')
      } catch (error) {
        generateNotify("Không tải được dữ liệu lớp học !")
      }
      $q.loading.hide()
      isRequest.value = false
    }

    const getStudentByCondition = async (payload = {}, variable: any) => {
      try {
        const res = await api.getAllStudent<IPaginate<IStudentResult[]>>(payload);
        variable.value = _.get(res, 'data.data.students.data', '')
      } catch (error) {
        generateNotify("Không tải được dữ liệu lớp học !")
      }
    }

    watch(() => search.value, () => handleGetStudents())

    const handleAddStudent = async () => {
      refSelect.value.validate()
      if (refSelect.value.hasError) {
        return
      }
      ;
      if (!isRequest.value) isRequest.value = true
      $q.loading.show()
      try {
        await api.addStudent({student_ids: optionSelectStudentActive.value}, idClass.value)
        handleGetStudents()
        generateNotify("Thêm thành công sinh viên", true)
      } catch (error) {
        generateNotify("Không thêm được sinh viên vào lớp học !")
      }
      closeDialog()
      $q.loading.hide()
      isRequest.value = false
    }

    const handleDelete = async () => {
      $q.loading.show()
      if (!isRequest.value) isRequest.value = true
      try {
        await api.updateStudent<IStudentResult>({
          ...studentFocuse.value,
          class_id: null,
        }, studentFocuse.value?.id || 0)
        checkboxArray.value = []
        handleGetStudents()
        generateNotify("Xóa thành công sinh viên", true)
      } catch (error) {
        generateNotify("Không xóa được sinh viên vào lớp học !")
      }
      studentFocuse.value = undefined
      $q.loading.hide()
      isRequest.value = false
    }

    const handleDeleteSelect = async () => {
      $q.loading.show()
      if (!isRequest.value) isRequest.value = true
      const listId = Object.values(checkboxArray.value).map(value => parseInt(value))
      try {
        await api.addStudent({student_ids: listId}, 0)
        checkboxArray.value = [];
        handleGetStudents()
        generateNotify("Xóa công sinh viên", true)
      } catch (error) {
        generateNotify("Không xóa được sinh viên vào lớp học !")
      }
      $q.loading.hide()
      isRequest.value = false
    }

    const handleGetStudents = async () => {
      $q.loading.show()
      const payload: any = {
        class_id: idClass.value,
        page: page.value.currentPage,
      };
      if (search.value) {
        payload.q = search.value
      }
      try {
        const res = await api.getAllStudent<IPaginate<IStudentResult[]>>(payload);
        students.value = _.get(res, 'data.data.students.data')
        listIdStudent.value = students.value.map(student => student?.id?.toString() || '');
        page.value.currentPage = _.get(res, 'data.data.students.current_page', 1)
        page.value.total = _.get(res, 'data.data.students.last_page', 0)
        page.value.perPage = _.get(res, 'data.data.students.per_page', 0)
      } catch (error) {
        generateNotify("Không tải được dữ liệu sinh viên !")
      }
      $q.loading.hide()

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

    const handleFormatDate = (value: string): string => {
      if (!value) return ""
      return formatDate(value);
    }

    onMounted((): void => {
      idClass.value = <string>route.params.id


      if (idClass.value) {
        handleGetClass(idClass.value);
        handleGetStudents();
      }
    })

    watch(() => page.value.currentPage, () => handleGetStudents())

    const closeDialog = (): void => {
      dialogAdd.value = false
    }

    const closeDialogImport = (): void => {
      dialogImportDialog.value = false

    }

    const closeDialogDelete = (): void => {
      dialogDelete.value = false
    }

    const openDialogDelete = (student: IStudentResult): void => {
      dialogDelete.value = true
      studentFocuse.value = student
    }

    const setShow = (): void => {
      dialogAdd.value = true
      optionSelectStudentActive.value = []
    }

    const file = ref<object | null>(null)
    const dialogImportDialog = ref<boolean>(false)
    const openImportExcelDialog = () => {
      dialogImportDialog.value = true
    }

    const handleUploadExcel = (): void => {
      if (!isRequest.value) {
        isRequest.value = true
        $q.loading.show()
        const formData = new FormData

        if (!file.value) {
          generateNotify('Vui lòng chọn tệp', true)
          return
        }

        formData.append('excel', file.value)

        api.importStudentClass(parseInt(idClass.value), formData).then(res => {
          if (res) {
            generateNotify('Tải tệp thành công', true)
            handleGetStudents()
            closeDialogImport()
          }
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', [])

          errors.forEach(item => {
            generateNotify(`Lỗi dòng ${item.row} - ${item.errors[0]}`, false)
          })

        }).finally(() => {
          isRequest.value = false
          $q.loading.hide()

        })
      }

    }

    const uploadFileExcel = (value) => {
      file.value = value.file
    }

    const selectModel = ref([])

    const selectOptionFilter = ref<any[]>([])

    const filterFn = (val: any, update: any) => {
      if (val === '') {
        update(() => {
          optionSelectStudent.value = []
        })
        return
      }

      update(() => {
        const keySearch = val.toLowerCase()
        const payload: any = {
          page: page.value.currentPage,
          filter_student_code: keySearch,
          class_id: 0
        };
        getStudentByCondition(payload, optionSelectStudent)
      })
    }

    watch(() => checkboxAll.value, (value) => {
      if (value === true) {
        checkboxArray.value = listIdStudent.value;
      }

      if (value === false) {
        checkboxArray.value = []
      }
    })

    watch(() => checkboxArray.value, (value) => {
      if (value.length < listIdStudent.value.length) {
        checkboxAll.value = 'maybe'
      }

      if (value.length == 0) {
        checkboxAll.value = false
      }
    })
    const openDialogDeleteSelect = (id: string): void => {
      dialogDeleteSelect.value = true
    }

    return {
      selectModel, class_code, teacher_id, department_id, class_name,
      filterFn, redirectRouter,
      selectOptionFilter,
      handleFormatDate,
      handleGetClass,
      idClass,
      getValueLodash,
      students,
      dialogAdd,
      closeDialog,
      setShow,
      page,
      optionSelectStudent,
      optionSelectStudentActive,
      handleAddStudent,
      openDialogDelete,
      closeDialogDelete,
      dialogDelete,
      handleDelete,
      ruleSelect,
      getValidationErrors,
      hasValidationErrors,
      refSelect,
      checkboxArray,
      checkboxAll,
      dialogDeleteSelect,
      isFilter,
      toggleFilter,
      closeFilter,
      openDialogDeleteSelect,
      handleDeleteSelect,
      search,
      dialogImportDialog,
      openImportExcelDialog,
      uploadFileExcel,
      handleUploadExcel
    }
  }
})
</script>



