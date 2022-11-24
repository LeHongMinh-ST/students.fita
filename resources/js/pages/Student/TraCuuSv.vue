<template>
    <div class="role-wrapper">
        <!-- <div class="popup">
            <CreateOrUpdateDepartment ref="popupRef" :getListDepartment="getListDepartment"
                :department="departmentCurrent" />
            <DeleteDepartment ref="popupDeleteRef" :getListDepartment="getListDepartment"
                :resetListIdDelete="resetListIdDelete" />
        </div> -->
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{ name: 'Home' }" />
            <q-breadcrumbs-el label="Thông tin sinh viên" />
        </q-breadcrumbs>
        <q-slide-transition>
            <q-card class="filter-wrapper" v-if="isFilter">
                <div class="filter-wrapper-content">
                    <div class="filter-header">
                        <div class="filter-header-text">Lọc dữ liệu</div>
                        <div class="filter-header-button">
                            <q-btn round icon="fa-solid fa-xmark" @click="closeFilter" size="sm" />
                        </div>
                    </div>
                </div>
                <q-card-section>
                    <q-btn color="primary" no-caps outline class="q-mr-sm">
                        Thêm bộ lọc
                    </q-btn>
                    <q-btn color="primary" no-caps class="q-mr-sm"> Áp dụng </q-btn>
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
                                    <q-item clickable v-close-popup @click="openDialogDeleteSelect(checkboxArray)">
                                        <q-item-section>
                                            <span>
                                                <q-icon name="fa-solid fa-trash" class="q-mr-sm" size="xs"></q-icon>Xoá
                                                ({{ checkboxArray.length }} bản ghi)
                                            </span>
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
                            <!-- <template v-slot:append>
                                <q-icon v-if="search !== ''" name="close" @click="search = ''" class="cursor-pointer" />
                                <q-icon name="search" />
                            </template> -->
                        </q-input>
                    </div>
                </div>
                <div class="table-wrapper-action">
                    <q-btn no-caps @click="onClickCreateBtn" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                        Tạo mới
                    </q-btn>
                    <q-btn no-caps @click="getListDepartment" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-refresh" class="q-mr-sm" size="xs"></q-icon>
                        Tải lại
                    </q-btn>
                </div>
            </q-card-section>
            <q-separator inset />
            <q-card-section>
                <q-markup-table class="role-table">
                    <thead>
                        <tr>
                            <th class="text-center" width="4%">
                                <q-checkbox v-model="checkboxAll" />
                            </th>
                            <th class="text-center" width="5%">STT</th>
                            <th class="text-left">Mã sinh viên</th>
                            <th class="text-left">Họ tên</th>
                            <th class="text-center">Giới tính</th>
                            <th class="text-left">Quê quán</th>
                            <th class="text-left">Ngày sinh</th>
                            <th class="text-center">Lớp</th>
                            <th class="text-center">Khoa</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">SĐT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="items && items.length > 0">
                            <tr v-for="(item, index) in items" :key="index">
                                <td class="text-center">
                                    <q-checkbox v-model="checkboxArray" :val="getValueLodash(department, 'id', 0)" />
                                </td>
                                <td class="text-center">
                                    {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "student_code", "") }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "full_name", "") }}
                                </td>
                                <td class="text-center">
                                    {{ getValueLodash(item, "gender", "") }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "countryside", "") }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "dob", "") }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "major", "") }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "email", "") }}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "phone", "") }}
                                </td>
                                <td class="text-center">
                                    <div class="inline cursor-pointer">
                                        <q-icon name="menu" size="sm"></q-icon>
                                        <q-menu touch-position>
                                            <q-list style="min-width: 100px">
                                                <q-item clickable v-close-popup @click="
                                                    openDialogUpdate(
                                                        item || null
                                                    )
                                                ">
                                                    <q-item-section>
                                                        <span>
                                                            <q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                size="xs"></q-icon>Chỉnh sửa
                                                        </span>
                                                    </q-item-section>
                                                </q-item>
                                                <q-item clickable v-close-popup @click="
                                                    openDialogDelete(
                                                        getValueLodash(item, 'id', 0)
                                                    )
                                                ">
                                                    <span>
                                                        <q-icon name="fa-solid fa-trash" class="q-mr-sm" size="xs">
                                                        </q-icon>Xoá
                                                    </span>
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
                    <q-inner-loading :showing="loadingDepartments" label-class="text-teal"
                        label-style="font-size: 1.1em" />
                </q-markup-table>
                <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
                    <q-pagination v-model="page.currentPage" :max="page.total" direction-links :max-pages="10" />
                </div>
            </q-card-section>
        </q-card>

    </div>
</template>

<style lang="css" src="./index.scss"></style>
<script lang="ts">
import _ from "lodash";
import { useQuasar } from "quasar";
import { defineComponent, getCurrentInstance, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import api from "../../api";
import { IDepartmentResult } from "../../models/IDepartmentResult";
import { IPage, IPayload, StudentFilter } from "../../models/IPage";
import IPaginate from "../../models/IPaginate";
import { HomeMutationTypes } from "../../store/modules/home/mutation-types";
import eventBus from "../../utils/eventBus";
import { formatDate } from "../../utils/helpers";
// import CreateOrUpdateDepartment from "./CreateOrUpdate.vue";
// import DeleteDepartment from "./Delete.vue";

export default defineComponent({
  name: "TraCuuSv",
  components: {
    // DeleteDepartment,
    // CreateOrUpdateDepartment
  },
  setup() {
    const {proxy} = getCurrentInstance();
    const $q = useQuasar();
    const store = useStore();
    const search = ref<string>("");
    const items = ref<Array<any>>([]);
    const itemIDs = ref<Array<number>>([]);
    const checkboxArray = ref<Array<number>>([]);
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
      const payload: StudentFilter = {
        page: 1,
      };

      if (search.value) {
        payload.student_code = search.value;
      }

      payload.page = page?.value?.currentPage;
      api
        .getAllStudent<IPaginate<any[]>>(payload)
        .then((res) => {
        items.value = _.get(res, "data.data.students.data");
          itemIDs.value = items.value.map(department => department.id);
          page.value.currentPage = _.get(res,"data.data.department.current_page", 1);
          page.value.total = _.get(res, "data.data.department.last_page", 0);
          page.value.perPage = _.get(res, "data.data.department.per_page", 0);
        })
        .catch(() => {
            generateNotify("Không tải được dữ liệu")
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

    const openDialogDeleteSelect = async (checkboxArray: any) => {
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
          checkboxArray.value = itemIDs.value;
        }

        if (value === false) {
          checkboxArray.value = [];
        }
      }
    );

    watch(
      () => checkboxArray.value,
      (value) => {
        if (value.length < itemIDs.value.length) {
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
        items,
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
</script>
