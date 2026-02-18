<template>
    <div>
        <div>
            <SearchComponent
                ref="SearchComponent"
                @filter-updated="updateFilter"
                :keys="fields.filter(item=>{return item.key != 'actions'})"
                :isCondition=false
                :isAction=false
            ></SearchComponent>
        </div>
        <div class="row justify-content-center" :style="{ 'pointer-events': isLoading ? 'none' : 'auto' }">
            <div>
                <b-row class="justify-content-end">
                    <b-col sm="5" md="5" class="d-flex table_navigate justify-content-end pb-1">
                        <div v-if="can('createusers')" class="col-md-1">
                            <a class="btn btnAdd" href="javascript:void(0)" @click="showAddOrEditForm()">
                                {{ __(('variable.add')) }} </a>
                        </div>
                    </b-col>
                </b-row>
                <div class="parent_card_pagination">
                    <div class="card">
                        <div>
                            <b-table
                                :class="showTableLine ? 'table_line': 'table_grid'"
                                :stacked="!showTableLine"
                                :responsive="showTableLine"
                                ref="table"
                                :busy="isBusy"
                                :hover="showTableLine"
                                :items="getData"
                                :fields="fields"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"
                                :apiUrl="getRout('users.index',{locale : lang})"
                                :perPage="paginate.perPage"
                                :currentPage="paginate.currentPage"
                                :striped="showTableLine"
                                :small="showTableLine"
                                :bordered="showTableLine"
                                :filter="filter"
                                @row-clicked="can('editusers') ?  showAddOrEditForm($event, 'edit') : ''"
                            >
                                <template #table-busy>
                                    <div class="text-center text-danger my-2">
                                        <b-spinner class="align-middle"></b-spinner>
                                        <strong>{{ __(('variable.loaded')) }}</strong></div>
                                </template>
                                <template v-if="!isLoading" #cell(actions)="row">
                                    <a v-if="can('editusers')" class="btn btn-inverse-warning  p-1 a_position"
                                       href="javascript:void(0)" @click="showAddOrEditForm(row.item, 'edit')">
                                        <i class="bi bi-pencil title_hov_top" >
                                            <svg
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 fill="currentColor"
                                                 class="bi bi-pencil" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                            </svg>
                                        </i>
                                    </a>
                                    <button v-if="can('deleteusers')" class="btn btn-danger p-1 a_position"
                                            @click="destroyItem(row.item.id)">
                                        <i class="bi bi-trash title_hov_top" >
                                            <svg
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="red"
                                                 class="bi bi-trash" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd"
                                                      d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                            </svg>
                                        </i>
                                    </button>
                                </template>
                            </b-table>
                        </div>
                    </div>
                    <PaginationComponent ref="PaginationComponent" v-if="Object.keys(users).length > 0"
                                         :paginate="paginate"
                                         @loadAfterChangePage="loadAfterChangePage">
                    </PaginationComponent>
                </div>
                <b-modal
                    class="addModal"
                    v-model="show"
                    :header-text-variant="headerTextVariant"
                    :header-bg-variant="headerBgVariant"
                >
                    <template #modal-title>
                        <b-row>
                            <b-col sm="10" md="10" class="title_popup fs-15 lh-2">{{
                                    ((addOrEdit == 'add') ? __(('variable.add')) :
                                        __(('variable.edit'))) + " " + __(('variable.user'))
                                }}
                            </b-col>
                        </b-row>
                    </template>
                    <b-container fluid>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_center' v-b-tooltip.hover
                                   :data-title="__(('variable.name'))">
                                <b-form-input
                                    :class="[errors['name'] ? 'error-border': '', 'addFormInputs']"
                                    v-model="user.name"
                                    type="text"
                                    :placeholder="__(('variable.name'))"
                                ></b-form-input>
                                <small v-if="errors['name']" class="error-msg">{{ errors['name'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_center' v-b-tooltip.hover
                                   :data-title="__(('variable.email'))">
                                <b-form-input
                                    :class="[errors['email'] ? 'error-border': '', 'addFormInputs']"
                                    v-model="user.email"
                                    type="text"
                                    :placeholder="__(('variable.email'))"
                                ></b-form-input>
                                <small v-if="errors['email']" class="error-msg">{{ errors['email'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_center' v-b-tooltip.hover
                                   :data-title="__(('variable.password'))">
                                <b-form-input
                                    type="password"
                                    :class="[errors['password'] ? 'error-border': '', 'addFormInputs']"
                                    v-model="user.password"
                                    :placeholder="__(('variable.password'))"

                                ></b-form-input>
                                <small v-if="errors['password']" class="error-msg">{{ errors['password'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_center' v-b-tooltip.hover
                                   :data-title="__(('variable.repeat_password'))">
                                <b-form-input
                                    type="password"
                                    :class="[errors['password_confirmation'] ? 'error-border': '', 'addFormInputs']"
                                    v-model="user.password_confirmation"
                                    :placeholder="__(('variable.repeat_password'))"
                                ></b-form-input>
                                <small v-if="errors['password_confirmation']" class="error-msg">
                                    {{ errors['password_confirmation'] }}
                                </small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_center' v-b-tooltip.hover
                                   :data-title="__(('variable.role'))">
                                <multiselect class="mb-3"
                                             v-model="user.role_id"
                                             :options="gRoles.map(item => item = {value: item.id, text : item.name})"
                                             :multiple="true"
                                             label="text"
                                             track-by="value"
                                             :selectedLabel="__(('variable.selected'))"
                                             :selectLabel="__(('variable.select'))"
                                             :deselectLabel="__(('variable.remove'))"
                                             :placeholder="__(('variable.role'))"
                                             :showNoResults="false"
                                             :class="[errors['role_id'] ? 'error-border': '', '']"
                                >
                                </multiselect>
                                <small v-if="errors['role_id']" class="error-msg">{{ errors['role_id'] }}</small>
                            </b-col>
                        </b-row>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class='blockInput title_hov_center' v-b-tooltip.hover
                                   :data-title="__(('variable.permission'))">
                                <multiselect class="mb-3"
                                             v-model="user.permission_id"
                                             :options="permissions.map(item => item = {value: item.id, text : item.name})"
                                             :multiple="true"
                                             label="text"
                                             track-by="value"
                                             :selectedLabel="__(('variable.selected'))"
                                             :selectLabel="__(('variable.select'))"
                                             :deselectLabel="__(('variable.remove'))"
                                             :placeholder="__(('variable.permission'))"
                                             :showNoResults="false"
                                             :class="[errors['permission_id'] ? 'error-border': '', '']"
                                >
                                </multiselect>
                                <small v-if="errors['permission_id']" class="error-msg">{{ errors['permission_id'] }}
                                </small>
                            </b-col>
                        </b-row>
                    </b-container>

                    <template #modal-footer>
                        <div class="w-100 handle_button">
                            <b-button
                                :disabled="buttonDisabled"
                                size="sm"
                                class="float-right addButton"
                                @click="sendData(user_id)"
                            >
                                {{ ((addOrEdit == 'add') ? __(('variable.add')) : __(('variable.edit'))) }}
                            </b-button>
                        </div>
                    </template>
                </b-modal>

            </div>
        </div>
        <div v-if="isLoading" class="loading-overflow">
            <vue-loading class="loading" type="balls" color="#d9544e"
                         :size="{ width: '100px', height: '100px' }"></vue-loading>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';
import {VueLoading} from 'vue-loading-template'
import Multiselect from 'vue-multiselect';
import PaginationComponent from "./PaginationComponent";
import SearchComponent from "./SearchComponent";

export default {

    components: {
        Swal,
        Multiselect,
        PaginationComponent,
        SearchComponent,
        VueLoading,
    },

    name: "usersComponent",

    props: [
        'locale',
        'authuser',
        'roles',
    ],

    watch: {

        locale: function (newVal, oldVal) {
            this.lang = newVal;
        },

        authuser: function (newVal, oldVal) {
            this.authUser = newVal;
        },

        roles: function (newVal, oldVal) {
            this.role = newVal;
        },

        filter: {
            handler(newVal, oldVal) {
                this.paginate.currentPage = 1;
            },
            deep: true,
        },
    },

    data() {
        return {
            //default
            lang: this.locale,
            authUser: this.authuser,
            role: this.roles,
            paginate: {
                perPage: 10,
                currentPage: 1,
                total: '',
                currentPageInput: '',
                lastPage: '',
            },
            errors: {},
            //default

            //BTable
            showTableLine: true,
            fields: [
                {
                    key: 'id',
                    label: this.__('variable.id'),
                    sortable: true
                },
                {
                    key: 'name',
                    label: this.__('variable.name'),
                    sortable: true, formatter: (val, key, row) => {
                        return val ? val : '';
                    }
                },
                {
                    key: 'email',
                    label: this.__('variable.email'),
                    sortable: true
                },
                {
                    key: 'actions',
                    label: this.__('variable.action'),
                    sortable: false,
                    formatter: (val, key, row) => {
                        return '';
                    }
                }
            ],
            isBusy: false,
            total: '',
            sortBy: 'id',
            sortDesc: false,
            filter: [
                {
                    text: '',
                    key: '',
                }
            ],
            //BTable

            //Modal
            show: false,
            headerBgVariant: 'custom',
            headerTextVariant: 'white',
            //Modal

            //Data
            gRoles: [],
            permissions: [],
            users: [],
            user: {
                name: '',
                role_id: [],
                permission_id: [],
                email: '',
                password: '',
                password_confirmation: '',
            },
            user_id: '',
            addOrEdit: 'add',
            buttonDisabled: false,
            isLoading: false,
            //Data


        }
    },

    mounted() {
    },

    created() {
    },

    methods: {

        updateFilter(newFilter) {
            this.filter = newFilter;
        },

        toggleTable() {
            this.showTableLine = !this.showTableLine;
        },

        loadAfterChangePage(currentPage, perPage) {
            if (currentPage) {
                this.paginate.currentPage = currentPage;
            }
            if (perPage) {
                this.paginate.perPage = perPage;

            }
        },

        getData(data) {
            let self = this;
            return axios.get(route('users.index', {locale: self.lang}) + `?page=${data.currentPage}`, {params: data})
                .then((resp) => {
                    if (resp && resp.data && resp.data.data) {
                        self.users = resp.data.data;
                        self.paginate.rows = resp.data.total;
                        self.paginate.total = resp.data.total;
                        self.paginate.currentPage = resp.data.current_page;
                        self.paginate.currentPageInput = self.paginate.currentPage;
                        self.paginate.lastPage = resp.data.last_page;
                        self.isBusy = false;
                        return self.users;
                    }
                })

                .catch((err) => {
                    self.showCatchError(err);
                });
        },

        sendData(id) {
            if (this.buttonDisabled) {
                return;
            }
            this.buttonDisabled = true;
            let self = this;
            self.errors = {};
            let data = Object.assign({}, self.user);
            let arrRole = [];
            self.user.role_id.forEach(row => {
                arrRole.push(row.value)
            });
            data.role_id = arrRole;
            let arrPerm = [];
            self.user.permission_id.forEach(row => {
                arrPerm.push(row.value)
            });
            data.permission_id = arrPerm;
            if (self.isSuperAdmin()) {
                data.organization_id = self.user.organization_id ? self.user.organization_id.value : '';
            }
            data.branch_id = self.user.branch_id ? self.user.branch_id.value : '';
            if (id) {
                data.id = id;
            }
            axios.post(route('users.store', {locale: self.lang}), data)
                .then((data) => {
                    if (data && data.data && data.data.isSuccess) {
                        self.show = false;
                        self.resetData();
                        self.$refs.table.refresh();
                    }
                    this.buttonDisabled = false;
                })
                .catch((err) => {
                    if (err && err.response && err.response.data) {
                        let errors = err.response.data;
                        for (let i in errors) {
                            Vue.set(self.errors, i, errors[i][0]);
                        }
                    }
                    this.buttonDisabled = false;

                })
        },

        showAddOrEditForm(obj = '', addOrEdit = 'add') {

            let self = this;
            self.errors = {};
            self.getRoles();
            self.getPermissions();
            self.addOrEdit = addOrEdit;
            self.user_id = obj ? obj.id : "";
            self.user.name = obj ? obj.name : "";
            self.user.email = obj ? obj.email : "";
            self.user.role_id = [];
            if (obj && obj.roles && obj.roles.length) {
                obj.roles.forEach(rol => {
                    self.user.role_id.push({value: rol.id, text: rol.name})
                });
            }
            self.user.permission_id = [];
            if (obj && obj.permissions && obj.permissions.length) {
                obj.permissions.forEach(perm => {
                    this.user.permission_id.push({value: perm.id, text: perm.name})
                });
            }

            self.show = true;
        },

        resetData() {
            let self = this;
            self.errors = {};
            self.users = [];
            self.user = {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                role_id: [],
                permission_id: [],
            };
            self.user_id = '';
            self.addOrEdit = 'add';
            self.buttonDisabled = false;
            self.gRoles = [];
            self.permissions = [];
            self.show = false;
        },

        getRoles() {
            let self = this;
            axios.get(route('getRoles', {locale: self.lang}))
                .then((resp) => {
                    if (resp && resp.data) {
                        self.gRoles = resp.data;
                    }
                })
                .catch((err) => {
                    self.showCatchError(err);
                });
        },

        getPermissions() {
            let self = this;
            axios.get(route('getPermissions', {locale: self.lang}))
                .then((resp) => {
                    if (resp && resp.data) {
                        self.permissions = resp.data;
                    }
                })
                .catch((err) => {
                    self.showCatchError(err);
                });
        },

        isSuperAdmin() {
            let self = this;
            let resp = false;
            self.role.forEach(row => {
                if (row.slug == "superadmin") {
                    resp = true;
                }
            });
            return resp;
        },

        destroyItem(id) {
            let self = this;
            Swal.fire({
                title: this.__('variable.sure'),
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: this.__('variable.yes'),
                cancelButtonText: this.__('variable.no'),
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(route('users.destroy', {locale: self.lang, user: id}))
                        .then((data) => {
                            if (data && data.data && data.data.isSuccess) {
                                if (this.paginate.perPage == 1) {
                                    this.paginate.currentPage--;
                                }
                                self.getData({
                                    currentPage: ((this.paginate.currentPage > 1) ? this.paginate.currentPage : 1),
                                    perPage: this.paginate.perPage, filter: this.filter
                                }).then(function () {
                                    self.show = false;
                                    self.resetData();
                                    self.$refs.table.refresh();

                                });
                                Swal.fire({
                                    title: this.__('variable.success'),
                                    icon: 'success',
                                    timer: 500,
                                    showConfirmButton: false // Hide the "OK" button
                                });
                            } else if (data && data.data && !data.data.isSuccess && data.data.message) {
                                Swal.fire({
                                    title: data.data.message,
                                    confirmButtonText: this.__('variable.ok')
                                });
                            }

                        })
                        .catch((err) => {
                            self.showCatchError(err);
                        })
                }
            })
        },

        getRout(param) {
            return getRout(param)
        },

        showCatchError(err) {
            if (err.response && err.response.data) {
                Swal.fire({
                    icon: 'error',
                    title: `${err.response.data.message}`,
                    confirmButtonText: this.__('variable.ok')
                });
            }
        },

    },
}
</script>


