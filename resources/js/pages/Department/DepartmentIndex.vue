<template>
  <div class="role-wrapper">
    <div class="popup">
    <CreateOrUpdateDepartment ref="popupRef" :getListDepartment="getListDepartment" :department="departmentCurrent"/>
    <DeleteDepartment ref="popupDeleteRef" :getListDepartment="getListDepartment" :resetListIdDelete="resetListIdDelete"/>
    </div>
    <q-breadcrumbs>
      <q-breadcrumbs-el
        label="Bảng điều khiển"
        icon="home"
        :to="{ name: 'Home' }"
      />
      <q-breadcrumbs-el label="Bộ môn" />
    </q-breadcrumbs>
    <q-slide-transition>
      <q-card class="filter-wrapper" v-if="isFilter">
        <div class="filter-wrapper-content">
          <div class="filter-header">
            <div class="filter-header-text">Lọc dữ liệu</div>
            <div class="filter-header-button">
              <q-btn
                round
                icon="fa-solid fa-xmark"
                @click="closeFilter"
                size="sm"
              />
            </div>
          </div>
        </div>
        <q-card-section>
          <q-btn color="primary" no-caps outline class="q-mr-sm">
            Thêm bộ lọc
          </q-btn>
          <q-btn color="primary" no-caps class="q-mr-sm"> Áp dụng </q-btn>
        </q-card-section>
      </q-card>
    </q-slide-transition>
    <q-card class="table-wrapper">
      <q-card-section class="table-wrapper-title">
        <div class="table-wrapper-filter">
          <q-slide-transition>
            <q-btn
              v-if="checkboxArray.length > 0"
              color="primary"
              no-caps
              outline
              class="q-mr-sm"
            >
              Hành động
              <q-icon
                name="fa-solid fa-caret-down"
                class="q-ml-sm"
                size="xs"
              ></q-icon>

              <q-menu>
                <q-list style="min-width: 100px">
                  <q-item
                    clickable
                    v-close-popup
                    @click="openDialogDeleteSelect(checkboxArray)"
                  >
                    <q-item-section>
                      <span
                        ><q-icon
                          name="fa-solid fa-trash"
                          class="q-mr-sm"
                          size="xs"
                        ></q-icon
                        >Xoá ({{ checkboxArray.length }} bản ghi)</span
                      >
                    </q-item-section>
                  </q-item>
                </q-list>
              </q-menu>
            </q-btn>
          </q-slide-transition>
          <q-btn class="q-mr-sm" no-caps color="primary" @click="toggleFilter">
            <q-icon
              name="fa-solid fa-filter"
              class="q-mr-sm"
              size="xs"
            ></q-icon>
            Lọc dữ liệu
          </q-btn>

          <div class="table-wrapper-search">
            <q-input
              bottom-slots
              v-model="search"
              label="Nhập từ khóa để tìm kiếm"
              outlined
              dense
            >
              <template v-slot:append>
                <q-icon
                  v-if="text !== ''"
                  name="close"
                  @click="text = ''"
                  class="cursor-pointer"
                />
                <q-icon name="search" />
              </template>
            </q-input>
          </div>
        </div>
        <div class="table-wrapper-action">
          <q-btn
            no-caps
            @click="onClickCreateBtn"
            color="secondary"
            class="q-mr-sm"
          >
            <q-icon name="fa-solid fa-plus" class="q-mr-sm" size="xs"></q-icon>
            Tạo mới
          </q-btn>
          <q-btn no-caps @click="getListDepartment" color="secondary" class="q-mr-sm">
            <q-icon
              name="fa-solid fa-refresh"
              class="q-mr-sm"
              size="xs"
            ></q-icon>
            Tải lại
          </q-btn>
        </div>
      </q-card-section>
      <q-separator inset />
      <q-card-section>
        <q-markup-table class="role-table">
          <thead>
            <tr>
              <th class="text-center" width="4%">
                <q-checkbox v-model="checkboxAll" />
              </th>
              <th class="text-center" width="5%">STT</th>
              <th class="text-left">Mã bộ môn</th>
              <th class="text-left">Tên bộ môn</th>
              <th class="text-center">Ngày tạo</th>
              <th class="text-left">Được tạo bởi</th>
              <th class="text-center">Tác vụ</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="departments.length > 0">
              <tr v-for="(department, index) in departments" :key="index">
                <td class="text-center">
                  <q-checkbox
                    v-model="checkboxArray"
                    :val="getValueLodash(department, 'id', 0)"
                  />
                </td>
                <td class="text-center">
                  {{ index + +1 + +page.perPage * (page.currentPage - 1) }}
                </td>
                <td class="text-left">
                  <span
                    @click="
                      redirectRouter('DepartmentUpdate', { id: department.id })
                    "
                    class="text-bold cursor-pointer text-link"
                  >
                    {{ getValueLodash(department, "department_code", "") }}
                  </span>
                </td>
                <td class="text-left">
                  {{ getValueLodash(department, "name", "") }}
                </td>
                <td class="text-center">
                  {{
                    handleFormatDate(
                      getValueLodash(department, "created_at", "")
                    )
                  }}
                </td>
                <td class="text-left">
                  {{ getValueLodash(department, "create_by.full_name", "") }}
                </td>
                <td class="text-center">
                  <div class="inline cursor-pointer">
                    <q-icon name="menu" size="sm"></q-icon>
                    <q-menu touch-position>
                      <q-list style="min-width: 100px">
                        <q-item
                          clickable
                          v-close-popup
                          @click="
                          openDialogUpdate(
                            department || null
                          )
                          "
                        >
                          <q-item-section>
                            <span
                              ><q-icon
                                name="fa-solid fa-pen-to-square"
                                class="q-mr-sm"
                                size="xs"
                              ></q-icon
                              >Chỉnh sửa</span
                            >
                          </q-item-section>
                        </q-item>
                        <q-item
                          clickable
                          v-close-popup
                          @click="
                            openDialogDelete(
                              getValueLodash(department, 'id', 0)
                            )
                          "
                        >
                          <span
                            ><q-icon
                              name="fa-solid fa-trash"
                              class="q-mr-sm"
                              size="xs"
                            ></q-icon
                            >Xoá</span
                          >
                        </q-item>
                      </q-list>
                    </q-menu>
                  </div>
                </td>
              </tr>
            </template>
            <template v-else>
              <tr>
                <td colspan="7" class="text-center">
                  <img class="imgEmpty" src="/images/empty.png" alt="" />
                </td>
              </tr>
            </template>
          </tbody>
          <q-inner-loading
            :showing="loadingDepartments"
            label-class="text-teal"
            label-style="font-size: 1.1em"
          />
        </q-markup-table>
        <div v-if="page.total > 1" class="q-pt-lg flex flex-center">
          <q-pagination
            v-model="page.currentPage"
            :max="page.total"
            direction-links
            :max-pages="10"
          />
        </div>
      </q-card-section>
    </q-card>

  </div>
</template>

<style lang="css" src="./index.scss"></style>
<script lang="ts" src="./index.ts">
</script>

