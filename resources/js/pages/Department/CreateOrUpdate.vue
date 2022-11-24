<template>
     <q-dialog v-model="isShow" @hide="closeDialog">
      <q-card style="width: 300px">
        <q-card-section>
          <div class="text-h6">Thêm mới bộ môn</div>
        </q-card-section>
        <q-card-section class="row items-center" style="width: 100%">
                <label for="code" class="text-bold"
                >Mã bộ môn <span class="required">*</span></label
              >
              <q-input
                class="full-width"
                outlined
                dense
                v-model="code"
                id="code"
                ref="codeRef"
                :rules="autoGenerateRule(`mã bộ môn`)"
                :error-message="getValidationErrors('code')"
                :error="hasValidationErrors('code')"
              ></q-input>
                 <label for="name" class="text-bold"
                >Tên bộ môn <span class="required">*</span></label
              >
              <q-input
                class="full-width"
                outlined
                dense
                v-model="name"
                id="name"
                ref="nameRef"
                :rules="autoGenerateRule(`tên bộ môn`)"
                :error-message="getValidationErrors('name')"
                :error="hasValidationErrors('name')"
              />
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
            @click="handleCreateOrUpdate"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>
</template>
<script>
import { validationHelper } from "../../utils/validationHelper";
import { getCurrentInstance, ref } from 'vue'
import api from "../../api";
import { useQuasar } from "quasar";

export default ({
    name: "CreateOrUpdateDepartment",
    props: {
        getListDepartment: {
            type: Function,
        }
    },
    setup({getListDepartment}) {
        const nameRef = ref(null);
        const name = ref("")
        const codeRef = ref(null);
        const code = ref("")
        const isShow = ref(false);
        const departmentCurrent = ref();

        const $q = useQuasar();

        const setDepartmentCurrent = (department) => {
            departmentCurrent.value = department
            name.value = departmentCurrent.value.name;
            code.value = departmentCurrent.value.department_code;
        }

        const closeDialog = () => {
            resetFrom([code, name, isShow, departmentCurrent]);
        };

        const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
        } = validationHelper();

        const isValidate = () => {
            let isCheck = true
            if (nameRef.value.hasError || codeRef.value.hasError) {
                isCheck = false
            }
            return isCheck
        }

        const handleCreateOrUpdate = () => {
            codeRef.value.validate()
            nameRef.value.validate()

            if(isValidate()){
                const payload = {
                    department_code: code.value,
                    name: name.value,
                }
                if(departmentCurrent.value){
                    api.updateDepartment(payload, departmentCurrent.value.id)
                .then(() => {
                    getListDepartment();
                    closeDialog();
                    generateNotify("Sửa thành công bộ môn", true)
                })
                .catch(() => {
                    generateNotify("Sửa mới thất bại")
                })
                .finally(() => {
                    $q.loading.hide()
                });
                } else
                api.createDepartment(payload)
                .then(() => {
                    getListDepartment();
                    closeDialog();
                    generateNotify("Thêm mới thành công bộ môn", true)
                })
                .catch(() => {
                    generateNotify("Thêm mới thất bại bộ môn")
                })
                .finally(() => {
                    $q.loading.hide()
                });
            }
        }

        const autoGenerateRule = (name) => {
            return  [
                (val) =>
                (val && val.trim().length > 0) || `Trường ${name} không được bỏ trống!`,
            ];
        }

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
            name, nameRef, code, codeRef, handleCreateOrUpdate,setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors,
            autoGenerateRule,
            onChangeDialog,
            isShow,
            closeDialog,
            departmentCurrent,
            setDepartmentCurrent
        }
    },
})
</script>

