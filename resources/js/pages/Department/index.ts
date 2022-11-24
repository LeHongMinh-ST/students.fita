import _ from "lodash";
import { useQuasar } from "quasar";
import { defineComponent, getCurrentInstance, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import api from "../../api";
import { IDepartmentResult } from "../../models/IDepartmentResult";
import { IPage, IPayload } from "../../models/IPage";
import IPaginate from "../../models/IPaginate";
import { HomeMutationTypes } from "../../store/modules/home/mutation-types";
import eventBus from "../../utils/eventBus";
import { formatDate } from "../../utils/helpers";
import CreateOrUpdateDepartment from "./CreateOrUpdate.vue";
import DeleteDepartment from "./Delete.vue";

export default defineComponent({
  name: "DepartmentIndex",
  components: {
    DeleteDepartment,
    CreateOrUpdateDepartment
  },
  setup() {
    const {proxy} = getCurrentInstance()
    const $q = useQuasar();
    const store = useStore();
    const search = ref<string>("");
    const departments = ref<Array<IDepartmentResult>>([]);
    const departmentIds = ref<Array<string>>([]);
    const checkboxArray = ref<Array<string>>([]);
    const checkboxAll = ref<boolean | string>(false);
    const departmentCurrent = ref<any>({})
    const page = ref<IPage>({
      currentPage: 1,
      total: 0,
      perPage: 10,
    });

    const currentPage = ref<number>(1);
    const loadingDepartments = ref<boolean>(false);
    const isFilter = ref<boolean>(false);
    const toggleFilter = (): void => {
      isFilter.value = !isFilter.value;
    };
    const closeFilter = (): void => {
      isFilter.value = false;
    };

    const onClickCreateBtn = async () => {
        proxy.$refs.popupRef?.onChangeDialog()
    }

    const handleFormatDate = (value: string): string => {
      return formatDate(value);
    };

    const getListDepartment = (): void => {
      loadingDepartments.value = true;
      const payload: IPayload = {
        page: 1,
      };

      if (search.value) {
        payload.q = search.value;
      }

      payload.page = page?.value?.currentPage;
      api
        .getDepartments<IPaginate<IDepartmentResult[]>>(payload)
        .then((res) => {
          departments.value = _.get(res, "data.data.department.data");

          departmentIds.value = departments.value.map(department => department.id);
                page.value.currentPage = _.get(
            res,
            "data.data.department.current_page",
            1
          );
          page.value.total = _.get(res, "data.data.department.last_page", 0);
          page.value.perPage = _.get(res, "data.data.department.per_page", 0);
        })
        .catch(() => {
            generateNotify("Không tải được danh sách bộ môn")
        })
        .finally(() => (loadingDepartments.value = false));
    };

    const openDialogDelete = async (id: number) => {
        proxy.$refs.popupDeleteRef?.setDepartmentId(id)
        proxy.$refs.popupDeleteRef?.onChangeDialog()
    };

    const openDialogUpdate = async (department: any) => {
        if(!department) return;
        proxy.$refs.popupRef?.setDepartmentCurrent(department)
        proxy.$refs.popupRef?.onChangeDialog()
    };

    const openDialogDeleteSelect = async (checkboxArray) => {
        proxy.$refs.popupDeleteRef?.setListIdDepartment(checkboxArray)
        proxy.$refs.popupDeleteRef?.onChangeDialog()

    };

    const getValueLodash = (res: object, data: string, d: any = null) => {
        return _.get(res, data, d);
    };

    const generateNotify = (message: string, isSuccess=false) => {
        isSuccess ? $q.notify({icon: "check",
        message: message,
        color: "positive",
        position: "top-right",}) :
        $q.notify({ icon: "report_problem",
        message: message,
        color: "negative",
        position: "top-right"})
    }


    watch(
      () => page?.value?.currentPage,
      () => getListDepartment()
    );
    watch(
      () => search.value,
      () => getListDepartment()
    );
    watch(
      () => checkboxAll.value,
      (value) => {
        if (value === true) {
          checkboxArray.value = departmentIds.value;
        }

        if (value === false) {
          checkboxArray.value = [];
        }
      }
    );

    watch(
      () => checkboxArray.value,
      (value) => {
        if (value.length < departmentIds.value.length) {
          checkboxAll.value = "maybe";
        }

        if (value.length == 0) {
          checkboxAll.value = false;
        }
      }
    );

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Quản lý bộ môn");
      eventBus.$on("notify-success", (message: string) => {
        generateNotify(message, true)
      });
        getListDepartment();

    });

    const resetListIdDelete = () => {
        checkboxArray.value = [];
    }

    return {
        openDialogUpdate,
        search,
        isFilter,
        toggleFilter,
        closeFilter,
        handleFormatDate,
        onClickCreateBtn,
        getValueLodash,
        currentPage,
        departments,
        loadingDepartments,
        getListDepartment,
        page,
        openDialogDelete,
        openDialogDeleteSelect,
        checkboxArray,
        checkboxAll,
        resetListIdDelete,
        departmentCurrent,
    };
  },
});
