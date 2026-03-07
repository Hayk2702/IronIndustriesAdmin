<template>
    <div>
        <SearchComponent
            ref="SearchComponent"
            @filter-updated="updateFilter"
            :keys="fields.filter(item => item.key !== 'actions')"
            :isCondition="false"
            :isAction="false"
        />

        <div class="row justify-content-center" :style="{ 'pointer-events': isLoading ? 'none' : 'auto' }">
            <div class="w-100">
                <div class="parent_card_pagination">
                    <div class="card">
                        <b-table
                            :class="showTableLine ? 'table_line' : 'table_grid'"
                            :stacked="!showTableLine"
                            :responsive="showTableLine"
                            ref="table"
                            :busy="isBusy"
                            :hover="showTableLine"
                            :items="getData"
                            :fields="fields"
                            :sort-by.sync="sortBy"
                            :sort-desc.sync="sortDesc"
                            :apiUrl="getRout('preorders.index',{locale : lang})"
                            :perPage="paginate.perPage"
                            :currentPage="paginate.currentPage"
                            :striped="showTableLine"
                            :small="showTableLine"
                            :bordered="showTableLine"
                            :filter="filter"
                            @row-clicked="openDetail"
                        >
                            <template #table-busy>
                                <div class="text-center text-danger my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>{{ __(('variable.loaded')) }}</strong>
                                </div>
                            </template>

                            <template #cell(is_viewed)="row">
                                <span :class="row.item.is_viewed ? 'status-viewed' : 'status-new'">
                                    {{ row.item.is_viewed ? 'Viewed' : 'New' }}
                                </span>
                            </template>

                            <template #cell(calculating_information)="row">
                                <div class="cut-text">
                                    {{ row.item.calculating_information }}
                                </div>
                            </template>

                            <template #cell(comment)="row">
                                <div class="cut-text">
                                    {{ row.item.comment || '—' }}
                                </div>
                            </template>

                            <template #cell(actions)="row">
                                <a
                                    class="btn btn-inverse-warning p-1 a_position"
                                    href="javascript:void(0)"
                                    @click.stop="openDetail(row.item)"
                                >
                                    {{ __('variable.edit') || 'View' }}
                                </a>

                                <button
                                    v-if="!row.item.is_viewed && can('editpreorders')"
                                    class="btn btn-info p-1 a_position"
                                    @click.stop="markViewed(row.item.id)"
                                >
                                    Viewed
                                </button>

                                <button
                                    v-if="can('deletepreorders')"
                                    class="btn btn-danger p-1 a_position"
                                    @click.stop="destroyItem(row.item.id)"
                                >
                                    Delete
                                </button>
                            </template>
                        </b-table>
                    </div>

                    <PaginationComponent
                        v-if="Object.keys(preorders).length > 0"
                        :paginate="paginate"
                        @loadAfterChangePage="loadAfterChangePage"
                    />
                </div>

                <b-modal
                    class="addModal"
                    v-model="show"
                    :header-text-variant="headerTextVariant"
                    :header-bg-variant="headerBgVariant"
                    size="lg"
                >
                    <template #modal-title>
                        <b-row>
                            <b-col sm="10" md="10" class="title_popup fs-15 lh-2">
                                Preorder details
                            </b-col>
                        </b-row>
                    </template>

                    <b-container fluid v-if="detailItem">
                        <b-row class="mb-2">
                            <b-col><strong>ID:</strong> {{ detailItem.id }}</b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col><strong>Full name:</strong> {{ detailItem.full_name }}</b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col><strong>Phone number:</strong> {{ detailItem.phone_number }}</b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col><strong>Email:</strong> {{ detailItem.email || '—' }}</b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col>
                                <strong>Calculating information:</strong>
                                <div class="detail-box">{{ detailItem.calculating_information }}</div>
                            </b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col>
                                <strong>Comment:</strong>
                                <div class="detail-box">{{ detailItem.comment || '—' }}</div>
                            </b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col>
                                <strong>Status:</strong>
                                <span :class="detailItem.is_viewed ? 'status-viewed' : 'status-new'">
                                    {{ detailItem.is_viewed ? 'Viewed' : 'New' }}
                                </span>
                            </b-col>
                        </b-row>

                        <b-row class="mb-2">
                            <b-col><strong>Created at:</strong> {{ detailItem.created_at }}</b-col>
                        </b-row>
                    </b-container>

                    <template #modal-footer>
                        <div class="w-100 handle_button">
                            <b-button
                                v-if="detailItem && !detailItem.is_viewed && can('editpreorders')"
                                size="sm"
                                class="float-right addButton mr-2"
                                @click="markViewedFromModal"
                            >
                                Mark viewed
                            </b-button>
                        </div>
                    </template>
                </b-modal>
            </div>
        </div>

        <div v-if="isLoading" class="loading-overflow">
            <vue-loading class="loading" type="balls" color="#d9544e" :size="{ width: '100px', height: '100px' }"></vue-loading>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";
import { VueLoading } from "vue-loading-template";
import PaginationComponent from "./PaginationComponent";
import SearchComponent from "./SearchComponent";

export default {
    name: "PreordersComponent",
    components: {
        Swal,
        VueLoading,
        PaginationComponent,
        SearchComponent,
    },

    props: ["locale"],

    data() {
        return {
            lang: this.locale,

            paginate: {
                perPage: 10,
                currentPage: 1,
                total: "",
                currentPageInput: "",
                lastPage: "",
            },

            fields: [
                { key: "id", label: this.__("variable.id"), sortable: true },
                { key: "full_name", label: "Full name", sortable: true },
                { key: "phone_number", label: "Phone number", sortable: true },
                { key: "email", label: "Email", sortable: true },
                { key: "calculating_information", label: "Calculating information", sortable: false },
                { key: "comment", label: "Comment", sortable: false },
                { key: "is_viewed", label: "Status", sortable: true },
                { key: "created_at", label: "Created at", sortable: true },
                { key: "actions", label: this.__("variable.action"), sortable: false, formatter: () => "" },
            ],

            isBusy: false,
            sortBy: "id",
            sortDesc: true,
            filter: [{ text: "", key: "" }],

            preorders: [],
            showTableLine: true,

            show: false,
            detailItem: null,
            headerBgVariant: "custom",
            headerTextVariant: "white",

            isLoading: false,
        };
    },

    watch: {
        locale(n) {
            this.lang = n;
        },
        filter: {
            handler() {
                this.paginate.currentPage = 1;
            },
            deep: true,
        },
    },

    methods: {
        updateFilter(newFilter) {
            this.filter = newFilter;
        },

        loadAfterChangePage(currentPage, perPage) {
            if (currentPage) this.paginate.currentPage = currentPage;
            if (perPage) this.paginate.perPage = perPage;
        },

        getData(data) {
            this.isBusy = true;
            return axios
                .get(route("preorders.index", { locale: this.lang }) + `?page=${data.currentPage}`, { params: data })
                .then((resp) => {
                    if (resp && resp.data && resp.data.data) {
                        this.preorders = resp.data.data;
                        this.paginate.total = resp.data.total;
                        this.paginate.currentPage = resp.data.current_page;
                        this.paginate.currentPageInput = this.paginate.currentPage;
                        this.paginate.lastPage = resp.data.last_page;
                        this.isBusy = false;
                        return this.preorders;
                    }
                })
                .catch((err) => {
                    this.isBusy = false;
                    this.showCatchError(err);
                });
        },

        openDetail(item) {
            this.isLoading = true;

            axios
                .get(route("preorders.detail", { locale: this.lang, id: item.id }))
                .then((resp) => {
                    if (resp && resp.data && resp.data.data) {
                        this.detailItem = resp.data.data;
                        this.show = true;
                        this.$refs.table.refresh();
                    }

                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.showCatchError(err);
                });
        },

        markViewed(id) {
            axios
                .post(route("preorders.markViewed", { locale: this.lang, id }))
                .then((resp) => {
                    if (resp && resp.data && resp.data.isSuccess) {
                        this.$refs.table.refresh();

                        if (this.detailItem && this.detailItem.id === id) {
                            this.detailItem.is_viewed = true;
                        }

                        Swal.fire({
                            title: resp.data.message || this.__("variable.updated_successfully"),
                            icon: "success",
                            timer: 700,
                            showConfirmButton: false,
                        });
                    }
                })
                .catch((err) => this.showCatchError(err));
        },

        markViewedFromModal() {
            if (!this.detailItem) return;
            this.markViewed(this.detailItem.id);
        },

        destroyItem(id) {
            Swal.fire({
                title: this.__("variable.sure"),
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: this.__("variable.yes"),
                cancelButtonText: this.__("variable.no"),
            }).then((result) => {
                if (!result.isConfirmed) return;

                axios
                    .delete(route("preorders.destroy", { locale: this.lang, preorder : id }))
                    .then((resp) => {
                        if (resp && resp.data && resp.data.isSuccess) {
                            this.$refs.table.refresh();
                            this.show = false;
                            Swal.fire({
                                title: this.__("variable.success"),
                                icon: "success",
                                timer: 600,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch((err) => this.showCatchError(err));
            });
        },

        getRout(param) {
            return getRout(param);
        },

        showCatchError(err) {
            if (err.response && err.response.data) {
                Swal.fire({
                    icon: "error",
                    title: `${err.response.data.message}`,
                    confirmButtonText: this.__("variable.ok"),
                });
            }
        },
    },
};
</script>

<style scoped>
.detail-box {
    white-space: pre-wrap;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px;
    background: #fafafa;
    margin-top: 5px;
}

.cut-text {
    max-width: 240px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.status-viewed {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    background: #d1e7dd;
    color: #0f5132;
    font-size: 12px;
}

.status-new {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 6px;
    background: #fff3cd;
    color: #664d03;
    font-size: 12px;
}
</style>
