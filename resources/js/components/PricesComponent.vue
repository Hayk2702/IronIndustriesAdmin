<template>
    <div>
        <SearchComponent
            ref="SearchComponent"
            @filter-updated="updateFilter"
            :keys="fields.filter(item => item.key !== 'actions' && item.key !== 'drag')"
            :isCondition="false"
            :isAction="false"
        />

        <div class="row justify-content-center" :style="{ 'pointer-events': isLoading ? 'none' : 'auto' }">
            <div class="w-100">
                <b-row class="justify-content-end">
                    <b-col sm="5" md="5" class="d-flex table_navigate justify-content-end pb-1">
                        <div v-if="can('createprices')" class="col-md-1">
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
                            <table class="table mb-0" :class="showTableLine ? 'table_line table table-striped table-bordered table-sm' : 'table_grid table'">
                                <thead>
                                <tr>
                                    <th style="width:60px;">#</th>
                                    <th style="width:90px;">{{ __('variable.position') || 'Position' }}</th>
                                    <th style="width:80px;">{{ __('variable.drag') || 'Drag' }}</th>
                                    <th>{{ __('variable.material_name') }}</th>
                                    <th>{{ __('variable.cut_cost') }}</th>
<!--                                    <th>{{ __('variable.material_cost_per_kg') }}</th>-->
                                    <th>{{ __('variable.density_kg_m2') }}</th>
                                    <th>{{ __('variable.bend_price') }}</th>
                                    <th>{{ __('variable.thicknesses') }}</th>
                                    <th style="width:140px;">{{ __('variable.action') }}</th>
                                </tr>
                                </thead>
                                <draggable
                                    v-model="items"
                                    tag="tbody"
                                    handle=".drag-handle"
                                    ghost-class="drag-ghost"
                                    chosen-class="drag-chosen"
                                    drag-class="drag-dragging"
                                    @end="onDragEnd"
                                >
                                    <tr v-for="(item, index) in items" :key="item.id" @click="can('editprices') ? showAddOrEditForm(item, 'edit') : null" style="cursor:pointer;">
                                        <td>{{ rowNumber(index) }}</td>
                                        <td>{{ item.position || rowNumber(index) }}</td>
                                        <td @click.stop><button type="button" class="drag-handle-btn"><span class="drag-handle">☰</span></button></td>
                                        <td>{{ item.material_name }}</td>
                                        <td>{{ item.cut_cost }}</td>
<!--                                        <td>{{ item.material_cost_per_kg }}</td>-->
                                        <td>{{ item.density_kg_m2 }}</td>
                                        <td>{{ item.bend_price }}</td>
                                        <td>
                                            <span v-if="item.thicknesses && item.thicknesses.length">{{ item.thicknesses.map(t => formatMm(t.thickness_mm)).join(', ') }}</span>
                                            <span v-else>—</span>
                                        </td>
                                        <td @click.stop>
                                            <a v-if="can('editprices')" class="btn btn-inverse-warning p-1 a_position" href="javascript:void(0)" @click.stop="showAddOrEditForm(item, 'edit')">
                                                <i class="bi bi-pencil title_hov_top">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg>
                                                </i>
                                            </a>
                                            <button v-if="can('deleteprices')" class="btn btn-danger p-1 a_position" @click.stop="destroyItem(item.id)">
                                                <i class="bi bi-trash title_hov_top">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="red" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                                                </i>
                                            </button>
                                        </td>
                                    </tr>
                                </draggable>
                            </table>
                        </div>
                    </div>

                    <PaginationComponent v-if="Object.keys(items).length > 0" :paginate="paginate" @loadAfterChangePage="loadAfterChangePage" />
                </div>

                <b-modal class="addModal" v-model="show" :header-text-variant="headerTextVariant" :header-bg-variant="headerBgVariant">
                    <template #modal-title>
                        <b-row>
                            <b-col sm="10" md="10" class="title_popup fs-15 lh-2">
                                {{ addOrEdit === 'add' ? __(('variable.add')) + ' ' + __(('variable.prices')) : __(('variable.edit')) + ' ' + __(('variable.prices')) }}
                            </b-col>
                        </b-row>
                    </template>

                    <b-container fluid>
                        <b-row class="mb-1 bRowPosition">
                            <b-col class="blockInput">
                                <label class="lbl">Material name</label>
                                <b-form-input :class="[errors.material_name ? 'error-border' : '', 'addFormInputs']" v-model="form.material_name" type="text" placeholder="Aluminum" />
                                <small v-if="errors.material_name" class="error-msg">{{ errors.material_name }}</small>
                            </b-col>
                        </b-row>

                        <b-row class="mb-1 bRowPosition">
                            <b-col md="6" class="blockInput">
                                <label class="lbl">{{ __('variable.position') || 'Position' }}</label>
                                <b-form-input :class="[errors.position ? 'error-border' : '', 'addFormInputs']" v-model="form.position" type="number" min="1" placeholder="1" />
                                <small v-if="errors.position" class="error-msg">{{ errors.position }}</small>
                            </b-col>
                            <b-col md="6" class="blockInput">
                                <label class="lbl">Cut cost</label>
                                <b-form-input v-model="form.cut_cost" class="addFormInputs" placeholder="0.00" />
                                <small v-if="errors.cut_cost" class="error-msg">{{ errors.cut_cost }}</small>
                            </b-col>
                        </b-row>

                        <b-row class="mb-1 bRowPosition">
                            <b-col md="6" class="blockInput">
                                <label class="lbl">Bend price</label>
                                <b-form-input v-model="form.bend_price" class="addFormInputs" placeholder="0.00" />
                                <small v-if="errors.bend_price" class="error-msg">{{ errors.bend_price }}</small>
                            </b-col>
<!--                            <b-col md="6" class="blockInput">-->
<!--                                <label class="lbl">Material cost per kg</label>-->
<!--                                <b-form-input v-model="form.material_cost_per_kg" class="addFormInputs" placeholder="0.00" />-->
<!--                                <small v-if="errors.material_cost_per_kg" class="error-msg">{{ errors.material_cost_per_kg }}</small>-->
<!--                            </b-col>-->
                        </b-row>

                        <b-row class="mb-1 bRowPosition">
                            <b-col md="6" class="blockInput">
                                <label class="lbl">Density (kg/m³)</label>
                                <b-form-input v-model="form.density_kg_m2" class="addFormInputs" placeholder="2700" />
                                <small v-if="errors.density_kg_m2" class="error-msg">{{ errors.density_kg_m2 }}</small>
                            </b-col>
                            <b-col class="blockInput">
                                <label class="lbl">Available thicknesses (mm)</label>
                                <multiselect
                                    v-model="form.thicknesses"
                                    :options="[]"
                                    :multiple="true"
                                    :taggable="true"
                                    @tag="addThicknessTag"
                                    label="label"
                                    track-by="value"
                                    placeholder="Type number and press Enter (ex: 3, 4, 6.5)"
                                />
                                <small v-if="errors.thicknesses" class="error-msg">{{ errors.thicknesses }}</small>
                                <small v-if="errors['thicknesses.0']" class="error-msg">{{ errors['thicknesses.0'] }}</small>
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
import Multiselect from "vue-multiselect";
import draggable from "vuedraggable";

export default {
    name: "PricesComponent",
    components: { Multiselect, Swal, VueLoading, PaginationComponent, SearchComponent, draggable },
    props: ["locale"],
    data() {
        return {
            lang: this.locale,
            paginate: { perPage: 10, currentPage: 1, total: "", currentPageInput: "", lastPage: "" },
            fields: [
                { key: "id", label: this.__("variable.id"), sortable: true },
                { key: "position", label: this.__("variable.position") || "Position", sortable: true },
                { key: "drag", label: this.__("variable.drag") || "Drag", sortable: false },
                { key: "material_name", label: this.__("variable.material_name"), sortable: true },
                { key: "cut_cost", label: this.__("variable.cut_cost"), sortable: true },
                // { key: "material_cost_per_kg", label: this.__("variable.material_cost_per_kg"), sortable: true },
                { key: "density_kg_m2", label: this.__("variable.density_kg_m2"), sortable: true },
                { key: "bend_price", label: this.__("variable.bend_price"), sortable: true },
                { key: "thicknesses", label: this.__("variable.thicknesses"), sortable: false },
                { key: "actions", label: this.__("variable.action"), sortable: false, formatter: () => "" },
            ],
            isBusy: false,
            sortBy: "position",
            sortDesc: false,
            filter: [{ text: "", key: "" }],
            items: [],
            showTableLine: true,
            show: false,
            headerBgVariant: "custom",
            headerTextVariant: "white",
            addOrEdit: "add",
            form: { id: null, material_name: "", cut_cost: "", density_kg_m2: "", bend_price: "", thicknesses: [], position: null }, //material_cost_per_kg: "",
            errors: {},
            buttonDisabled: false,
            isLoading: false,
            isSavingPositions: false,
        };
    },
    watch: {
        locale(n) { this.lang = n; this.fetchTableData(); },
        filter: { handler() { this.paginate.currentPage = 1; this.fetchTableData(); }, deep: true },
    },
    mounted() { this.fetchTableData(); },
    methods: {
        updateFilter(newFilter) { this.filter = newFilter; },
        toggleTable() { this.showTableLine = !this.showTableLine; },
        rowNumber(index) { return ((this.paginate.currentPage - 1) * this.paginate.perPage) + index + 1; },
        loadAfterChangePage(currentPage, perPage) {
            if (currentPage) this.paginate.currentPage = currentPage;
            if (perPage) this.paginate.perPage = perPage;
            this.fetchTableData();
        },
        fetchTableData() {
            this.getData({ currentPage: this.paginate.currentPage, perPage: this.paginate.perPage, sortBy: this.sortBy, sortDesc: this.sortDesc, filter: this.filter });
        },
        getData(data) {
            this.isBusy = true;
            return axios.get(route("prices.index", { locale: this.lang }) + `?page=${data.currentPage}`, { params: data })
                .then((resp) => {
                    if (resp?.data?.data) {
                        this.items = resp.data.data;
                        this.paginate.total = resp.data.total;
                        this.paginate.currentPage = resp.data.current_page;
                        this.paginate.currentPageInput = this.paginate.currentPage;
                        this.paginate.lastPage = resp.data.last_page;
                    }
                })
                .catch((err) => this.showCatchError(err))
                .finally(() => { this.isBusy = false; });
        },
        formatMm(v) {
            if (v === null || v === undefined) return "";
            const n = Number(v);
            if (Number.isNaN(n)) return String(v);
            const s = (Math.round(n * 1000) / 1000).toString();
            return `${s}mm`;
        },
        thicknessTagObj(num) {
            const n = Number(num);
            const s = (Math.round(n * 1000) / 1000).toString();
            return { value: n, label: `${s}mm` };
        },
        addThicknessTag(newTag) {
            const cleaned = String(newTag).replace(",", ".").trim();
            const n = Number(cleaned);
            if (!cleaned || Number.isNaN(n) || n <= 0) return;
            const exists = this.form.thicknesses.some(t => Number(t.value) === Number(n));
            if (!exists) this.form.thicknesses.push(this.thicknessTagObj(n));
            this.form.thicknesses.sort((a,b) => Number(a.value) - Number(b.value));
        },
        onDragEnd() { this.recalculatePositions(); this.savePositions(); },
        recalculatePositions() {
            const startPosition = ((this.paginate.currentPage - 1) * this.paginate.perPage) + 1;
            this.items = this.items.map((item, index) => ({ ...item, position: startPosition + index }));
        },
        savePositions() {
            if (this.isSavingPositions) return;
            this.isSavingPositions = true;
            const payload = { items: this.items.map((item, index) => ({ id: item.id, position: ((this.paginate.currentPage - 1) * this.paginate.perPage) + index + 1 })) };
            axios.post(route("prices.updatePositions", { locale: this.lang }), payload)
                .then((resp) => {
                    Swal.fire({ title: resp?.data?.message || this.__("variable.updated_successfully"), icon: "success", timer: 700, showConfirmButton: false });
                })
                .catch((err) => { this.showCatchError(err); this.fetchTableData(); })
                .finally(() => { this.isSavingPositions = false; });
        },
        showAddOrEditForm(obj = null, mode = "add") {
            this.errors = {};
            this.addOrEdit = mode;
            const th = (obj && obj.thicknesses) ? obj.thicknesses : [];
            const mapped = th.map(t => this.thicknessTagObj(t.thickness_mm));
            this.form = {
                id: obj ? obj.id : null,
                material_name: obj ? (obj.material_name || "") : "",
                cut_cost: obj ? (obj.cut_cost ?? "") : "",
                // material_cost_per_kg: obj ? (obj.material_cost_per_kg ?? "") : "",
                density_kg_m2: obj ? (obj.density_kg_m2 ?? "") : "",
                bend_price: obj ? (obj.bend_price ?? "") : "",
                thicknesses: mapped,
                position: obj ? (obj.position || null) : null,
            };
            this.show = true;
        },
        save() {
            if (this.buttonDisabled) return;
            this.buttonDisabled = true;
            this.errors = {};
            this.isLoading = true;
            const payload = {
                id: this.form.id,
                material_name: this.form.material_name,
                cut_cost: this.form.cut_cost,
                // material_cost_per_kg: this.form.material_cost_per_kg,
                density_kg_m2: this.form.density_kg_m2,
                bend_price: this.form.bend_price,
                thicknesses: (this.form.thicknesses || []).map(t => t.value),
                position: this.form.position,
            };
            axios.post(route("prices.store", { locale: this.lang }), payload)
                .then((resp) => {
                    if (resp?.data?.isSuccess) {
                        this.show = false;
                        this.fetchTableData();
                        Swal.fire({ title: resp.data.message || this.__("variable.updated_successfully"), icon: "success", timer: 900, showConfirmButton: false });
                    }
                })
                .catch((err) => {
                    if (err?.response?.data && typeof err.response.data === "object") {
                        const errors = err.response.data;
                        for (let k in errors) this.$set(this.errors, k, Array.isArray(errors[k]) ? errors[k][0] : errors[k]);
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
            Swal.fire({ title: this.__("variable.sure"), icon: "warning", showCancelButton: true, confirmButtonText: this.__("variable.yes"), cancelButtonText: this.__("variable.no") })
                .then((result) => {
                    if (!result.isConfirmed) return;
                    axios.delete(route("prices.destroy", { locale: this.lang, price: id }))
                        .then((resp) => {
                            if (resp?.data?.isSuccess) {
                                this.fetchTableData();
                                Swal.fire({ title: this.__("variable.success"), icon: "success", timer: 600, showConfirmButton: false });
                            }
                        })
                        .catch((err) => this.showCatchError(err));
                });
        },
        getRout(param) { return getRout(param); },
        showCatchError(err) {
            if (err.response && err.response.data) {
                Swal.fire({ icon: "error", title: `${err.response.data.message}`, confirmButtonText: this.__("variable.ok") });
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
