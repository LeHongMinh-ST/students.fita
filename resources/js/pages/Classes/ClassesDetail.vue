<template>
    <div class="classes-wrapper">
    <q-breadcrumbs>
      <q-breadcrumbs-el label="Bảng điều khiển" icon="home" :to="{name: 'Home'}"/>
      <q-breadcrumbs-el label="Chi tiết lớp học"/>
    </q-breadcrumbs>
    <q-card class="table-wrapper">
      <q-card-section class="table-wrapper-title">
        <div>
          <div>Mã lớp học :{{ getValueLodash(classes, 'class_code', '') }} </div>
          <div>Tên lớp học :{{ getValueLodash(classes, 'name', '') }} </div>
          <div>Bộ môn :{{ getValueLodash(classes.department, 'name', '') }} </div>
          <div>Giáo viên : {{classes.class_code}}</div>
        </div>
      </q-card-section>
      <q-separator inset/>
      <q-card-section>Danh sách sinh viên</q-card-section>
      <q-card-section>  
        <q-markup-table class="role-table">
          <thead>
          <tr>
            
            <th class="text-center" width="5%">STT</th>
            <th class="text-left">Mã lớp</th>
            <th class="text-left">Tên lớp</th>
            <th class="text-left">Bộ môn</th>
            <th class="text-left">Giáo viên</th>
            <th class="text-center">Ngày tạo</th>
            <th class="text-left">Được tạo bởi</th>
          </tr>
          </thead>
          <tbody>
          
          </tbody>
          
        </q-markup-table>
        
      </q-card-section>
    </q-card>
    
    
  </div>
</template>
<style lang="css" src="./index.scss"></style>
<script lang="ts">
  import {defineComponent, onMounted, ref, watch} from "vue";
import {useStore} from "vuex";
import {HomeMutationTypes} from "../../store/modules/home/mutation-types";
import {useRouter,useRoute,} from "vue-router/dist/vue-router";
import api from "../../api";
import eventBus from "../../utils/eventBus";
import {useQuasar} from "quasar";
import {formatDate} from "../../utils/helpers";
import IPaginate from "../../models/IPaginate";


export default defineComponent({
    name: "ClassesDetail",
    setup() {
      const $q = useQuasar()
      const store = useStore()
      const router = useRoute()
      const idClass = ref("")
      const classes: any = {
        class_code: ref<string>(),
        name: ref<string>(),
        teacher_id: ref<string>(),
        department_id: ref<string>(),
      };
  
      
        const getValueLodash = (res: object, data: string, d: any = null) => {
            return _.get(res, data, d)
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
      onMounted((): void => {
       
        idClass.value = <string> router.params.id
        
        if(idClass.value) {
            handleGetClass(idClass.value);
        }
      })
  
  
      return {
        handleGetClass,
        idClass,
        classes,
        getValueLodash
      }
    }
  })
</script>



