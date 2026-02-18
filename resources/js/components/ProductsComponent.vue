<template>
    <div class="page">
        <SearchComponent
            ref="SearchComponent"
            @filter-updated="updateFilter"
            :keys="fields.filter(item => item.key !== 'actions')"
            :isCondition="false"
            :isAction="false"
        />

        <div class="row justify-content-center" :style="{ 'pointer-events': isLoading ? 'none' : 'auto' }">
            <div>
                <b-row class="justify-content-end">
                    <b-col sm="5" md="5" class="d-flex table_navigate justify-content-end pb-1">
                        <b-button size="sm" class="addButton toggleTableBtm" @click="toggleTable">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-border-all" viewBox="0 0 16 16">
                                <path d="M0 0h16v16H0V0zm1 1v6.5h6.5V1H1zm7.5 0v6.5H15V1H8.5zM15 8.5H8.5V15H15V8.5zM7.5 15V8.5H1V15h6.5z"/>
                            </svg>
                        </b-button>

                        <div v-if="can('createproducts')" class="col-md-1">
                            <a class="btn btnAdd" href="javascript:void(0)" @click="showAddOrEditForm()">
                                {{ __(('variable.add')) }}
                            </a>
                        </div>
                    </b-col>
                </b-row>

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
                            :apiUrl="getRout('products.index',{locale : lang})"
                            :perPage="paginate.perPage"
                            :currentPage="paginate.currentPage"
                            :striped="showTableLine"
                            :small="showTableLine"
                            :bordered="showTableLine"
                            :filter="filter"
                            @row-clicked="can('editproducts') ? showAddOrEditForm($event, 'edit') : ''"
                        >
                            <template #table-busy>
                                <div class="text-center text-danger my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>{{ __(('variable.loaded')) }}</strong>
                                </div>
                            </template>

                            <template #cell(service)="row">
                                <span v-if="row.item.service && row.item.service.title">
                                    {{ row.item.service.title }}
                                </span>
                                <span v-else class="muted">—</span>
                            </template>

                            <template #cell(images)="row">
                                <div class="thumbs" v-if="row.item.images && row.item.images.length">
                                    <img v-for="img in row.item.images.slice(0,3)"
                                         :key="img.id"
                                         :src="img.image_url"
                                         class="thumb"
                                         alt="img" />
                                    <span v-if="row.item.images.length > 3" class="more">
                                        +{{ row.item.images.length - 3 }}
                                    </span>
                                </div>
                                <span v-else class="muted">—</span>
                            </template>

                            <template v-if="!isLoading" #cell(actions)="row">
                                <a v-if="can('editproducts')" class="btn btn-inverse-warning p-1 a_position"
                                   href="javascript:void(0)" @click.stop="showAddOrEditForm(row.item, 'edit')">
                                    <i class="bi bi-pencil title_hov_top" :data-title="__(('variable.edit'))">
                                        <svg v-b-tooltip.hover
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             fill="currentColor"
                                             class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </i>
                                </a>

                                <button v-if="can('deleteproducts')" class="btn btn-danger p-1 a_position"
                                        @click.stop="destroyItem(row.item.id)">
                                    <i class="bi bi-trash title_hov_top" :data-title="__(('variable.remove'))">
                                        <svg v-b-tooltip.hover
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

                    <PaginationComponent
                        v-if="Object.keys(products).length > 0"
                        :paginate="paginate"
                        @loadAfterChangePage="loadAfterChangePage"
                    />
                </div>

                <!-- Modal -->
                <b-modal class="addModal" v-model="show" :header-text-variant="headerTextVariant" :header-bg-variant="headerBgVariant">
                    <template #modal-title>
                        <b-row>
                            <b-col sm="10" md="10" class="title_popup fs-15 lh-2">
                                {{ (addOrEdit === 'add' ? __(('variable.add')) : __(('variable.edit'))) + " " + __(('variable.product')) }}
                            </b-col>
                        </b-row>
                    </template>

                    <b-container fluid>

                        <!-- title -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">{{ __(('variable.title')) }}</label>
                                <b-form-input
                                    :class="[errors.title ? 'error-border' : '', 'addFormInputs']"
                                    v-model="form.title"
                                    type="text"
                                    :placeholder="__(('variable.title'))"
                                />
                                <small v-if="errors.title" class="error-msg">{{ errors.title }}</small>
                            </b-col>
                        </b-row>

                        <!-- service -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">{{ __(('variable.service')) }}</label>
                                <multiselect
                                    v-model="form.service_id"
                                    :options="serviceOptions"
                                    :multiple="false"
                                    label="text"
                                    track-by="value"
                                    :selectedLabel="__(('variable.selected'))"
                                    :selectLabel="__(('variable.select'))"
                                    :deselectLabel="__(('variable.remove'))"
                                    :placeholder="__(('variable.service'))"
                                    :showNoResults="false"
                                    :class="[errors['service_id'] ? 'error-border': '', 'x']"
                                >
                                </multiselect>

                                <small v-if="errors.service_id" class="error-msg">{{ errors.service_id }}</small>
                            </b-col>
                        </b-row>

                        <!-- price/weight -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __(('variable.price')) }}</label>
                                <b-form-input
                                    v-model="form.price"
                                    class="addFormInputs"
                                    :placeholder="__(('variable.price'))"
                                />
                                <small v-if="errors.price" class="error-msg">{{ errors.price }}</small>
                            </b-col>
                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __(('variable.weight')) }}</label>
                                <b-form-input
                                    v-model="form.weight"
                                    class="addFormInputs"
                                    :placeholder="__(('variable.weight'))"
                                />
                                <small v-if="errors.weight" class="error-msg">{{ errors.weight }}</small>
                            </b-col>
                        </b-row>

                        <!-- size/weight -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __(('variable.size')) }}</label>
                                <b-form-input
                                    v-model="form.size"
                                    class="addFormInputs"
                                    :placeholder="__(('variable.size'))"
                                />
                                <small v-if="errors.size" class="error-msg">{{ errors.size }}</small>
                            </b-col>
                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __(('variable.weight')) }}</label>
                                <b-form-input
                                    v-model="form.weight"
                                    class="addFormInputs"
                                    :placeholder="__(('variable.weight'))"
                                />
                                <small v-if="errors.weight" class="error-msg">{{ errors.weight }}</small>
                            </b-col>
                        </b-row>

                        <!-- type/material -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __(('variable.type')) }}</label>
                                <b-form-input
                                    v-model="form.type"
                                    class="addFormInputs"
                                    :placeholder="__(('variable.type'))"
                                />
                                <small v-if="errors.type" class="error-msg">{{ errors.type }}</small>
                            </b-col>

                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __(('variable.material')) }}</label>
                                <b-form-input
                                    v-model="form.material"
                                    class="addFormInputs"
                                    :placeholder="__(('variable.material'))"
                                />
                                <small v-if="errors.material" class="error-msg">{{ errors.material }}</small>
                            </b-col>
                        </b-row>

                        <!-- images -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">{{ __(('variable.images')) }}</label>

                                <input type="file" class="fileInput" accept="image/*" multiple @change="onFiles" />
                                <small v-if="errors.images" class="error-msg">{{ errors.images }}</small>
                                <small v-if="errors['images.0']" class="error-msg">{{ errors['images.0'] }}</small>

                                <!-- existing images -->
                                <div v-if="existingImages.length" class="previewGrid">
                                    <div v-for="img in existingImages" :key="img.id" class="previewItem">
                                        <img :src="img.image_url" alt="existing" />
                                        <button type="button" class="xBtn" @click="markToDelete(img.id)">×</button>
                                    </div>
                                </div>

                                <!-- new previews -->
                                <div v-if="previewUrls.length" class="previewGrid">
                                    <div v-for="(u,idx) in previewUrls" :key="u" class="previewItem">
                                        <img :src="u" alt="preview" />
                                        <button type="button" class="xBtn" @click="removeNew(idx)">×</button>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>

                        <!-- description -->
                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">{{ __(('variable.description')) }}</label>
                                <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>
                                <small v-if="errors.description" class="error-msg">{{ errors.description }}</small>
                            </b-col>
                        </b-row>

                    </b-container>

                    <template #modal-footer>
                        <div class="w-100 handle_button">
                            <b-button :disabled="buttonDisabled" size="sm" class="float-right addButton" @click="save">
                                {{ addOrEdit === 'add' ? __(('variable.add')) : __(('variable.edit')) }}
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
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Multiselect from "vue-multiselect";

export default {
    name: "ProductsComponent",
    components: {Multiselect, Swal, VueLoading, PaginationComponent, SearchComponent },
    props: ["locale"],

    data() {
        return {
            lang: this.locale,

            editor: ClassicEditor,
            editorConfig: { placeholder: this.__("variable.description") },

            paginate: {
                perPage: 10,
                currentPage: 1,
                total: "",
                currentPageInput: "",
                lastPage: "",
            },

            fields: [
                { key: "id", label: this.__("variable.id"), sortable: true },
                { key: "title", label: this.__("variable.title"), sortable: true },
                { key: "service", label: this.__("variable.service"), sortable: false },
                { key: "images", label: this.__("variable.images"), sortable: false },
                { key: "actions", label: this.__("variable.action"), sortable: false, formatter: () => "" },
            ],

            isBusy: false,
            sortBy: "id",
            sortDesc: false,
            filter: [{ text: "", key: "" }],

            products: [],
            showTableLine: true,

            // modal
            show: false,
            headerBgVariant: "custom",
            headerTextVariant: "white",
            addOrEdit: "add",

            form: {
                id: null,
                service_id: null,
                title: "",
                description: "",
                price: "",
                size: "",
                weight: "",
                type: "",
                material: "",
            },

            // services list
            services: [],
            serviceOptions: [{ value: null, text: "—" }],

            // images
            newFiles: [],
            previewUrls: [],
            existingImages: [],
            deletedImageIds: [],

            errors: {},
            buttonDisabled: false,
            isLoading: false,
        };
    },

    watch: {
        locale(n) { this.lang = n; },
        filter: {
            handler() { this.paginate.currentPage = 1; },
            deep: true,
        },
    },

    mounted() {
        this.loadServices();
    },

    methods: {
        updateFilter(newFilter) {
            this.filter = newFilter;
        },

        toggleTable() {
            this.showTableLine = !this.showTableLine;
        },

        loadAfterChangePage(currentPage, perPage) {
            if (currentPage) this.paginate.currentPage = currentPage;
            if (perPage) this.paginate.perPage = perPage;
        },

        getData(data) {
            this.isBusy = true;
            return axios
                .get(route("products.index", { locale: this.lang }) + `?page=${data.currentPage}`, { params: data })
                .then((resp) => {
                    if (resp && resp.data && resp.data.data) {
                        this.products = resp.data.data;
                        this.paginate.total = resp.data.total;
                        this.paginate.currentPage = resp.data.current_page;
                        this.paginate.currentPageInput = this.paginate.currentPage;
                        this.paginate.lastPage = resp.data.last_page;
                        this.isBusy = false;
                        return this.products;
                    }
                })
                .catch((err) => {
                    this.isBusy = false;
                    this.showCatchError(err);
                });
        },

        loadServices() {
            // assumes services.index returns {data:[...]} or pagination. If your services index returns paginate, we can fetch large perPage.
            axios.get(route("services.index", { locale: this.lang }), { params: { perPage: 500 } })
                .then((resp) => {
                    const arr = resp?.data?.data || [];
                    this.services = arr;
                    this.serviceOptions = [{ value: null, text: "—" }]
                        .concat(arr.map(s => ({ value: s.id, text: s.title })));
                })
                .catch(() => {
                    // ignore, still usable without select data
                });
        },

        async showAddOrEditForm(obj = null, mode = "add") {
            if (!this.serviceOptions || this.serviceOptions.length <= 0) {
                await this.loadServices();
            }
            this.errors = {};
            this.addOrEdit = mode;
            const sid = obj ? (obj.service_id || (obj.service ? obj.service.id : null)) : null;

            this.form = {
                id: obj ? obj.id : null,
                service_id: this.findServiceOption(sid),
                title: obj ? obj.title : "",
                description: obj ? (obj.description || "") : "",
                price: obj ? (obj.price || "") : "",
                size: obj ? (obj.size || "") : "",
                weight: obj ? (obj.weight ?? "") : "",
                type: obj ? (obj.type || "") : "",
                material: obj ? (obj.material || "") : "",
            };

            // images
            this.newFiles = [];
            this.previewUrls = [];
            this.deletedImageIds = [];
            this.existingImages = (obj && obj.images) ? JSON.parse(JSON.stringify(obj.images)) : [];

            this.show = true;
        },

        findServiceOption(id) {
            if (id === null || id === undefined || id === "") return null;
            const num = Number(id);
            return this.serviceOptions.find(o => Number(o.value) === num) || null;
        },

        onFiles(e) {
            const files = Array.from((e.target.files || []));
            this.newFiles.push(...files);
            files.forEach((f) => this.previewUrls.push(URL.createObjectURL(f)));
            e.target.value = "";
        },

        removeNew(idx) {
            const url = this.previewUrls[idx];
            if (url) URL.revokeObjectURL(url);
            this.previewUrls.splice(idx, 1);
            this.newFiles.splice(idx, 1);
        },

        markToDelete(imageId) {
            this.existingImages = this.existingImages.filter(i => i.id !== imageId);
            if (!this.deletedImageIds.includes(imageId)) this.deletedImageIds.push(imageId);
        },

        save() {
            if (this.buttonDisabled) return;

            this.buttonDisabled = true;
            this.errors = {};
            this.isLoading = true;

            const fd = new FormData();
            if (this.form.id) fd.append("id", this.form.id);

            fd.append(
                "service_id",
                this.form.service_id && this.form.service_id.value != null ? this.form.service_id.value : ""
            );            fd.append("title", this.form.title || "");
            fd.append("description", this.form.description || "");
            fd.append("size", this.form.size || "");
            fd.append("price", this.form.price || "");
            fd.append("weight", this.form.weight || "");
            fd.append("type", this.form.type || "");
            fd.append("material", this.form.material || "");

            this.deletedImageIds.forEach((id, i) => fd.append(`deleted_image_ids[${i}]`, id));
            this.newFiles.forEach((file) => fd.append("images[]", file));

            axios
                .post(route("products.store", { locale: this.lang }), fd, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((resp) => {
                    if (resp && resp.data && resp.data.isSuccess) {
                        this.show = false;
                        this.$refs.table.refresh();

                        Swal.fire({
                            title: resp.data.message || this.__("variable.updated_successfully"),
                            icon: "success",
                            timer: 900,
                            showConfirmButton: false,
                        });
                    }
                    this.isLoading = false;
                    this.buttonDisabled = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.buttonDisabled = false;

                    if (err && err.response && err.response.data && typeof err.response.data === "object") {
                        const errors = err.response.data;
                        for (let k in errors) {
                            this.$set(this.errors, k, Array.isArray(errors[k]) ? errors[k][0] : errors[k]);
                        }
                    } else {
                        this.showCatchError(err);
                    }
                });
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
                    .delete(route("products.destroy", { locale: this.lang, product: id }))
                    .then((resp) => {
                        if (resp && resp.data && resp.data.isSuccess) {
                            this.$refs.table.refresh();
                            Swal.fire({ title: this.__("variable.success"), icon: "success", timer: 600, showConfirmButton: false });
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

