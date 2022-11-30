<template>
    <div class="classes-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
            <q-breadcrumbs-el label="Danh sách lớp học"/>
            <q-breadcrumbs-el label="Tạo mới"/>
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-9 q-pr-lg">
                    <q-card class="main-form">
                        <div class="form-group">
                            <label class="text-bold">Mã lớp học <span class="required">*</span></label>
                            <q-input outlined dense v-model="class_code" id="class_code" ref="codeRef"
                                     :rules="class_code"
                                     :error-message="getValidationErrors('class_code')"
                                     :error="hasValidationErrors('class_code')"/>
                        </div>
                        <div class="form-group">
                            <label class="text-bold">Tên <span class="required">*</span></label>
                            <q-input outlined dense v-model="name" id="name" ref="nameRef" :rules="nameRules"
                                     :error-message="getValidationErrors('name')" :error="hasValidationErrors('name')"/>
                        </div>
                        <div class="form-group">
                            <label for="department" class="text-bold">Bộ môn</label>
                            <div class="q-gutter-md">
                                <q-select
                                    emit-value
                                    map-options
                                    :ref="refInput.department_id"
                                    borderless
                                    dense
                                    outlined
                                    v-model="department_id"
                                    option-value="id"
                                    option-label="name"
                                    :options="departments"
                                    :rules="rule.department_id"
                                />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="department" class="text-bold">Giáo viên</label>
                            <div class="q-gutter-md">
                                <q-select
                                    emit-value
                                    map-options
                                    :ref="refInput.department_id"
                                    borderless
                                    dense
                                    outlined
                                    v-model="teacher_id"
                                    option-value="id"
                                    option-label="full_name"
                                    :options="users"
                                    :rules="rule.teacher_id"
                                />
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
                            <q-separator/>
                            <q-card-section>
                                <q-btn @click="handleSave" no-caps color="secondary" class="q-mr-sm">
                                    <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                                    Lưu
                                </q-btn>
                                <q-btn @click="redirectRouter('Classes')" no-caps color="warning" class="q-mr-sm">
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
        reactive,
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
    import {useRouter} from "vue-router";
    import eventBus from "../../utils/eventBus"
    import {
        validationHelper
    } from "../../utils/validationHelper"
    import { IDepartmentResult } from "../../models/IDepartmentResult";
    import IUserResult from "../../models/IUserResult";
    import IPaginate from "../../models/IPaginate";
    import _ from "lodash";


export default defineComponent({
    name: "ClassesCreate",
    setup() {
        const model = ref<any>([]);
        const modelTeacher = ref<any>([]);
        const optionDeparment = ref<Array<any>>([]);
        const optionTeacher = ref<Array<any>>([]);
        const store = useStore()
        const $q = useQuasar()
        const router = useRouter()
        const route = useRoute()
        const {
            setValidationErrors,
            getValidationErrors,
            hasValidationErrors,
            resetValidateErrors
        } = validationHelper()

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

        const users = ref<Array<any>>([]);

        const redirectRouter = (nameRoute: string): void => {
            router.push({name: nameRoute})
        }

            const redirectRouter = (nameRoute: string): void => {
             router.push({name: nameRoute});
            };

        const refInput: any = {
            name: ref<any>(null),
            class_code: ref<any>(null),
            teacher_id: ref<any>(null),
            department_id: ref<any>(null),
        };

        const classes: any = {
            class_code: ref<string>(""),
            name: ref<string>(""),
            teacher_id: ref<string>(""),
            department_id: ref<string>(""),
        };

        const payload = reactive({...classes})

        const getListDepartment = (): void => {
            const payload = {
                page: 1,
                limit: 100,
            };
            api
                .getDepartments<IPaginate<IDepartmentResult[]>>(payload)
                .then((res) => {
                    departments.value = _.get(res, "data.data.department.data");
                })
                .catch(() => {
                    $q.notify({
                        icon: "report_problem",
                        message: "Không tải được danh sách nhóm vai trò!",
                        color: "negative",
                        position: "top-right",
                    });
                })
                .finally(() => {
                });
        };

        const getListUserIsLecturers = (): void => {
            const payload = {
                page: 1,
                limit: 100,
            }
            api.getUsers<IPaginate<IUserResult[]>>(payload).then(res => {
                users.value = _.get(res, 'data.data.users.data')

            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được danh sách người dùng!',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally()
        };

        onMounted(() => {
            getListDepartment();
            getListUserIsLecturers();
            idClass.value = <string>route.params.id
            if (idClass.value) {
                handleGetClass(idClass.value);
            }
        })

        const handleSave = (): void => {
            const data = JSON.parse(JSON.stringify(payload));
            if (idClass.value) {
                api.updateClass(data, idClass.value).then(res => {
                    if (res) {
                        eventBus.$emit('notify-success', 'Cập nhật lớp học thành công')
                        redirectRouter('User')
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
            } else {
                api.createClass(data).then(res => {
                    if (res) {
                        eventBus.$emit('notify-success', 'Tạo mới lớp học thành công')
                        redirectRouter('Classes')
                    }
                }).catch(error => {
                    let errors = _.get(error.response, 'data.error', {})
                    console.log('errors', errors)
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

        const handleGetClass = (id: string): void => {
            $q.loading.show()
            api.getClass<{}>(id).then(res => {
                const data = _.get(res, 'data.data.class', '')
                for (const key in classes) {
                    classes[key].value = data[key]
                }
            }).catch(() => {
                $q.notify({
                    icon: 'report_problem',
                    message: 'Không tải được dữ liệu lớp học !',
                    color: 'negative',
                    position: 'top-right'
                })
            }).finally(() => $q.loading.hide())
        }
        return {
            handleSave,
            redirectRouter,
            getValidationErrors,
            hasValidationErrors,
            model,
            optionDeparment,
            optionTeacher,
            modelTeacher,
            departments,
            users,
            rule,
            refInput,
            ...classes
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
