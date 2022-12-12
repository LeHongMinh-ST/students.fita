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
                            <img :src="auth.thumbnail ? auth.thumbnail_url : 'https://cdn.quasar.dev/img/avatar4.jpg'">
                        </q-avatar>
                        <q-menu>
                            <div class="row no-wrap q-pa-md">

                                <div class="column items-center">

                                    <q-avatar size="72px">
                                        <img :src="auth.thumbnail ? auth.thumbnail_url : 'https://cdn.quasar.dev/img/avatar4.jpg'">
                                    </q-avatar>

                                    <div class="text-subtitle1 q-mt-md q-mb-xs">{{ auth.full_name }}</div>
                                    <q-btn
                                        color="primary"
                                        label="Tài khoản"
                                        push
                                        class="q-mb-sm"
                                        size="sm"
                                        v-close-popup
                                        @click="redirectRouteName('Profile')"
                                    />

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
                <q-separator class="q-my-md"
                             v-if="checkPermission('department-index')||checkPermission('class-index') ||checkPermission('dashboard-index')"/>
                <q-list padding>
                    <q-item-label header class="text-weight-bold"
                                  v-if="checkPermission('department-index')||checkPermission('class-index') ||checkPermission('dashboard-index')">
                        Quản lý chung
                    </q-item-label>
                    <q-item v-for="link in links1" :key="link.text" v-ripple clickable
                            :active="checkActive(link.routeName)" active-class="bg-grey text-white"
                            @click="redirectRouteName(link.routeName)" v-show="checkPermission(link.permission)">
                        <q-item-section avatar>
                            <q-icon :color="checkActive(link.routeName) ? 'white' : 'grey'" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}</q-item-label>
                        </q-item-section>
                    </q-item>

                    <q-separator class="q-my-md"
                                 v-if="checkPermission('student-index')||checkPermission('report-index') "/>

                    <q-item-label header class="text-weight-bold"
                                  v-if="checkPermission('student-index')||checkPermission('report-index') ">
                        Quản lý thông tin
                    </q-item-label>
                    <q-item v-for="link in links2" :key="link.text" v-ripple clickable
                            :active="checkActive(link.routeName)" active-class="bg-grey text-white"
                            v-show="checkPermission(link.permission)" @click="redirectRouteName(link.routeName)">
                        <q-item-section avatar>
                            <q-icon :color="checkActive(link.routeName) ? 'white' : 'grey'" :name="link.icon"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ link.text }}
                              <q-badge v-if="link.badge" rounded color="red" :label="link.badge" />
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-separator v-if="checkPermission('user-index')||checkPermission('role-index') " class="q-my-md"/>
                    <q-item-label header class="text-weight-bold"
                                  v-if="checkPermission('user-index')||checkPermission('role-index') ">
                        Quản trị hệ thống
                    </q-item-label>
                    <q-item v-for="link in linksSystem" :key="link.text" v-ripple clickable
                            :active="checkActive(link.routeName)" active-class="bg-grey text-white"
                            @click="redirectRouteName(link.routeName)" v-show="checkPermission(link.permission)">
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
                    <router-view/>
                </div>
            </div>
        </q-page-container>
    </q-layout>
</template>

<script lang="ts">
import {computed, defineComponent, onMounted, ref} from 'vue'
import {fabYoutube} from '@quasar/extras/fontawesome-v6'
import {useRoute, useRouter} from "vue-router/dist/vue-router";
import {useStore} from "vuex";
import {AuthActionTypes} from "../store/modules/auth/actions-type";
import {permissionHelper} from "../utils/permissionHelper";
import api from "../api";
import {HomeMutationTypes} from "../store/modules/home/mutation-types";
import useCount from "../uses/useCount";

export default defineComponent({
    name: 'AppLayout',

    setup() {
        const store = useStore()
        const leftDrawerOpen = ref(false)
        const router = useRouter()
        const route = useRoute()
        const {getRequestCount, getReportCount} = useCount()

        const checkActive = (routerName: string): boolean => {
            return route.name === routerName
        }

        const {checkPermission} = permissionHelper()

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

        const links1 = ref<any>( [
          {icon: 'fa-solid fa-home', text: 'Bảng điều khiển', routeName: 'Home', permission: 'dashboard-index'},
          {
            icon: 'fa-solid fa-building-user',
            text: 'Quản lý bộ môn',
            routeName: 'Department',
            permission: 'department-index'
          },
          {icon: 'fa-solid fa-users', text: 'Quản lý lớp học', routeName: 'Classes', permission: 'class-index'},
        ])

        const links2 = ref([
          {
            icon: 'fa-solid fa-address-card',
            text: 'Quản lý sinh viên',
            routeName: 'StudentIndex',
            permission: 'student-index'
          },
          {
            icon: 'fa-solid fa-rectangle-list',
            text: 'Yêu cầu xét duyệt',
            routeName: 'ReviewListIndex',
            permission: 'student-update',
            badge: store.state.home.countRequest
          },
          {
            icon: 'fa-solid fa-flag',
            text: 'Phản ánh sinh viên',
            routeName: 'ReportStudent',
            permission: 'report-index',
            badge: store.state.home.countReport
          },
        ])

        const linksSystem = ref([
          {icon: 'fa-solid fa-user', text: 'Người dùng', routeName: 'User', permission: 'user-index'},
          {
            icon: 'fa-solid fa-user-shield',
            text: 'Nhóm và phân quyền',
            routeName: 'Role',
            permission: 'role-index'
          },
        ])

        onMounted(()=> {
          getReportCount()
          getRequestCount()
        })

        return {
            fabYoutube,
            auth,
            leftDrawerOpen,
            title,
            logout,
            toggleLeftDrawer,
            redirectRouteName,
            checkPermission,
            checkActive,
            links1,
            links2 ,
            linksSystem
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
