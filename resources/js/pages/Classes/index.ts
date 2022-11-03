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
    const roleIds = ref<Array<string>>([])

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
      api.deleteRole(roleId.value).then(() => {
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

    const handleGetRoleIds = (): void => {
      api.getAllRoleId<number[]>().then(res => roleIds.value = _.get(res, 'data.data.classes', []))
    }

    const handleDeleteSelect = () => {
      $q.loading.show()
      const data = {
        role_id: checkboxArray.value
      }
      api.deleteSelected(data).then(() => {
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
        checkboxArray.value = roleIds.value
      }

      if (value === false) {
        checkboxArray.value = []
      }

    })

    watch(() => checkboxArray.value, (value) => {
      if (value.length < roleIds.value.length) {
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
      handleGetRoleIds()
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