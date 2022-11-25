<template>
  <div class="classes-wrapper">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el label="Danh sách lớp học"/>
    </q-breadcrumbs>
    <q-slide-transition>
      <q-card class="filter-wrapper" v-if="isFilter">
        <div class="filter-wrapper-content">
          <div class="filter-header">
            <div class="filter-header-text">Lọc dữ liệu</div>
            <div class="filter-header-button">
              <q-btn round icon="fa-solid fa-xmark" @click="closeFilter" size="sm"/>
            </div>
          </div>
        </div>
        <q-card-section>
          <q-btn color="primary" no-caps outline class="q-mr-sm">
            Thêm bộ lọc
          </q-btn>
          <q-btn color="primary" no-caps class="q-mr-sm">
            Áp dụng
          </q-btn>
        </q-card-section>
      </q-card>
    </q-slide-transition>
    <q-card class="table-wrapper">
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
          <q-btn class="q-mr-sm" no-caps color="primary" @click="toggleFilter">
            <q-icon name="fa-solid fa-filter" class="q-mr-sm" size="xs"></q-icon>
            Lọc dữ liệu
          </q-btn>

          <div class="table-wrapper-search">
            <q-input bottom-slots v-model="search" label="Nhập từ khóa để tìm kiếm" outlined dense>

              <template v-slot:append>
                <q-icon v-if="text !== ''" name="close" @click="text = ''" class="cursor-pointer"/>
                <q-icon name="search"/>
              </template>
            </q-input>
          </div>
        </div>
        <div class="table-wrapper-action">
          <q-btn no-caps @click="redirectRouter('ClassesCreate')" color="secondary" class="q-mr-sm">
            <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
            Tạo mới
          </q-btn>
          <q-btn no-caps @click="getListClasses" color="secondary" class="q-mr-sm">
            <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
            Tải lại
          </q-btn>
        </div>

      </q-card-section>
      <q-separator inset/>
      <q-card-section>
        <q-markup-table class="role-table">
          <thead>
          <tr>
            <th class="text-center" width="4%">
              <q-checkbox v-model="checkboxAll"/>
            </th>
            <th class="text-center" width="5%">STT</th>
            <th class="text-left">Mã lớp</th>
            <th class="text-left">Tên lớp</th>
            <th class="text-left">Bộ môn</th>
            <th class="text-left">Giáo viên</th>
            <th class="text-center">Ngày tạo</th>
            <th class="text-center">Tác vụ</th>
          </tr>
          </thead>
          <tbody>
          <template v-if="classes.length > 0">
            <tr v-for="(classItem, index) in classes" :key="index">
              <td class="text-center">
                <q-checkbox v-model="checkboxArray" :val="getValueLodash(classItem, 'id', 0)"/>
              </td>
              <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
              <td class="text-left">
                  <span @click="redirectRouter('ClassesDetail', {id: getValueLodash(classItem, 'id', 0)})"
                        class="text-bold cursor-pointer text-link">
                    {{ getValueLodash(classItem, 'class_code', '') }}
                  </span>
              </td>
              <td class="text-left">
                  <span @click="redirectRouter('ClassesDetail', {id: getValueLodash(classItem, 'id', 0)})"
                        class="text-bold cursor-pointer text-link">
                    {{ getValueLodash(classItem, 'name', '') }}
                  </span>
              </td>
              <td class="text-left"> {{ getValueLodash(classItem.department, 'name', 'Không có bộ môn') }}</td>
              <td class="text-left"> {{ getValueLodash(classItem, 'teacher.full_name', 'Không có giáo viên') }}</td>
              <td class="text-center">{{ handleFormatDate(getValueLodash(classItem, 'created_at', '')) }}</td>
              <td class="text-center">
                <div class="inline cursor-pointer">
                  <q-icon name="menu" size="sm"></q-icon>
                  <q-menu touch-position>
                    <q-list style="min-width: 100px">
                      <q-item clickable v-close-popup
                              @click="redirectRouter('ClassesDetail', {id: getValueLodash(classItem, 'id', 0)})">
                        <q-item-section>  
                          
                                                    <span><q-icon name="fa-solid fa-eye" class="q-mr-sm"
                                                                  size="xs"></q-icon>Xem chi tiết</span>
                        </q-item-section>
                      </q-item>
                      <q-item clickable v-close-popup
                              @click="redirectRouter('ClassesUpdate', {id: getValueLodash(classItem, 'id', 0)})">
                        <q-item-section>
                                                    <span><q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                  size="xs"></q-icon>Chỉnh sửa</span>
                        </q-item-section>
                      </q-item>
                      <q-item clickable v-close-popup
                              @click="openDialogDelete(getValueLodash(classItem, 'id', 0))">
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
                <img class="imgEmpty" src="/images/empty.png" alt="">
              </td>
            </tr>
          </template>
          </tbody>
          <q-inner-loading
              :showing="loadingClasses"
              label-class="text-teal"
              label-style="font-size: 1.1em"
          />
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
    <q-dialog v-model="dialogDelete" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="fa-solid fa-trash" color="red" text-color="white"/>
          <span class="q-ml-sm">Bạn có chắc chắn muốn xóa! Dữ liệu không thể phục hồi!</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Đóng" color="primary" @click="closeDialog" v-close-popup/>
          <q-btn label="Đồng ý" color="red" @click="handleDelete" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
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
  </div>
</template>
<style lang="css" src="./index.scss"></style>
<script lang="ts">
  import {defineComponent, onMounted, ref, watch} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useRouter} from "vue-router/dist/vue-router";
import api from "../../api";
import eventBus from "../../utils/eventBus";
import {useQuasar} from "quasar";
import {formatDate} from "../../utils/helpers";
import IPaginate from "../../models/IPaginate";

export default defineComponent({
  name: "ClassesIndex",
  setup() {
    const $q = useQuasar()
    const store = useStore()
    const router = useRouter()

    const search = ref<string>('')
    const dialogDelete = ref<boolean>(false)
    const dialogDeleteSelect = ref<boolean>(false)
    const roleId = ref<string>('')
    const classes = ref<Array<any>>([])
    
    const classIds = ref<Array<string>>([])

    const checkboxArray = ref<Array<string>>([])
    const checkboxAll = ref<boolean | string>(false)
    const page = ref<Object>({
      currentPage: 1,
      total: 0,
      perPage: 10
    })

    const currentPage = ref<number>(1)

    const loadingClasses = ref<boolean>(false)
    const isFilter = ref<boolean>(false)
    const toggleFilter = (): void => {
      isFilter.value = !isFilter.value
    }
    const closeFilter = (): void => {
      isFilter.value = false
    }

    const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
      router.push({name: nameRoute, params: params})
    }

    const handleFormatDate = (value: string): string => {
      return formatDate(value)
    }

    const getListClasses = (): void => {

      loadingClasses.value = true
      const payload = {
        page: 1,
      }

      if (search.value) {
        payload.q = search.value
      }

      payload.page = page?.value?.currentPage;

      api.getClasses<IPaginate<[]>>(payload).then(res => {
        classes.value = _.get(res, 'data.data.class.data')

        console.log("aaaaaaaaaaa "+classes);
        page.value.currentPage = _.get(res, 'data.data.class.current_page', 1)
        page.value.total = _.get(res, 'data.data.class.last_page', 0)
        page.value.perPage = _.get(res, 'data.data.class.per_page', 0)        
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không tải được danh sách nhóm vai trò!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => loadingClasses.value = false)
    }

    const openDialogDelete = (id: string): void => {
      dialogDelete.value = true
      roleId.value = id
    }
    const openDialogDeleteSelect = (id: string): void => {
      dialogDeleteSelect.value = true
    }


    const closeDialog = (): void => {
      dialogDelete.value = false
      dialogDeleteSelect.value = false
      roleId.value = ''
    }

    const handleDelete = (): void => {
      $q.loading.show()
      api.deleteClass(roleId.value).then(() => {
        getListClasses()
        closeDialog()
        $q.notify({
          icon: 'check',
          message: 'Xóa thành công nhóm vai trò',
          color: 'positive',
          position: 'top-right'
        })
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không xóa được nhóm vai trò!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => $q.loading.hide())
    }

    const getValueLodash = (res: object, data: string, d: any = null) => {
      return _.get(res, data, d)
    }

    const handleGetClassIds = (): void => {
      api.getAllClassId<number[]>().then(res=>{
        console.log("test"+res);
        classIds.value = _.get(res, 'data.data.classes', [])
      } )
    }

    const handleDeleteSelect = () => {
      $q.loading.show()
      const data = {
        id: checkboxArray.value
      }
      api.deleteSelectedClass(data).then(() => {
        getListClasses()
        closeDialog()
        checkboxArray.value = []
        $q.notify({
          icon: 'check',
          message: 'Xóa thành công nhóm vai trò',
          color: 'positive',
          position: 'top-right'
        })
      }).catch(() => {
        $q.notify({
          icon: 'report_problem',
          message: 'Không xóa được nhóm vai trò!',
          color: 'negative',
          position: 'top-right'
        })
      }).finally(() => $q.loading.hide())
    }

    watch(() => page.value.currentPage, () => getListClasses())
    watch(() => search.value, () => getListClasses())
    watch(() => checkboxAll.value, (value) => {
      if (value === true) {
        checkboxArray.value = classIds.value
      }

      if (value === false) {
        checkboxArray.value = []
      }

    })

    watch(() => checkboxArray.value, (value) => {
      if (value.length < classIds.value.length) {
        checkboxAll.value = 'maybe'
      }

      if (value.length == 0) {
        checkboxAll.value = false
      }
    })

    onMounted((): void => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý lớp học')
      eventBus.$on('notify-success', message => {
        $q.notify({
          icon: 'check',
          message: message,
          color: 'positive',
          position: 'top-right'
        })
      })
      getListClasses()
      handleGetClassIds()
    })


    return {
      search,
      isFilter,
      toggleFilter,
      closeFilter,
      handleFormatDate,
      redirectRouter,
      getValueLodash,
      handleDelete,
      handleDeleteSelect,
      currentPage,
      classes,
      loadingClasses,
      getListClasses,
      page,
      dialogDelete,
      openDialogDelete,
      openDialogDeleteSelect,
      dialogDeleteSelect,
      closeDialog,
      checkboxArray,
      checkboxAll,
    }
  }
})
</script>



