<template>
    <div class="student-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
            <q-breadcrumbs-el label="Sinh viên"/>
            <q-breadcrumbs-el label="Thông tin sinh viên"/>
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-3 q-pr-lg">
                    <q-card class="main-form meta-boxes">
                        <q-card-section>
                            <div class="text-bold">Thông tin sinh viên</div>
                        </q-card-section>
                        <q-separator />
                        <q-card-section>
                           <div class="q-pa-md">
                               <div class="avatar">
                                   <q-img class="avatar-student" :src="student.thumbnail ?? '/images/User-Default.jpg'">

                                   </q-img>
                               </div>

                               <div class="student-name text-center">
                                   <div class="text-bold"> {{ student.full_name }}</div>
                                   <div>{{ student.student_code }}</div>
                               </div>
                               <q-separator />
                               <div class="main-action q-mt-md text-center">
                                   <q-btn color="secondary" class="q-mr-sm">
                                       <q-icon name="fa-solid fa-pen-to-square" class="q-mr-sm"
                                               size="xs"></q-icon>Chỉnh sửa
                                   </q-btn>

                                   <q-btn color="red">
                                       <q-icon name="fa-solid fa-trash" class="q-mr-sm"
                                               size="xs"></q-icon>Xóa
                                   </q-btn>
                               </div>
                           </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-9">
                    <q-card>
                        <q-tabs
                            v-model="tab"
                            class="text-grey"
                            active-color="primary"
                            indicator-color="primary"
                            align="justify"
                            narrow-indicator
                        >
                            <q-tab name="home" label="Thông tin chung" />
                            <q-tab name="learning_outcome" label="Kết quả học tập" />
                        </q-tabs>

                        <q-separator />

                        <q-tab-panels v-model="tab" animated>
                            <q-tab-panel name="home">
                                <div class="text-h6">Thông tin chung</div>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            </q-tab-panel>

                            <q-tab-panel name="learning_outcome">
                                <div class="text-h6">Kết quả học tập</div>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                            </q-tab-panel>


                        </q-tab-panels>
                    </q-card>
                </div>
            </div>
        </div>

    </div>
</template>

<script lang="ts">
import {defineComponent, onMounted, ref} from "vue";
import useStudent from "../../uses/useStudent";
import {useRoute} from "vue-router";

export default defineComponent({
    name: "StudentDetail",
    setup() {
        const route = useRoute()
        const {student, getStudent} = useStudent()
        const userId = ref<string>('')
        const tab = ref<string>('home')
        onMounted(() => {
            userId.value = <string>route.params.id
            getStudent(userId.value)
        })

        return {
            student,tab
        }
    }
})
</script>

<style scoped lang="scss">
.student-wrapper {
    .main {
        margin-top: 20px;

        .main-form {
            margin-bottom: 15px;

            .avatar {
                text-align: center;

                .avatar-student {
                    height: 200px;
                    width: 200px;
                    border-radius: 5px;
                }
            }

            .student-name {
                margin: 10px;
                font-size: 20px;
            }
        }


        .family-wrapper {
            border: 1px solid #000000;
            border-radius: 5px;
            position: relative;

            .label-family {
                position: absolute;
                top: 0px;
                left: 5px;
                padding: 0px 10px;
                transform: translateY(-50%);
                background-color: #ffffff;
            }
        }
    }


}


</style>
