<template>
    <div class="q-pa-md row items-start q-gutter-md justify-center">
        <div class="col-12 loginWrapper">
        <q-card class="my-card">
            <q-card-section>
            <div class="row">
                <div class="col" style="display: flex; align-items: center;">
                    <div class="logoLogin">
                        <img src="/images/FITA.png" alt="">
                    </div>
                    <div class="textLoginHeader"><h4 class="textHeader text-center">Tra cứu thông tin sinh viên</h4></div>
                </div>
            </div>
            </q-card-section>
        </q-card>
        </div>
    </div>
    <div class="q-pa-md" style="max-width: 90%; margin: 0 auto;">
        <div class="row">
            <div class="col-6">
                <div class="main-form" style="margin-right: 16px;">
                    <q-input color="teal" outlined v-model="text" label="Họ và tên">
                        <template v-slot:append>
                        <q-avatar>
                            <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                        </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-6">
                <div class="main-form" style="margin-left: 16px;">
                    <q-input color="teal" outlined v-model="text" label="Ngày sinh">
                        <template v-slot:append>
                        <q-avatar>
                            <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                        </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-12">
                <div class="main-form">
                    <q-input color="teal" outlined v-model="text" label="Mã sinh viên">
                        <template v-slot:append>
                        <q-avatar>
                            <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                        </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-12" style="margin-top: 20px;">
                <q-btn color="red" icon-right="search" label="Tìm kiếm" />
            </div>
        </div>
    </div>

    <div class="q-pa-md" style="max-width: 90%; margin: 0 auto;">
        <div class="q-pa-md">
            <q-table
            title="Treats"
            :rows="rows"
            :columns="columns"
            row-key="name"
            />
        </div>
    </div>

</template>

  <script lang="ts">
  import {defineComponent, ref, watch} from 'vue'
  import api from '../api'
  import {QTable} from 'quasar'
  import _ from 'lodash'
const columns = [
  {
    name: 'name',
    required: true,
    label: 'Dessert (100g serving)',
    align: 'left',
    field: row => row.name,
    format: val => `${val}`,
    sortable: true
  },
  { name: 'calories', align: 'center', label: 'Calories', field: 'calories', sortable: true },
  { name: 'fat', label: 'Fat (g)', field: 'fat', sortable: true },
  { name: 'carbs', label: 'Carbs (g)', field: 'carbs' },
  { name: 'protein', label: 'Protein (g)', field: 'protein' },
  { name: 'sodium', label: 'Sodium (mg)', field: 'sodium' },
  { name: 'calcium', label: 'Calcium (%)', field: 'calcium', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10) },
  { name: 'iron', label: 'Iron (%)', field: 'iron', sortable: true, sort: (a, b) => parseInt(a, 10) - parseInt(b, 10) }
]

const rows = [
  {
    name: 'Frozen Yogurt',
    calories: 159,
    fat: 6.0,
    carbs: 24,
    protein: 4.0,
    sodium: 87,
    calcium: '14%',
    iron: '1%'
  },
  {
    name: 'Ice cream sandwich',
    calories: 237,
    fat: 9.0,
    carbs: 37,
    protein: 4.3,
    sodium: 129,
    calcium: '8%',
    iron: '1%'
  },
  {
    name: 'Eclair',
    calories: 262,
    fat: 16.0,
    carbs: 23,
    protein: 6.0,
    sodium: 337,
    calcium: '6%',
    iron: '7%'
  },
  {
    name: 'Cupcake',
    calories: 305,
    fat: 3.7,
    carbs: 67,
    protein: 4.3,
    sodium: 413,
    calcium: '3%',
    iron: '8%'
  },
  {
    name: 'Gingerbread',
    calories: 356,
    fat: 16.0,
    carbs: 49,
    protein: 3.9,
    sodium: 327,
    calcium: '7%',
    iron: '16%'
  },
  {
    name: 'Jelly bean',
    calories: 375,
    fat: 0.0,
    carbs: 94,
    protein: 0.0,
    sodium: 50,
    calcium: '0%',
    iron: '0%'
  },
  {
    name: 'Lollipop',
    calories: 392,
    fat: 0.2,
    carbs: 98,
    protein: 0,
    sodium: 38,
    calcium: '0%',
    iron: '2%'
  },
  {
    name: 'Honeycomb',
    calories: 408,
    fat: 3.2,
    carbs: 87,
    protein: 6.5,
    sodium: 562,
    calcium: '0%',
    iron: '45%'
  },
  {
    name: 'Donut',
    calories: 452,
    fat: 25.0,
    carbs: 51,
    protein: 4.9,
    sodium: 326,
    calcium: '2%',
    iron: '22%'
  },
  {
    name: 'KitKat',
    calories: 518,
    fat: 26.0,
    carbs: 65,
    protein: 7,
    sodium: 54,
    calcium: '12%',
    iron: '6%'
  }
]

export default {
    name: 'TraCuuThongTinSV',
  setup () {
    return {
      columns,
      rows,
      text: ref('')
    }
  }
}
</script>

  <style scoped lang="scss">
    .main-form {
        margin-top: 20px;
    }
    .breadcrumb {
        background-image: url(https://sv.iuh.edu.vn/Content/loginnews/images/city2.png);
        background-size: cover;
        padding-top: 50px;
        padding-bottom: 80px;
    }
  .loginWrapper {
    margin-top: 0vw;

    .logoLogin {
      width: 4vw;
      img {
        width: 100%;
      }
    }

    .textLoginHeader {
      .textHeader {
        margin: 10px;
      }
    }
  }

  </style>
