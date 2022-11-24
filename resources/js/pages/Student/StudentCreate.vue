<template>
  <div class="student-wrapper">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el label="Sinh viên" :to="{name: 'StudentIndex'}"/>
      <q-breadcrumbs-el label="Tạo mới"/>
    </q-breadcrumbs>
    <div class="main">
      <div class="row">
        <div class="col-9 q-pr-lg">
          <q-card class="main-form  meta-boxes">
            <q-card-section>
              <div class="widget-title text-bold">Thông tin chung</div>
            </q-card-section>
            <q-separator/>
            <q-card-section>
              <div class="row">
                <div class="col-3 q-pr-md">
                  <div class="form-group">
                    <label class="text-bold">Ảnh sinh viên </label>
                    <div>
                      <q-file
                          v-model="image"
                          label="Chọn ảnh đại diện"
                          outlined
                          dense
                          @update:model-value="handleUpload()"
                      ></q-file>
                    </div>
                    <div class="avatar-wrapper q-mt-sm">
                      <q-img
                          class="avatar-student"
                          :src="imageUrl"
                          spinner-color="white"
                          style="height: 275px; width: 100%"
                      ></q-img>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Niên khóa</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">CCCD/CMT</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Số điện thoại</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Email</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Quốc tịch</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                </div>
                <div class="col-9">
                  <div class="form-group">
                    <label class="text-bold">Mã Sinh Viên <span class="required">*</span></label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Tên <span class="required">*</span></label>
                    <q-input
                        outlined
                        dense
                        v-model="name"
                        ref="nameRef"
                        :rules="nameRules"
                        :error-message="getValidationErrors('name')"
                        :error="hasValidationErrors('name')"
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Giới tính <span class="required">*</span></label>
                    <q-select
                        outlined
                        dense
                        :options="genderList"
                        option-label="name"
                        option-value="values"
                        v-model="gender"
                        label="Chọn giới tính"
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Ngày sinh <span class="required">*</span></label>
                    <q-input outlined dense v-model="dob">
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                            <q-date mask="DD/MM/YYYY" :locale="myLocale" v-model="dob">
                              <div class="row items-center justify-end">
                                <q-btn v-close-popup label="Đóng" color="primary" flat/>
                              </div>
                            </q-date>
                          </q-popup-proxy>
                        </q-icon>
                      </template>
                    </q-input>
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Nơi sinh</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Quê quán</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Hộ khẩu thường trú</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="row">
                    <div class="col-6 q-pr-sm">
                      <div class="form-group">
                        <label class="text-bold">Dân tộc</label>
                        <q-input
                            outlined
                            dense
                        />
                      </div>
                    </div>
                    <div class="col-6 q-pr-sm">
                      <div class="form-group">
                        <label class="text-bold">Tôn giáo</label>
                        <q-input
                            outlined
                            dense
                        />
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Trình độ học vấn</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Khoa</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Lớp</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="family-wrapper q-mt-lg q-mb-lg">
                    <label class="text-bold label-family">Thông tin gia đình</label>
                    <div class="family-list q-pa-md">
                      <div class="row">
                        <div class="col-2 q-mr-sm">
                          <div class="form-group">
                            <label class="text-bold">Quan hệ</label>
                            <q-input
                                outlined
                                dense
                            />
                          </div>
                        </div>
                        <div class="col-4 q-mr-sm">
                          <div class="form-group">
                            <label class="text-bold">Họ và tên</label>
                            <q-input
                                outlined
                                dense
                            />
                          </div>
                        </div>
                        <div class="col-3 q-mr-sm">
                          <div class="form-group">
                            <label class="text-bold">Nghề nghiệp</label>
                            <q-input
                                outlined
                                dense
                            />
                          </div>
                        </div>
                        <div class="col-2 q-mr-sm">
                          <div class="form-group">
                            <label class="text-bold">Số điện thoại</label>
                            <q-input
                                outlined
                                dense
                            />
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <q-btn color="secondary">
                            <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
                            Thêm mới
                          </q-btn>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <div class="row">

                <div class="col">
                  <div class="form-group">
                    <label class="text-bold">Đối tượng chính sách xã hội</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>

                  <div class="form-group">
                    <label class="text-bold">Kết quả học tập</label>
                    <q-input
                        outlined
                        dense
                    />
                  </div>
                  <div class="form-group">
                    <label class="text-bold">Ghi chú</label>
                    <q-input type="textarea" outlined dense id="description" v-model="description"></q-input>
                  </div>
                </div>
              </div>
            </q-card-section>

          </q-card>

        </div>
        <div class="col-3 right-sidebar">
          <q-card class="widget meta-boxes action-horizontal q-mb-md">
            <q-card-section>
              <div class="widget-title text-bold">Tác vụ</div>
            </q-card-section>
            <q-separator/>
            <q-card-section>
              <q-btn @click="handleCreateStudent" no-caps color="secondary" class="q-mr-sm">
                <q-icon name="fa-solid fa-save" class="q-mr-sm" size="xs"></q-icon>
                Lưu
              </q-btn>
              <q-btn @click="redirectRouter('StudentIndex')" no-caps color="warning" class="q-mr-sm">
                <q-icon name="fa-solid fa-rotate-left" class="q-mr-sm" size="xs"></q-icon>
                Quay lại
              </q-btn>
            </q-card-section>
          </q-card>
          <q-card>
            <q-card-section>
              <div class="widget-title text-bold">Thiết lập sinh viên</div>
            </q-card-section>
            <q-separator/>
            <q-card-section>
              <div class="main-form">
                <div class="form-group">
                  <label class="text-bold">Tình trạng sinh viên</label>
                  <q-select
                      outlined
                      dense
                  />
                </div>

                <div class="form-group">
                  <label class="text-bold">Chức vụ</label>
                  <q-select
                      outlined
                      dense
                  />
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
import {defineComponent, onMounted, ref, watch} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useRouter} from "vue-router";
import {QDate, useQuasar} from "quasar";
import {validationHelper} from "../../utils/validationHelper";
import {GenderEnum} from "../../enums/gender.enum";
import moment from "moment";
import {date} from 'quasar';
import {MY_LOCALE} from "../../utils/constants";
import {IStudentResult} from "../../models/IStudentResult";

export default defineComponent({
  name: "StudentCreate",
  components: {QDate},
  setup() {
    const store = useStore()
    const $q = useQuasar()
    const router = useRouter()
    const {setValidationErrors, getValidationErrors, hasValidationErrors, resetValidateErrors} = validationHelper()

    const student = ref<IStudentResult>({full_name: "", student_code: ""})

    const name = ref<string>('')
    const nameRef = ref<any>(null)
    const nameRules = [
      val => (val && val.length > 0) || 'Trường tên vai trò không được bỏ trống!'
    ]

    const gender = ref<number | null>(null)
    const dob = ref<string>(moment().format('DD/MM/YYYY'))

    const myLocale= MY_LOCALE

    const image = ref(null);
    const imageUrl = ref('');
    const handleUpload = () => {
      if (image.value) {
        imageUrl.value = URL.createObjectURL(image.value);
      }
    }
    const genderList = [
      {name: 'Nam', value: GenderEnum.Male},
      {name: 'Nữ', value: GenderEnum.FeMale},
      {name: 'Khác', value: GenderEnum.Other},
    ]
    const description = ref<string>('')

    onMounted(() => {
      store.commit(`home/${HomeMutationTypes.SET_TITLE}`, 'Quản lý sinh viên')
    })

    const redirectRouter = (nameRoute: string): void => {
      router.push({name: nameRoute})
    }

    const isValidate = (): boolean => {
      let isCheck = true

      if (nameRef.value.hasError) {
        isCheck = false
      }

      return isCheck
    }

    const handleCreateStudent = () => {

    }

    watch(name, (): void => {
      resetValidateErrors('name')
      nameRef.value.resetValidation()
    })

    return {
      name,
      gender,
      nameRef,
      nameRules,
      description,
      redirectRouter,
      getValidationErrors,
      hasValidationErrors,
      handleCreateStudent,
      genderList,
      dob,
      image,
      imageUrl,
      handleUpload,
      myLocale
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

      .form-group {
        margin-bottom: 15px;
      }
    }

    .avatar-wrapper {
      height: 275px;
      width: 100%;
      border-radius: 5px;
      border: 1px solid #8f8f8f;

      .avatar-student {
        border-radius: 5px;
        object-fit: cover;
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
