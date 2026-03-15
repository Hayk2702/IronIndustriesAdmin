<template>
    <div>
        <SearchComponent
            ref="SearchComponent"
            @filter-updated="updateFilter"
            :keys="fields.filter(item => item.key !== 'actions' && item.key !== 'images' && item.key !== 'drag')"
            :isCondition="false"
            :isAction="false"
        />

        <div class="row justify-content-center" :style="{ 'pointer-events': isLoading ? 'none' : 'auto' }">
            <div class="w-100">
                <b-row class="justify-content-end">
                    <b-col sm="5" md="5" class="d-flex table_navigate justify-content-end pb-1">
                        <div v-if="can('createservices')" class="col-md-1">
                            <a class="btn btnAdd" href="javascript:void(0)" @click="showAddOrEditForm()">
                                {{ __(('variable.add')) }}
                            </a>
                        </div>
                    </b-col>
                </b-row>

                <div class="parent_card_pagination">
                    <div class="card">
                        <div v-if="isBusy" class="text-center text-danger my-3">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>{{ __(('variable.loaded')) }}</strong>
                        </div>

                        <div v-else class="table-responsive">
                            <table
                                class="table mb-0"
                                :class="showTableLine ? 'table_line table table-striped table-bordered table-sm' : 'table_grid table'"
                            >
                                <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th style="width: 90px;">{{ __('variable.position') || 'Position' }}</th>
                                    <th style="width: 80px;">{{ __('variable.drag') || 'Drag' }}</th>
                                    <th>{{ __('variable.title') }}</th>
                                    <th>{{ __('variable.images') }}</th>
                                    <th style="width: 140px;">{{ __('variable.action') }}</th>
                                </tr>
                                </thead>

                                <draggable
                                    v-model="services"
                                    tag="tbody"
                                    handle=".drag-handle"
                                    ghost-class="drag-ghost"
                                    chosen-class="drag-chosen"
                                    drag-class="drag-dragging"
                                    @end="onDragEnd"
                                >
                                    <tr
                                        v-for="(item, index) in services"
                                        :key="item.id"
                                        @click="can('editservices') ? showAddOrEditForm(item, 'edit') : null"
                                        style="cursor:pointer;"
                                    >
                                        <td>{{ rowNumber(index) }}</td>
                                        <td>{{ item.position || rowNumber(index) }}</td>
                                        <td @click.stop>
                                            <button type="button" class="drag-handle-btn">
                                                <span class="drag-handle">☰</span>
                                            </button>
                                        </td>
                                        <td>{{ item.title }}</td>
                                        <td>
                                            <div class="thumbs" v-if="item.images && item.images.length">
                                                <img
                                                    v-for="img in item.images.slice(0, 3)"
                                                    :key="img.id"
                                                    :src="img.image_url"
                                                    class="thumb"
                                                    alt="img"
                                                />
                                                <span v-if="item.images.length > 3" class="more">
                                                    +{{ item.images.length - 3 }}
                                                </span>
                                            </div>
                                            <span v-else class="muted">—</span>
                                        </td>
                                        <td @click.stop>
                                            <a
                                                v-if="can('editservices')"
                                                class="btn btn-inverse-warning p-1 a_position"
                                                href="javascript:void(0)"
                                                @click.stop="showAddOrEditForm(item, 'edit')"
                                            >
                                                <i class="bi bi-pencil title_hov_top">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                    </svg>
                                                </i>
                                            </a>

                                            <button
                                                v-if="can('deleteservices')"
                                                class="btn btn-danger p-1 a_position"
                                                @click.stop="destroyItem(item.id)"
                                            >
                                                <i class="bi bi-trash title_hov_top">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="red" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                    </svg>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                </draggable>
                            </table>
                        </div>
                    </div>

                    <PaginationComponent
                        v-if="Object.keys(services).length > 0"
                        :paginate="paginate"
                        @loadAfterChangePage="loadAfterChangePage"
                    />
                </div>

                <b-modal class="addModal" v-model="show" :header-text-variant="headerTextVariant" :header-bg-variant="headerBgVariant">
                    <template #modal-title>
                        <b-row>
                            <b-col sm="10" md="10" class="title_popup fs-15 lh-2">
                                {{ (addOrEdit === 'add' ? __(('variable.add')) : __(('variable.edit'))) + " " + __(('variable.service')) }}
                            </b-col>
                        </b-row>
                    </template>

                    <b-container fluid>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <b-form-input
                                    :class="[errors.title ? 'error-border' : '', 'addFormInputs']"
                                    v-model="form.title"
                                    type="text"
                                    :placeholder="__(('variable.title'))"
                                />
                                <small v-if="errors.title" class="error-msg">{{ errors.title }}</small>
                            </b-col>
                        </b-row>

                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">{{ __('variable.position') || 'Position' }}</label>
                                <b-form-input
                                    :class="[errors.position ? 'error-border' : '', 'addFormInputs']"
                                    v-model="form.position"
                                    type="number"
                                    min="1"
                                    placeholder="1"
                                />
                                <small v-if="errors.position" class="error-msg">{{ errors.position }}</small>
                            </b-col>
                        </b-row>

                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">{{ __(('variable.images')) }}</label>
                                <input type="file" class="fileInput" accept="image/*" multiple @change="onFiles" />
                                <small v-if="errors.images" class="error-msg">{{ errors.images }}</small>
                                <small v-if="errors['images.0']" class="error-msg">{{ errors['images.0'] }}</small>

                                <div v-if="existingImages.length" class="previewGrid">
                                    <div v-for="img in existingImages" :key="img.id" class="previewItem">
                                        <img :src="img.image_url" alt="existing" />
                                        <button type="button" class="xBtn" @click="markToDelete(img.id)">×</button>
                                    </div>
                                </div>

                                <div v-if="previewUrls.length" class="previewGrid">
                                    <div v-for="(u, idx) in previewUrls" :key="u" class="previewItem">
                                        <img :src="u" alt="preview" />
                                        <button type="button" class="xBtn" @click="removeNew(idx)">×</button>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>

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
import draggable from "vuedraggable";

export default {
    name: "ServicesComponent",
    components: { Swal, VueLoading, PaginationComponent, SearchComponent, draggable },
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
                { key: "position", label: this.__("variable.position") || "Position", sortable: true },
                { key: "drag", label: this.__("variable.drag") || "Drag", sortable: false },
                { key: "title", label: this.__("variable.title"), sortable: true },
                { key: "images", label: this.__("variable.images"), sortable: false },
                { key: "actions", label: this.__("variable.action"), sortable: false, formatter: () => "" },
            ],
            isBusy: false,
            sortBy: "position",
            sortDesc: false,
            filter: [{ text: "", key: "" }],
            services: [],
            showTableLine: true,
            show: false,
            headerBgVariant: "custom",
            headerTextVariant: "white",
            addOrEdit: "add",
            form: { id: null, title: "", description: "", position: null },
            newFiles: [],
            previewUrls: [],
            existingImages: [],
            deletedImageIds: [],
            errors: {},
            buttonDisabled: false,
            isLoading: false,
            isSavingPositions: false,
        };
    },

    watch: {
        locale(n) {
            this.lang = n;
            this.fetchTableData();
        },
        filter: {
            handler() {
                this.paginate.currentPage = 1;
                this.fetchTableData();
            },
            deep: true,
        },
    },

    mounted() {
        this.fetchTableData();
    },

    methods: {
        updateFilter(newFilter) {
            this.filter = newFilter;
        },
        toggleTable() {
            this.showTableLine = !this.showTableLine;
        },
        rowNumber(index) {
            return ((this.paginate.currentPage - 1) * this.paginate.perPage) + index + 1;
        },
        loadAfterChangePage(currentPage, perPage) {
            if (currentPage) this.paginate.currentPage = currentPage;
            if (perPage) this.paginate.perPage = perPage;
            this.fetchTableData();
        },
        fetchTableData() {
            this.getData({
                currentPage: this.paginate.currentPage,
                perPage: this.paginate.perPage,
                sortBy: this.sortBy,
                sortDesc: this.sortDesc,
                filter: this.filter,
            });
        },
        getData(data) {
            this.isBusy = true;
            return axios
                .get(route("services.index", { locale: this.lang }) + `?page=${data.currentPage}`, { params: data })
                .then((resp) => {
                    if (resp && resp.data && resp.data.data) {
                        this.services = resp.data.data;
                        this.paginate.total = resp.data.total;
                        this.paginate.currentPage = resp.data.current_page;
                        this.paginate.currentPageInput = this.paginate.currentPage;
                        this.paginate.lastPage = resp.data.last_page;
                    }
                })
                .catch((err) => this.showCatchError(err))
                .finally(() => {
                    this.isBusy = false;
                });
        },
        onDragEnd() {
            this.recalculatePositions();
            this.savePositions();
        },
        recalculatePositions() {
            const startPosition = ((this.paginate.currentPage - 1) * this.paginate.perPage) + 1;
            this.services = this.services.map((item, index) => ({
                ...item,
                position: startPosition + index,
            }));
        },
        savePositions() {
            if (this.isSavingPositions) return;
            this.isSavingPositions = true;

            const payload = {
                items: this.services.map((item, index) => ({
                    id: item.id,
                    position: ((this.paginate.currentPage - 1) * this.paginate.perPage) + index + 1,
                })),
            };

            axios
                .post(route("services.updatePositions", { locale: this.lang }), payload)
                .then((resp) => {
                    Swal.fire({
                        title: resp?.data?.message || this.__("variable.updated_successfully"),
                        icon: "success",
                        timer: 700,
                        showConfirmButton: false,
                    });
                })
                .catch((err) => {
                    this.showCatchError(err);
                    this.fetchTableData();
                })
                .finally(() => {
                    this.isSavingPositions = false;
                });
        },
        showAddOrEditForm(obj = null, mode = "add") {
            this.errors = {};
            this.addOrEdit = mode;
            this.form = {
                id: obj ? obj.id : null,
                title: obj ? obj.title : "",
                description: obj ? (obj.description || "") : "",
                position: obj ? (obj.position || null) : null,
            };
            this.newFiles = [];
            this.previewUrls = [];
            this.deletedImageIds = [];
            this.existingImages = (obj && obj.images) ? JSON.parse(JSON.stringify(obj.images)) : [];
            this.show = true;
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
            fd.append("title", this.form.title || "");
            fd.append("description", this.form.description || "");
            if (this.form.position) fd.append("position", this.form.position);
            this.deletedImageIds.forEach((id, i) => fd.append(`deleted_image_ids[${i}]`, id));
            this.newFiles.forEach((file) => fd.append("images[]", file));

            axios
                .post(route("services.store", { locale: this.lang }), fd, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((resp) => {
                    if (resp && resp.data && resp.data.isSuccess) {
                        this.show = false;
                        this.fetchTableData();
                        Swal.fire({
                            title: resp.data.message || this.__("variable.updated_successfully"),
                            icon: "success",
                            timer: 900,
                            showConfirmButton: false,
                        });
                    }
                })
                .catch((err) => {
                    if (err && err.response && err.response.data && typeof err.response.data === "object") {
                        const errors = err.response.data;
                        for (let k in errors) {
                            this.$set(this.errors, k, Array.isArray(errors[k]) ? errors[k][0] : errors[k]);
                        }
                    } else {
                        this.showCatchError(err);
                    }
                })
                .finally(() => {
                    this.isLoading = false;
                    this.buttonDisabled = false;
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
                    .delete(route("services.destroy", { locale: this.lang, service: id }))
                    .then((resp) => {
                        if (resp && resp.data && resp.data.isSuccess) {
                            this.fetchTableData();
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

<style scoped>

.drag-handle-btn {
    border: none;
    background: transparent;
    cursor: grab;
    padding: 4px 8px;
}

.drag-handle {
    font-size: 20px;
    color: #666;
    user-select: none;
}

.drag-ghost {
    opacity: 0.5;
    background: #f8f9fa;
}

.drag-chosen {
    background: #fff8e1;
}

.drag-dragging {
    background: #f1f3f5;
}
</style>
