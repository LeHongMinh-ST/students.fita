<template>
    <div class="student-wrapper">
        <q-breadcrumbs>
            <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}" />
            <q-breadcrumbs-el label="Sinh viên" />
            <q-breadcrumbs-el label="Thông tin sinh viên" />
        </q-breadcrumbs>
        <div class="main">
            <div class="row">
                <div class="col-12 q-pr-lg">
                    <q-card class="main-form meta-boxes">
                        <q-card-section>
                            <div class="text-bold">Thông tin báo cáo</div>
                        </q-card-section>
                        <q-card-section class="table-wrapper-title" style="justify-content: center !important">
                            <div class="q-px-md q-py-sm"
                                style="border: 1px solid black; margin: 10px; border-radius: 10px; width: 60%">
                                <div style="gap: 20px;">
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Tiêu đề</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            BC1</p>
                                    </div>
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Chủ đề</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            BC1</p>
                                    </div>
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Nội dung</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            Các học viên cao học có tên trong danh sách tốt nghiệp thạc sĩ đợt 3 năm 2022 kiểm tra chính xác thông tin cá nhân, nếu phát hiện có sai sót cần liên hệ với Ban Quản lý đào tạo để đính chính thông tin.

Thời hạn đính chính đến 17h00 ngày 11 tháng 11 năm 2022.

Thông tin đính chính gửi về Ban Quản lý đào tạo - P121 Bàn 7 (C/v Nguyễn Anh Tuấn: 024.6261.7520; Email: natuan.qldt@vnua.edu.vn). Quá thời hạn đính chính trên học viên hoàn toàn chịu trách nhiệm. </p>
                                    </div>
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Trạng thái báo cáo</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            Chờ duyệt</p>
                                    </div>
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Trạng thái duyệt</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            Chưa duyệt</p>
                                    </div>
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Người tạo</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            Lê Hồng Minh</p>
                                    </div>
                                    <div style="line-height:30px; width: 90%">
                                        <span style="font-weight: bold;" class="">Người duyệt</span>
                                        <p style="border: 1px solid grey; padding: 2px 10px 0px; border-radius: 3px;">
                                            Chưa có dữ liệu</p>
                                    </div>
                                </div>
                            </div>
                        </q-card-section>
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
        ref
    } from "vue";
    import useStudent from "../../uses/useStudent";
    import {
        useRoute
    } from "vue-router";
    import api from '../../api'
    import {
        IStudentResult
    } from "../../models/IStudentResult";
    import {
        useQuasar
    } from "quasar";

    export default defineComponent({
        name: "ReportStudentDetail",
        setup() {
            const route = useRoute()
            const {
                student,
                getStudent
            } = useStudent()
            const userId = ref < string > ('')
            const tab = ref < string > ('home')
            const $q = useQuasar()
            onMounted(() => {
                userId.value = < string > route.params.id
                getStudent(parseInt(userId.value))
            })
            const loading = ref < boolean > (false)
            const handleUpdateLearningOutcome = () => {
                loading.value = true
                api.updateLearningOutcome < IStudentResult > (student.value.id).then(res => {
                    student.value = _.get(res, 'data.data.student', {})
                }).catch(() => {
                    $q.notify({
                        icon: 'report_problem',
                        message: 'Không thể cập nhật dữ liệu',
                        color: 'negative',
                        position: 'top-right'
                    })
                }).finally(() => {
                    loading.value = false
                })
            }

            return {
                student,
                tab,
                handleUpdateLearningOutcome,
                loading
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

            .item {
                .item-header {
                    font-size: 18px;
                }
            }
        }


    }

</style>
