<template>
    <q-dialog v-model="isShow" persistent @hide="closeDialog">
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="fa-solid fa-trash" color="red" text-color="white" />
          {{listIdDepartment ? true : false}}
        <span v-if="listIdDepartment?.length ? true : false" class="q-ml-sm"
            >Bạn có chắc chắn muốn xóa {{ listIdDepartment?.length }} bản ghi! Dữ
            liệu không thể phục hồi!
        </span>
        <span v-else class="q-ml-sm"
            >Bạn có chắc chắn muốn xóa! Dữ liệu không thể phục hồi!
        </span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn
            flat
            label="Đóng"
            color="primary"
            @click="closeDialog"
            v-close-popup
          />
          <q-btn
            label="Đồng ý"
            color="red"
            @click="handleDelete"
            v-close-popup
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

</template>
<script>
import { ref } from 'vue'
import api from "../../api";
import { useQuasar } from "quasar";

export default ({
    name: "DeleteDepartment",
    props: {
        getListDepartment: {
            type: Function,
        },
        resetListIdDelete: {
            type: Function
        }
    },
    setup({getListDepartment, resetListIdDelete}) {
        const isShow = ref(false);
        const departmentId = ref();
        const listIdDepartment = ref([])

        const $q = useQuasar();

        const setDepartmentId = (id) => {
            departmentId.value = id
        }

        const setListIdDepartment = (listId) => {
            listIdDepartment.value = listId
        }

        const closeDialog = () => {
            resetFrom([departmentId, listIdDepartment]);
        };

        const handleDelete = () => {
            if(listIdDepartment.value?.length) {
                $q.loading.show();
                const data = {
                    id: listIdDepartment.value,
                };
                api
                .deleteDepartmentSelected(data)
                .then(() => {
                    getListDepartment();
                    closeDialog();
                    resetListIdDelete();
                    generateNotify("Xóa thành công bộ môn", true)
                })
                .catch(() => {
                    generateNotify("Không xóa được bộ môn")
                })
                .finally(() => $q.loading.hide());
            } else {
                $q.loading.show();
                api
                    .deleteDepartment(departmentId.value)
                    .then(() => {
                        getListDepartment();
                        closeDialog();
                        generateNotify("Xóa thành công bộ môn", true)
                    })
                    .catch(() => {
                        generateNotify("Không xóa được bộ môn")
                    })
                    .finally(() => $q.loading.hide());
            }
        };

        const generateNotify = (message, isSuccess=false) => {
            isSuccess ? $q.notify({icon: "check",
            message: message,
            color: "positive",
            position: "top-right",}) :
            $q.notify({ icon: "report_problem",
            message: message,
            color: "negative",
            position: "top-right"})
        }

        const resetFrom = (arrProperties) => {
        for (const element of arrProperties) {
            const type = typeof element.value
            switch (type) {
                case 'string':
                    element.value = ""
                    break;
                case 'boolean':
                    element.value = false
                    break;
                case 'number':
                    element.value = 0
                    break;
                case 'object':
                    element.value = null
                    break;
                }
            }
        }

        const onChangeDialog = () => {
            isShow.value = true;
        }

        return {
            handleDelete,
            onChangeDialog,
            isShow,
            closeDialog,
            setDepartmentId,
            setListIdDepartment,
            listIdDepartment
        }
    },
})
</script>

