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

                    <q-btn round flat>
                        <q-avatar size="26px">
                            <img :src="auth.thumbnail ? auth.thumbnail_url : 'https://cdn.quasar.dev/img/avatar4.jpg'">
                        </q-avatar>
                        <q-menu>
                            <div class="row no-wrap q-pa-md">

                                <div class="column items-center">

                                    <q-avatar size="72px">
                                        <img
                                            :src="auth.thumbnail ? auth.thumbnail_url : 'https://cdn.quasar.dev/img/avatar4.jpg'">
                                    </q-avatar>

                                    <div class="text-subtitle1 q-mt-md q-mb-xs">{{ auth.full_name }}</div>

                                    <q-btn
                                        color="primary"
                                        label="Đăng xuất"
                                        push
                                        size="sm"
                                        v-close-popup
                                        @click="logout"
                                    />
                                </div>
                            </div>
                        </q-menu>
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
                    <q-item v-for="link in links1" :key="link.text" v-ripple clickable active-class="bg-grey text-white"
                            :active="checkActive(link.routeName)" @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon :color="checkActive(link.routeName) ? 'white' : 'grey'" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}</q-item-label>
                        </q-item-section>
                    </q-item>

                </q-list>

                <q-separator v-if="checkClassMonitor()" class="q-my-md"/>
                <q-list  v-if="checkClassMonitor()" padding>
                    <q-item-label header class="text-weight-bold">
                        Quản lý lớp học
                    </q-item-label>
                    <q-item v-for="link in links2" :key="link.text" v-ripple clickable active-class="bg-grey text-white"
                            :active="checkActive(link.routeName)" @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon :color="checkActive(link.routeName) ? 'white' : 'grey'" :name="link.icon"/>
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
import {computed, defineComponent, ref} from 'vue'
import {fabYoutube} from '@quasar/extras/fontawesome-v6'
import {useRouter, useRoute} from "vue-router/dist/vue-router";
import {useStore} from "vuex";
import {AuthStudentActionTypes} from "../store/modules/auth_student/actions-type";
import {permissionHelper} from "../utils/permissionHelper";

export default defineComponent({
    name: 'StudentLayout',

    setup() {
        const store = useStore()
        const leftDrawerOpen = ref(false)
        const router = useRouter()
        const route = useRoute()

        const {checkClassMonitor} = permissionHelper()

        function toggleLeftDrawer(): void {
            leftDrawerOpen.value = !leftDrawerOpen.value
        }

        const checkActive = (routerName: string): boolean => {
            return route.name === routerName
        }

        const auth = store.getters["authStudent/getAuthUserStudent"]

        const redirectRouteName = (routeName: string): void => {
            router.push({name: routeName})
        }

        const logout = (): void => {
            store.dispatch(`authStudent/${AuthStudentActionTypes.LOGOUT_ACTION_STUDENT}`)
        }

        const title = computed(() => {
            return store.state.home.title
        })


        return {
            fabYoutube,
            checkActive,
            leftDrawerOpen,
            title,
            auth,
            toggleLeftDrawer,
            redirectRouteName,
            logout,
            checkClassMonitor,
            links1: [
                {icon: 'fa-solid fa-address-card', text: 'Hồ sơ sinh viên', routeName: 'HomeStudent'},
                {icon: 'fa-solid fa-users', text: 'Thông tin lớp học', routeName: 'StudentClass'},
            ],
            links2: [
                {icon: 'fa-solid fa-circle-info', text: 'Duyệt thông tin', routeName: 'RequestStudent'},
                {icon: 'fa-solid fa-flag', text: 'Phản ánh lớp học', routeName: 'ReportStudent',},
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
