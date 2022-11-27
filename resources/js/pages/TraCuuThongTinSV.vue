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
                            <div class="textLoginHeader">
                                <h4 class="textHeader text-center">Tra cứu thông tin sinh viên</h4>
                            </div>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </div>
    </div>
    <div class="q-pa-md" style="max-width: 90%; margin: 0 auto;">
        <div class="row">
            <div class="col-4">
                <div class="main-form me-3">
                    <q-input color="teal" outlined v-model="sv.hoTen" label="Họ và tên">
                        <template v-slot:append>
                            <q-avatar>
                                <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                            </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-4">
                <div class="main-form me-3">
                    <q-input color="teal" outlined v-model="sv.ngaySinh" label="Ngày sinh">
                        <template v-slot:append>
                            <q-avatar>
                                <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                            </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-4">
                <div class="main-form me-3">
                    <q-input color="teal" outlined v-model="sv.maSV" label="Mã sinh viên">
                        <template v-slot:append>
                            <q-avatar>
                                <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                            </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-4">
                <div class="main-form me-3">
                    <q-input color="teal" outlined v-model="sv.lop" label="Lớp">
                        <template v-slot:append>
                            <q-avatar>
                                <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                            </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-4">
                <div class="main-form me-3">
                    <q-input color="teal" outlined v-model="sv.khoa" label="Khoa">
                        <template v-slot:append>
                            <q-avatar>
                                <img src="https://cdn.quasar.dev/logo-v2/svg/logo.svg">
                            </q-avatar>
                        </template>
                    </q-input>
                </div>
            </div>
            <div class="col-4">
                <div class="main-form me-3">
                    <q-input color="teal" outlined v-model="sv.khoa" label="Khoa">
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

    <!-- <div class="q-pa-md" style="max-width: 90%; margin: 0 auto;">
        <div class="q-pa-md">
            <q-table
            title="Treats"
            :rows="rows"
            :columns="columns"
            row-key="name"
            />
        </div>
    </div> -->

    <table class="table table-striped table-customize">
        <thead>
            <tr>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in items" :key="item.id">
                <td>{{ item['name'] }}</td>
                <td>{{ item['height'] }}</td>
                <td>j{{ item['weight'] }}</td>
            </tr>
        </tbody>
    </table>


</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import _ from 'lodash'
import type { Header, Item } from "vue3-easy-data-table";
import IStudent from "../models/IStudent";


export default defineComponent({

    setup() {

        let sv: IStudent = new IStudent();

        const items: Item[] = [
            { "name": "Curry", "height": 178, "weight": 77, "age": 20 },
            { "name": "James", "height": 180, "weight": 75, "age": 21 },
            { "name": "Jordan", "height": 181, "weight": 73, "age": 22 },
            { "name": "Jordan", "height": 181, "weight": 73, "age": 22 },
            { "name": "Jordan", "height": 181, "weight": 73, "age": 22 },
            { "name": "Jordan", "height": 181, "weight": 73, "age": 22 }
        ];

        return {
            items, sv
        };
    },
});

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

.table-customize {
    width: 90%;
    margin: auto;
    background-color: #f2f2f2;
    border-radius: 5px;
    overflow: hidden;

    thead {
        tr {
            th {
                text-align: start;
                padding: 15px 5px;
                background-color: rgb(140, 183, 231);

            }
        }
    }

    tbody {
        tr {
            td {
                padding: 10px 5px;
            }
        }
    }
}

.table-customize tbody tr:nth-child(odd) {
    background-color: rgb(246, 255, 255);
}

.table-customize tbody tr:nth-child(even) {
    background-color: #fff;
}

.table-customize tbody tr:hover {
    background-color: #f8e6e6;
}

table,
th,
td {
    border: 0.1px solid rgb(209, 200, 200);
    border-collapse: collapse;
}
</style>
