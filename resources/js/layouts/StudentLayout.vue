<template>
    <q-layout view="lHh Lpr lFf" class="main bg-grey-2" >
        <q-header elevated>
            <q-toolbar>
                <q-btn
                    flat
                    dense
                    round
                    @click="toggleLeftDrawer"
                    icon="menu"
                    aria-label="Menu"
                />
                <q-toolbar-title>
                    {{ title }}
                </q-toolbar-title>
                <q-space/>
                <div class="q-gutter-sm row items-center no-wrap">
                    <q-btn round dense flat icon="fas fa-heart" style="color:#9d4182 !important;" type="a" href="https://github.com/sponsors/pratik227" target="_blank">
                    </q-btn>
                    <q-btn round dense flat color="white" icon="notifications">
                        <q-badge color="red" text-color="white" floating>
                            5
                        </q-badge>
                        <q-menu
                        >
                            <q-list style="min-width: 100px">
                                <messages></messages>
                                <q-card class="text-center no-shadow no-border">
                                    <q-btn label="View All" style="max-width: 120px !important;" flat dense
                                           class="text-indigo-8"></q-btn>
                                </q-card>
                            </q-list>
                        </q-menu>
                    </q-btn>
                    <q-btn round flat>
                        <q-avatar size="26px">
                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                        </q-avatar>
                    </q-btn>
                </div>
            </q-toolbar>
        </q-header>

        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
            class="bg-white main-drawer"
            :width="240"
        >
            <q-scroll-area class="fit">
                <div class="logoWrapper">
                    <div class="logo">
                        <a href="">
                            <img src="/images/FITA.png" alt="">
                        </a>
                    </div>
                </div>
                <q-separator class="q-my-md"/>
                <q-list padding>
                    <q-item-label header class="text-weight-bold">
                        Thông tin sinh viên
                    </q-item-label>
                    <q-item v-for="link in links1" :key="link.text" v-ripple clickable @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon color="grey" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}</q-item-label>
                        </q-item-section>
                    </q-item>

                </q-list>

                <q-separator class="q-my-md"/>
                <q-list padding>
                    <q-item-label header class="text-weight-bold">
                        Quản lý lớp học
                    </q-item-label>
                    <q-item v-for="link in links2" :key="link.text" v-ripple clickable @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon color="grey" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}</q-item-label>
                        </q-item-section>
                    </q-item>

                </q-list>
            </q-scroll-area>
        </q-drawer>

        <q-page-container>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <router-view />
                </div>
            </div>
        </q-page-container>
    </q-layout>
</template>

<script lang="ts">
import {defineComponent, ref, computed } from 'vue'
import {fabYoutube} from '@quasar/extras/fontawesome-v6'
import {useRouter} from "vue-router/dist/vue-router";
import {useStore} from "vuex";

export default defineComponent({
    name: 'StudentLayout',

    setup() {
        const store = useStore()
        const leftDrawerOpen = ref(false)
        const router = useRouter()

        function toggleLeftDrawer(): void {
            leftDrawerOpen.value = !leftDrawerOpen.value
        }

        const redirectRouteName = (routeName: string): void => {
            router.push({name: routeName})
        }

        const title = computed(() => {
            return store.state.home.title
        })


        return {
            fabYoutube,

            leftDrawerOpen,
            title,

            toggleLeftDrawer,
            redirectRouteName,

            links1: [
                {icon: 'fa-solid fa-address-card', text: 'Hồ sơ sinh viên', routeName: 'HomeStudent'},
                {icon: 'fa-solid fa-users', text: 'Thông tin lớp học', routeName: 'ClassStudent'},
            ],
            links2: [
                {icon: 'fa-solid fa-circle-info', text: 'Duyệt thông tin', routeName: 'HomeStudent'},
                {icon: 'fa-solid fa-flag', text: 'Phản ánh lớp học', routeName: 'ClassStudent'},
            ]
        }
    }
})
</script>

<style lang="scss" scoped>
.logoWrapper {
    .logo {
        text-align: center;
        padding-top: 20px;

        a {

            img {
                width: 100px;
            }
        }
    }
}
.main {
    //background-color: #F3F5F8;
}
.main-drawer {
    //background-color: #F5F5F5!important;
}
.page-content-wrapper {
    .page-content {
        margin-top: 0;
        min-height: 600px;
        padding: 20px 20px 10px 20px;
    }
}
</style>
