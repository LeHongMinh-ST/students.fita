<template>
  <div class="classes-wrapper">
    <q-breadcrumbs class="q-pb-lg">
      <q-breadcrumbs-el
        label="Bảng điều khiển"
        icon="home"
        :to="{ name: 'HomeStudent' }"
      />
      <q-breadcrumbs-el label="Chi tiết lớp học" />
    </q-breadcrumbs>

    <q-card class="table-wrapper">
      <q-card-section style="display: flex; justify-content: end">
        <q-btn
          no-caps
          color="warning"
          class="q-mr-sm"
          @click="redirectRouter('HomeStudent')"
        >
          <q-icon
            name="fa-solid fa-rotate-left"
            class="q-mr-sm"
            size="xs"
          ></q-icon>
          Quay lại
        </q-btn>
      </q-card-section>
      <div v-if="!auth.class_id">
        <q-separator inset />
        <q-card-section
          class="table-wrapper-title"
          style="justify-content: center !important"
        >
          <div class="text-h6">Bạn chưa được thêm vào lớp!</div>
        </q-card-section>
      </div>
      <div v-else>
        <q-separator inset />
        <q-card-section class="table-wrapper-title row wrapper-info-class">
          <div class="q-px-md q-py-sm wrapper-info-class-content">
            <span class="q-px-sm field-info">Thông tin lớp học</span>
            <div style="display: flex; padding-top: 10px; gap: 20px">
              <div class="area-field">
                <span class="text-weight-bold">Mã lớp</span>
                <p class="field-info-detail">
                  {{ class_code ?? "Đang cập nhật" }}
                </p>
              </div>
              <div class="area-field">
                <span class="text-weight-bold">Tên lớp</span>
                <p
                  class="field-info-detail"
                >
                  {{ class_name ?? "Đang cập nhật" }}
                </p>
              </div>
              <div class="area-field">
                <span class="text-weight-bold">Bộ môn</span>
                <p
                 class="field-info-detail"
                >
                  {{ department_name ?? "Đang cập nhật" }}
                </p>
              </div>
              <div class="area-field">
                <span class="text-weight-bold">Giáo viên</span>
                <p
                  class="field-info-detail"
                >
                  {{ teacher_name ?? "Đang cập nhật" }}
                </p>
              </div>
            </div>
          </div>
        </q-card-section>
        <q-separator inset />
        <q-card-section class="table-wrapper-title">
          <div class="table-wrapper-filter">
            <div class="table-wrapper-search">
              <q-input
                bottom-slots
                v-model="search"
                label="Nhập từ khóa để tìm kiếm"
                outlined
                dense
              >
                <template v-slot:append>
                  <q-icon
                    v-if="search !== ''"
                    name="close"
                    @click="search = ''"
                    class="cursor-pointer"
                  />
                  <q-icon name="search" />
                </template>
              </q-input>
            </div>
          </div>
        </q-card-section>
        <q-card-section class="wrapper-table">
          <q-markup-table class="student-table">
            <thead>
              <tr>
                <th class="text-center" width="4%">STT</th>
                <th class="text-left">Mã sinh viên</th>
                <th class="text-left">Họ tên</th>
                <th class="text-left">Giới tính</th>
                <th class="text-left">Ngày sinh</th>
                <th class="text-left">Chuyên ngành</th>
                <th class="text-left">Email</th>
                <th class="text-left">SĐT</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="students.length > 0">
                <tr v-for="(student, index) in students" :key="index">
                  <td class="text-center">
                    {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
                  </td>
                  <td class="text-left">
                      {{
                          getValueLodash(student, "student_code", "") ??
                          "Chưa cập nhật"
                      }}
                  </td>
                  <td class="text-left">
                      {{ getValueLodash(student, "full_name", "") ?? "Chưa cập nhật" }}
                  </td>
                  <td class="text-left">
                    {{ getValueLodash(student, "gender_text", "") ?? "Chưa cập nhật"}}
                  </td>
                  <td class="text-left">
                    {{ getValueLodash(student, "dob", "") ?? "Chưa cập nhật"}}
                  </td>
                  <td class="text-left">
                      {{ getValueLodash(student, "major", "") ?? "Chưa cập nhật"}}
                  </td>
                  <td class="text-left">
                    {{ getValueLodash(student, "phone", "") ?? "Chưa cập nhật"}}
                  </td>
                  <td class="text-left">
                      {{ getValueLodash(student, "email",  "") ?? "Chưa cập nhật"}}
                  </td>
                </tr>
              </template>
              <template v-else>
                <tr>
                  <td colspan="8" class="text-center">
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
      </div>
    </q-card>
  </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref, watch} from "vue";
import api from "../../apiStudent";
import {useQuasar} from "quasar";
import _ from "lodash";
import {useRouter} from "vue-router";
import {IStudentResult} from "resources/js/models/IStudentResult";
import {formatDate} from "../../utils/helpers";
import {IPage} from "resources/js/models/IPage";
import IPaginate from "resources/js/models/IPaginate";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useStore} from "vuex";
import Upload from "../../components/Upload.vue";

export default defineComponent({
    name: "ClassesDetailStudent",
    components: {Upload},
    setup() {
        const store = useStore();
        const $q = useQuasar();
        const class_code = ref<string>();
        const class_name = ref<string>();
        const teacher_name = ref<string>();
        const department_name = ref<string>();
        const students = ref<IStudentResult[]>([]);
        const search = ref<string>("");
        const router = useRouter();

        const page = ref<IPage>({
            currentPage: 1,
            total: 0,
            perPage: 10,
        });

        const redirectRouter = (nameRoute: string): void => {
            router.push({name: nameRoute});
        };

        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d);
        };

        watch(
            () => search.value,
            () => handleGetStudents()
        );

        const handleGetStudents = async () => {
            $q.loading.show();
            const payload: any = {
                page: page.value.currentPage,
            };
            if (search.value) {
                payload.q = search.value;
            }
            try {
                const res = await api.getClassUserCurrent<IPaginate<IStudentResult[]>>(
                    payload
                );
                students.value = _.get(res, "data.data.students.data", []);
                teacher_name.value = _.get(res, "data.data.class.teacher.full_name")
                class_code.value = _.get(res, "data.data.class.class_code")
                class_name.value = _.get(res, "data.data.class.name")
                department_name.value = _.get(res, "data.data.class.department.name")
                page.value.currentPage = _.get(
                    res,
                    "data.data.data.students.current_page",
                    1
                );
                page.value.total = _.get(res, "data.data.data.students.last_page", 0);
                page.value.perPage = _.get(res, "data.data.data.students.per_page", 0);
            } catch (error) {
                generateNotify("Không tải được dữ liệu sinh viên!");
            }
            $q.loading.hide();
    };

    const generateNotify = (message: string, isSuccess = false) => {
      isSuccess
        ? $q.notify({
            icon: "check",
            message: message,
            color: "positive",
            position: "top-right",
          })
        : $q.notify({
            icon: "report_problem",
            message: message,
            color: "negative",
            position: "top-right",
          });
    };

    const handleFormatDate = (value: string): string => {
      if (!value) return "";
      return formatDate(value);
    };

    const auth = store.getters["authStudent/getAuthUserStudent"];
    onMounted((): void => {
       store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Chi tiết lớp học')
      if (auth.class_id) {
        handleGetStudents();
      }
    });

    watch(
      () => page.value.currentPage,
      () => handleGetStudents()
    );

    return {
      class_code,
      teacher_name,
      department_name,
      class_name,
      redirectRouter,
      handleFormatDate,
      getValueLodash,
      students,
      page,
      search,
      auth,
    };
  },
});
</script>
<style scoped lang="scss">
.wrapper-table{
  padding-top: 0px;
  .student-table{
        tr {
          th {
            text-transform: uppercase;
            font-weight: bold;
            color: #949597;
          }

          td {
            .text-link {
              color: #337ab7;
            }
          }
        }
  }
}
.table-wrapper-title{
  padding-bottom: 0px;
  .table-wrapper-search {
          margin-top: 20px;
          width: 20vw;
        }
}
.wrapper-info-class {
  justify-content: center !important;
  padding: 0px !important;

  .wrapper-info-class-content {
    border: 1px solid black;
    margin: 10px;
    border-radius: 10px;
    width: 60%;

    .field-info {
      position: absolute;
      top: 0;
      background-color: white;
      font-weight: bold;
    }
    .area-field {
      line-height: 30px;
      width: 20%;
      .field-info-detail {
        border: 1px solid grey;
        padding: 2px 10px 0px;
        border-radius: 3px;
      }
    }
  }
}
</style>
