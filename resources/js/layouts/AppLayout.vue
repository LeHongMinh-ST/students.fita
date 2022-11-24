<template>
    <q-layout view="lHh Lpr lFf" class="main bg-grey-2">
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
                            <img src="https://cdn.quasar.dev/img/avatar4.jpg">
                        </q-avatar>
                        <q-menu>
                            <div class="row no-wrap q-pa-md">
                                <div class="column items-center">
                                    <q-avatar size="72px">
                                        <img src="https://cdn.quasar.dev/img/avatar4.jpg">
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
                        Quản lý chung
                    </q-item-label>
                    <q-item v-for="link in links1" :key="link.text" v-ripple clickable
                            @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon color="grey" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-separator class="q-my-md"/>

                    <q-item-label header class="text-weight-bold">
                        Quản lý thông tin
                    </q-item-label>
                    <q-item v-for="link in links2" :key="link.text" v-ripple clickable
                            @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon color="grey" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}</q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-separator class="q-my-md"/>
                    <q-item-label header class="text-weight-bold">
                        Quản trị hệ thống
                    </q-item-label>
                    <q-item v-for="link in linksSystem" :key="link.text" v-ripple clickable
                            @click="redirectRouteName(link.routeName)">
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
                    <router-view/>
                </div>
            </div>
        </q-page-container>
    </q-layout>
</template>

<script lang="ts">
import {computed, defineComponent, ref} from 'vue'
import {fabYoutube} from '@quasar/extras/fontawesome-v6'
import {useRouter} from "vue-router/dist/vue-router";
import {useStore} from "vuex";
import {AuthActionTypes} from "../store/modules/auth/actions-type";

export default defineComponent({
    name: 'AppLayout',

    setup() {
        const store = useStore()
        const leftDrawerOpen = ref(false)
        const router = useRouter()

        const auth = store.getters["auth/getAuthUser"]

        const logout = (): void => {
            store.dispatch(`auth/${AuthActionTypes.LOGOUT_ACTION}`)
        }

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
            auth,
            leftDrawerOpen,
            title,
            logout,
            toggleLeftDrawer,
            redirectRouteName,

            links1: [
                {icon: 'fa-solid fa-home', text: 'Bảng điều khiển', routeName: 'Home'},
                {icon: 'fa-solid fa-building-user', text: 'Quản lý bộ môn', routeName: 'Department'},
                {icon: 'fa-solid fa-users', text: 'Quản lý lớp học', routeName: 'Classes'},
            ],

            links2: [
                {icon: 'fa-solid fa-address-card', text: 'Quản lý sinh viên', routeName: 'StudentIndex'},
                {icon: 'fa-solid fa-rectangle-list', text: 'Danh sách xét duyệt', routeName: 'Student'},
                {icon: 'fa-solid fa-flag', text: 'Báo cáo sinh viên', routeName: 'Student'},

            ],
            linksSystem: [
                {icon: 'fa-solid fa-user', text: 'Người dùng', routeName: 'User'},
                {icon: 'fa-solid fa-user-shield', text: 'Nhóm và phân quyền', routeName: 'Role'},
            ],
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
