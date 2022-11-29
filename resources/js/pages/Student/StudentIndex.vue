<template>
    <div class="role-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{ name: 'Home' }" />
            <q-breadcrumbs-el label="Thông tin sinh viên" />
        </q-breadcrumbs>
        <q-card class="table-wrapper">
            <div style="height: 20px;"></div>
            <div class="body-search">
                <div class="bg">
                    <div class="box-search text-center q-pt-md">
                        <label class="q-pa-sm q-mt-lg text-bold">Nhập mã sinh viên</label>
                        <q-input outlined dense class="q-pa-sm q-mt-sm" @keyup.enter="handleGetStudent"
                                 :error-message="getValidationErrors('studentCode')"
                                 :error="hasValidationErrors('studentCode')"
                                 @update:model-value="() => resetValidateErrors('studentCode')"
                                 v-model="studentCode"
                                 placeholder="Nhập mã sinh viên"/>
                        <div class="ss-sbm q-mt-md">
                            <q-btn @click="handleGetStudent" no-caps class="q-mr-sm btn">
                                <q-icon name="fa-solid fa-search" class="q-mr-sm" size="xs"></q-icon>
                                Tìm kiếm
                            </q-btn>
                        </div>
                    </div>
                </div>

            </div>

            <q-card-section class="table-wrapper-title">
                <div class="table-wrapper-action">
                    <q-btn no-caps @click="redirectRouter('StudentCreate')" color="secondary" class="q-mr-sm">
                        <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                        Tạo mới
                    </q-btn>

                </div>
            </q-card-section>
            <q-separator inset />
            <q-card-section>
                <q-markup-table class="role-table">
                    <thead>
                    <tr>
                        <th class="text-center" width="5%">STT</th>
                        <th class="text-left">Mã sinh viên</th>
                        <th class="text-left">Họ tên</th>
                        <th class="text-center">Giới tính</th>

                        <th class="text-left">Ngày sinh</th>
                        <th class="text-center">Lớp</th>
                        <th class="text-center">Chuyên ngành</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">SĐT</th>
                        <th class="text-center">Tác vụ</th>
                    </tr>
                    </thead>
                    <tbody>
                        <template v-if="items && items.length > 0">
                            <tr v-for="(item, index) in items" :key="index">
                                <td class="text-center">
                                    {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
                                </td>
                                <td class="text-left">
                                     <span class="cursor-pointer text-bold text-primary"
                                           @click="redirectRouter('StudentDetail',{id: item?.id})">
                                           {{ getValueLodash(item, "student_code", "") ?? "Chưa cập nhật" }}
                                    </span>
                                </td>
                                <td class="text-left">
                                    <span class="cursor-pointer text-bold text-primary"
                                          @click="redirectRouter('StudentDetail',{id: item?.id})">
                                        {{ getValueLodash(item, "full_name", "") ?? "Chưa cập nhật" }}
                                    </span>

                                </td>
                                <td class="text-center">
                                    {{ getValueLodash(item, "gender_text", "") ?? "Chưa cập nhật"}}
                                </td>
                                <td class="text-left">
                                    {{ getValueLodash(item, "dob", "") ?? "Chưa cập nhật"}}
                                </td>
                                <td class="text-center">
                                    {{ getValueLodash(item, "general_class.name",  "") ?? "Chưa cập nhật"}}
                                </td>
                                <td class="text-center">
                                    {{ getValueLodash(item, "major", "") ?? "Chưa cập nhật"}}
                                </td>
                                <td class="text-center">
                                    {{ getValueLodash(item, "email",  "") ?? "Chưa cập nhật"}}
                                </td>
                                <td class="text-center">
                                    {{ getValueLodash(item, "phone", "") ?? "Chưa cập nhật"}}
                                </td>

                                <td class="text-center">
                                    <div class="inline cursor-pointer">
                                        <q-icon name="menu" size="sm"></q-icon>
                                        <q-menu touch-position>
                                            <q-list style="min-width: 100px">
                                                <q-item clickable v-close-popup
                                                @click="redirectRouter('StudentUpdate', {id: getValueLodash(item, 'id', 0) })">
                                                    <q-item-section>
                                                        <span>
                                                            <q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                                                size="xs"></q-icon>Chỉnh sửa
                                                        </span>
                                                    </q-item-section>
                                                </q-item>
                                            </q-list>
                                        </q-menu>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="11" class="text-center">
                                    <img class="imgEmpty" src="/images/empty.png" alt=""/>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <q-inner-loading :showing="loading" label-class="text-teal"
                                     label-style="font-size: 1.1em"/>
                </q-markup-table>
                <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
                    <q-pagination v-model="page.currentPage" :max="page.total" direction-links :max-pages="10" />
                </div>
            </q-card-section>
        </q-card>

    </div>
</template>

<script lang="ts">
import _ from "lodash";
import {useQuasar} from "quasar";
import {defineComponent, onMounted, ref, watch} from "vue";
import {useRouter} from "vue-router";
import {useStore} from "vuex";
import api from "../../api";
import {IPage, StudentFilter} from "../../models/IPage";
import IPaginate from "../../models/IPaginate";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import eventBus from "../../utils/eventBus";
import {formatDate} from "../../utils/helpers";
import {validationHelper} from "../../utils/validationHelper";
// import DeleteDepartment from "./Delete.vue";

export default defineComponent({
    name: "StudentIndex",
    setup() {
        const $q = useQuasar();
        const store = useStore();
        const studentCode = ref<string>("");
        const items = ref<Array<any>>([]);
        const itemIDs = ref<Array<number>>([]);
        const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()

        const page = ref<IPage>({
            currentPage: 1,
            total: 0,
            perPage: 10,
        });

        const currentPage = ref<number>(1);
        const loading = ref<boolean>(false);
        const isFilter = ref<boolean>(false);
        const router = useRouter();
        const toggleFilter = (): void => {
            isFilter.value = !isFilter.value;
        };
        const closeFilter = (): void => {
            isFilter.value = false;
        };

        const handleFormatDate = (value: string): string => {
            return formatDate(value);
        };

        const handleGetStudent = (): void => {


            if (!studentCode.value) {
                setValidationErrors({
                    studentCode: ['Vui lòng nhập mã sinh viên']
                })
                return
            }
            loading.value = true;
            const payload: StudentFilter = {
                page: 1,
            };
            payload.student_code = studentCode.value;
            payload.page = page?.value?.currentPage;
            api
                .getAllStudent<IPaginate<any[]>>(payload)
                .then((res) => {
                    items.value = _.get(res, "data.data.students.data");
                    itemIDs.value = items.value.map(department => department.id);
                    page.value.currentPage = _.get(res, "data.data.students.current_page", 1);
                    page.value.total = _.get(res, "data.data.students.last_page", 0);
                    page.value.perPage = _.get(res, "data.data.students.per_page", 0);
                })
                .catch(() => {
                    generateNotify("Không tải được dữ liệu")
                })
                .finally(() => (loading.value = false));
        };


        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d);
        };

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

        const redirectRouter = (nameRoute: string, params: any | [] = null): void => {
                router.push({name: nameRoute, params: params})
        }

        const gender = {
            "0": "Nam",
            "1": "Nữ"
        }

        watch(
            () => page?.value?.currentPage,
            () => handleGetStudent()
        );


        onMounted(() => {
            store.commit(`home/${HomeMutationTypes.SET_TITLE}`, "Tra cứu thông tin sinh viên");
            eventBus.$on("notify-success", (message: string) => {
                generateNotify(message, true)
            });
        });



        return {
            studentCode,
            isFilter,
            toggleFilter,
            closeFilter,
            handleFormatDate,
            getValidationErrors,
            hasValidationErrors,
            getValueLodash,
            currentPage,
            items,
            loading,
            handleGetStudent,
            page,
            gender,
            redirectRouter,
            resetValidateErrors
        };
    },
});
</script>

<style scoped lang="scss">

.table-wrapper {
    margin-top: 20px;
}
.body-search {
    width: 100%;
    height: 170px;

    div.bg {
        // background-color: #7FC1ED;
        border-radius: 5px;
        width: 440px;
        height: 170px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;

        div.box-search {
            width: 400px;
            height: 170px;
            border: 1px solid black;
            border-radius: 5px;
            margin: 0px auto;
            position: relative;
            background-color: #ffffff;
            label {
                font-size: 16px;
            }

            div.lbmsv {
                width: 100%;
                text-align: center;
                margin: 15px 0px;
            }

            input.iputsmv {
                width: 80%;
                margin: 0px 40px;
                margin-bottom: 15px;

            }

            div.ss-sbm {
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            q-btn.btn {
                margin: 0px auto;
                width: 50px;
                height: 25px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                background-color: antiquewhite;
                border-radius: 5px;
            }
        }
    }


}

input.iputsmv:focus {
    border: 1px solid rgb(58, 161, 202);
    outline: none;
}

// table, th, td {
//     border: 1px solid rgb(94, 94, 94);
//     border-collapse: collapse;
// }
// tr{
//     background-color: #F9F9F9;
//     color: #000080;

//     th{
//         color: #ffffff !important;
//         background-color: #2D8ECE !important;
//     }
// }

</style>
