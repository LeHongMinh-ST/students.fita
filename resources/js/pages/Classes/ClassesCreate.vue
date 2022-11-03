<template>
    <div class="classes-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}" />
            <q-breadcrumbs-el label="Danh sách lớp học" />
            <q-breadcrumbs-el label="Tạo mới" />
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-9 q-pr-lg">
                    <q-card class="main-form">
                        <q-badge color="secondary" multi-line>
                                Model: "{{ optionDeparment }}"
                        </q-badge>
                        <div class="form-group">
                            <label for="code" class="text-bold">Mã lớp học <span class="required">*</span></label>
                            <q-input outlined dense v-model="code" id="code" ref="codeRef" :rules="codeRules"
                                :error-message="getValidationErrors('code')" :error="hasValidationErrors('code')" />
                        </div>
                        <div class="form-group">
                            <label for="name" class="text-bold">Tên <span class="required">*</span></label>
                            <q-input outlined dense v-model="name" id="name" ref="nameRef" :rules="nameRules"
                                :error-message="getValidationErrors('name')" :error="hasValidationErrors('name')" />
                        </div>
                        <div class="form-group">
                            <label for="department" class="text-bold">Bộ môn</label>
                            <div class="q-gutter-md">
                                <q-select filled outlined v-model="model" :options="optionDeparment" label="Chọn bộ môn" name="department" emit-value map-options/>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:30px">
                            <label for="teacher" class="text-bold">Giáo viên</label>
                            <div class="q-gutter-md">
                                <q-select outlined v-model="model" :options="optionTeacher" label="Chọn giáo viên" name="teacher"/>
                            </div>
                        </div>
                    </q-card>
                </div>
                <div class="col-3 right-sidebar">
                    <q-card class="widget meta-boxes action-horizontal">
                        <q-card>
                            <q-card-section>
                                <div class="widget-title text-bold">Tác vụ</div>
                            </q-card-section>
                            <q-separator />
                            <q-card-section>
                                <q-btn @click="handleCreateClass" no-caps color="secondary" class="q-mr-sm">
                                    <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                                    Lưu
                                </q-btn>
                                <q-btn @click="redirectRouter('Role')" no-caps color="warning" class="q-mr-sm">
                                    <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
                                    Quay lại
                                </q-btn>
                            </q-card-section>
                        </q-card>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {
        defineComponent,
        onMounted,
        ref,
        watch
    } from "vue"
    import api from "../../api"
    import {
        HomeMutationTypes
    } from "../../store/modules/home/mutation-types"
    import {
        useStore
    } from "vuex"
    import {
        useQuasar
    } from "quasar"
    import {
        useRouter
    } from "vue-router/dist/vue-router"
    import eventBus from "../../utils/eventBus"
    import {
        validationHelper
    } from "../../utils/validationHelper"
    import IPaginate from "../../models/IPaginate";
    import _ from "lodash";



    export default defineComponent({
        name: "ClassesCreate",
        setup() {
            const model = ref<any>([]);
            const modelTeacher = ref<any>([]);
            const optionDeparment=ref<Array<any>>([]);
            const optionTeacher=ref<Array<any>>([]);
            const store = useStore()
            const $q = useQuasar()
            const router = useRouter()
            const {
                setValidationErrors,
                getValidationErrors,
                hasValidationErrors,
                resetValidateErrors
            } = validationHelper()

            const name = ref<string>('')
            const nameRef = ref<any>(null)

            const departments = ref<Array<any>>([]);

            const nameRules = [
                val => (val && val.length > 0) || 'Trường tên lớp học không được bỏ trống!'
            ]

            const code = ref <string> ('')
            const codeRef = ref <any> (null)

            const codeRules = [
                val => (val && val.length > 0) || 'Trường mã lớp học không được bỏ trống!'
            ]

            const teacherId = ref<number> ()
            const departmentId = ref<number> ()
            
            

            const handleCreateClass = (): void => {
                nameRef.value.validate();
                codeRef.value.validate();

                if (isValidate()) {
                    $q.loading.show()
                    let data = {
                        name: name.value,
                        class_code: code.value,
                        teacher_id:teacherId,
                        department_id:departmentId,
                    }

                    api.createClass <[]> (data).then(res => {
                        if (res) {
                            eventBus.$emit('notify-success', 'Tạo mới lớp học thành công')
                            redirectRouter('Role')
                        }
                    }).catch(error => {
                        let errors = _.get(error.response, 'data.error', {})
                        if (Object.keys(errors).length === 0) {
                            let message = _.get(error.response, 'data.message', '')
                            $q.notify({
                                icon: 'report_problem',
                                message,
                                color: 'negative',
                                position: 'top-right'
                            })
                        }
                        if (Object.keys(errors).length > 0) {
                            setValidationErrors(errors)
                        }
                    }).finally(() => $q.loading.hide())
                }

            }

            const isValidate = (): boolean => {
                let isCheck = true

                if (nameRef.value.hasError) {
                    isCheck = false
                }

                return isCheck
            }

            const redirectRouter = (nameRoute: string): void => {
                router.push({
                    name: nameRoute
                })
            }

            const getListDepartment = (): void => {                
                
                const payload = {
                    page: 1,
                };

                api.getDepartments<IPaginate<[]>>(payload)
                    .then((res) => {
                        const department = ref<Array<any>>([]);
                        department.value = _.get(res, "data.data.department.data"); 
                        
                        
                        department.value.forEach(element => {
                            var objectOptionDepart = ref<any>({});
                            objectOptionDepart.label=element['name'];
                            objectOptionDepart.value=element['id'];
                            console.log(objectOptionDepart);
                            // objectOptionDepart.value.add(objectOptionDepart);
                            optionDeparment.value.push(objectOptionDepart);
                        });
                     
                        console.log("aaaaaa"+optionDeparment.value);                   
                    })
                    .catch(() => {
                        generateNotify("Không tải được danh sách bộ môn")
                    })
                    .finally(() => (console.log("aaaa")));
            };
            
            

            watch(name, (): void => {
                resetValidateErrors('name')
                nameRef.value.resetValidation()
                codeRef.value.resetValidation()
            })

            onMounted(() => {
                getListDepartment()
            })

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
            return {
                name,
                nameRef,
                nameRules,
                code,
                codeRef,
                codeRules,
                teacherId,
                departmentId,
                redirectRouter,
                handleCreateClass,
                getValidationErrors,
                hasValidationErrors,
                generateNotify,
                model,
                optionDeparment,
                optionTeacher,
                modelTeacher
            }
        }
    })

</script>

<style scoped lang="scss">
    .classes-wrapper {
        .main {
            margin-top: 20px;

            .main-form {
                padding: 10px;
                margin-bottom: 15px;

                .form-group {
                    margin-bottom: 15px;
                }
            }
        }
    }

</style>
