<template>
    <div class="parent_search">
        <div class="icon_search">
            <i v-if="filters.length < 6" class="bi bi-plus-square-fill btnAddFilter" @click="addFilter">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                     class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                    <path
                        d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"/>
                </svg>
            </i>
        </div>
        <div class="child_search">
            <div class="child_search_div" v-for="(filter,i) in filters">
                <b-col class="child_search_div_b_col">
                    <div class="searchDiv">
                        <multiselect
                            v-model="filter.key"
                            :options="options.map(item => item = {value: item.key, text : item.label})"
                            :multiple="false"
                            label="text"
                            track-by="value"
                            :selectedLabel="__(('variable.selected'))"
                            :selectLabel="__(('variable.select'))"
                            :deselectLabel="__(('variable.remove'))"
                            :placeholder="__(('variable.filter_by'))"
                            :showNoResults="false"
                            @input="updateFilter"
                            class="search_multi_select_custom"
                        >
                        </multiselect>
                        <select v-if="showAction" class="search_select" v-model="filter.action">
                            <option>LIKE</option>
                            <option>=</option>
                            <option>!=</option>
                            <option>></option>
                            <option><</option>
                            <option>>=</option>
                            <option><=</option>
                        </select>
                        <template
                            v-if="filter && filter.key && filter.key.value && datepickerStrings.indexOf(filter.key.value) !== -1">
                            <datepicker class="searchDpCustom"
                                        v-model="filter.text"
                                        :format="'dd-MM-yyyy'"
                                        :lang="translate"
                                        :editable="true"
                                        :placeholder="__(('variable.search'))"
                                        @change="updateFilter"
                            ></datepicker>
                        </template>
                        <template v-else>
                            <b-input
                                class="searchInput"
                                v-model="(filter.text instanceof Date) ? (filter.text = '') : filter.text"
                                @change="updateFilter"
                                :placeholder="__(('variable.search'))"
                            ></b-input>
                        </template>
                        <i class="bi bi-dash-square-fill btnRemoveFilter" v-if="filters.length > 1"
                           @click="removeFilter(i)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                 class="bi bi-dash-square-fill" viewBox="0 0 16 16">
                                <path
                                    d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 7.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/>
                            </svg>
                        </i>
                    </div>
                    <a v-if="i < filters.length - 1 && showCondition" href="javascript:void(0)" class="condition_a"
                       @click="changeCondition(filter)">
                        {{ filter.condition == "AND" ? __('variable.and') : __('variable.or') }}</a>
                </b-col>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import Datepicker from 'vue2-datepicker-mask';

export default {
    name: "SearchComponent",

    components: {
        Multiselect,
        Datepicker

    },

    props: [
        'keys',
        'isCondition',
        'isAction',
        'dateKeys',
    ],

    watch: {
        keys: function (newVal, oldVal) {
            this.options = newVal;
        },

        dateKeys: function (newVal, oldVal) {
            this.datepickerStrings = newVal;
        },

        isCondition: function (newVal, oldVal) {
            this.showCondition = newVal;
        },

        isAction: function (newVal, oldVal) {
            this.showAction = newVal;
        },
    },

    data() {

        return {
            //Data
            options: this.keys,
            showCondition: this.isCondition,
            showAction: this.isAction,
            datepickerStrings: this.dateKeys ? this.dateKeys : [],
            filters: [
                {
                    text: '',
                    key: '',
                    condition: 'AND',
                    action: 'LIKE',
                },
                {
                    text: '',
                    key: '',
                    condition: 'AND',
                    action: 'LIKE',
                },
                {
                    text: '',
                    key: '',
                    condition: 'AND',
                    action: 'LIKE',
                }
            ],
            translate: {
                days: [this.__('variable.sunday'), this.__('variable.monday'), this.__('variable.tuesday'), this.__('variable.wednesday'), this.__('variable.thursday'), this.__('variable.friday'), this.__('variable.saturday')],
                months: [this.__('variable.jan'), this.__('variable.feb'), this.__('variable.mar'), this.__('variable.apr'), this.__('variable.my'), this.__('variable.jun'), this.__('variable.jul'), this.__('variable.aug'), this.__('variable.sep'), this.__('variable.oct'), this.__('variable.nov'), this.__('variable.dec')],
                pickers: [this.__('variable.next_seven_days'), this.__('variable.next_thirty_days'), this.__('variable.last_seven_days'), this.__('variable.last_thirty_days')],
                placeholder: {
                    date: this.__('variable.select_date'),
                }
            },
            //DATA
        }
    },

    mounted() {
        // this.updateFilter();
    },

    created() {

    },

    methods: {

        updateFilter() {
            this.$emit("filter-updated", this.filters);
        },

        addFilter() {
            if (this.filters.length >= 6) {
                return
            }

            this.filters.push({text: '', key: '', condition: 'AND', action: 'LIKE',})
        },

        removeFilter(id) {
            if (this.filters.length === 3) {
                this.filters[id].text = '';
                this.filters[id].key = '';
                this.filters[id].condition = 'AND';
                this.filters[id].action = 'LIKE';
                return
            }
            this.filters.splice(id, 1);
            this.updateFilter();
        },

        resetFilter() {
            this.filters = [
                {
                    text: '',
                    key: '',
                    condition: 'AND',
                    action: 'LIKE',
                },
                {
                    text: '',
                    key: '',
                    condition: 'AND',
                    action: 'LIKE',
                },
                {
                    text: '',
                    key: '',
                    condition: 'AND',
                    action: 'LIKE',
                },

            ];
        },

        changeCondition(item) {
            if (!item || !item.condition) {
                return
            }
            if (item.condition === "AND") {
                item.condition = "OR";
            } else {
                item.condition = "AND";
            }
        },
    }
}
</script>

<style scoped>
:deep(.multiselect) {
    font-family: inherit;
}

/* Input (closed state) */
:deep(.multiselect__tags) {
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(255,255,255,.05) !important;
    min-height: 42px !important;
    padding: 8px 10px !important;
}
:deep(.multiselect__single),
:deep(.multiselect__input) {
    color: rgba(255,255,255,.92) !important;
    background: transparent !important;
}
:deep(.multiselect__placeholder) {
    color: rgba(255,255,255,.45) !important;
}

/* Dropdown panel */
:deep(.multiselect__content-wrapper) {
    border-radius: 14px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(10, 14, 30, .98) !important;          /* <— dropdown bg */
    box-shadow: 0 18px 50px rgba(0,0,0,.45) !important;
    overflow: hidden;
}

/* Options (normal) */
:deep(.multiselect__option) {
    color: rgba(255,255,255,.85) !important;               /* <— text */
    background: transparent !important;
    padding: 10px 12px !important;
}

/* Hover option */
:deep(.multiselect__option--highlight) {
    background: rgba(124,58,237,.22) !important;           /* purple hover */
    color: rgba(255,255,255,.95) !important;
}

/* Selected option row */
:deep(.multiselect__option--selected) {
    background: rgba(6,182,212,.16) !important;            /* cyan selected row */
    color: rgba(255,255,255,.95) !important;
}

/* Selected + hovered */
:deep(.multiselect__option--selected.multiselect__option--highlight) {
    background: rgba(6,182,212,.24) !important;
}

/* ✅ This fixes the right green “Select” badge you see in the screenshot */
:deep(.multiselect__option span) {
    color: inherit !important;
}

/* The small “Select / Selected” label (right side) */
:deep(.multiselect__option::after) {
    background: rgba(124,58,237,.18) !important;           /* badge bg */
    color: rgba(255,255,255,.92) !important;               /* badge text */
    border-left: 1px solid rgba(255,255,255,.08) !important;
}

/* When option is selected: badge style */
:deep(.multiselect__option--selected::after) {
    background: rgba(6,182,212,.20) !important;
    color: rgba(255,255,255,.95) !important;
}

/* Caret icon */
:deep(.multiselect__select::before) {
    border-color: rgba(255,255,255,.65) transparent transparent transparent !important;
}

/* Focus ring */
:deep(.multiselect--active .multiselect__tags) {
    box-shadow: 0 0 0 4px rgba(124,58,237,.18) !important;
    border-color: rgba(124,58,237,.45) !important;
}


:deep(*) { box-sizing: border-box; }

.parent_search{
    --border: rgba(255,255,255,.11);
    --text: rgba(255,255,255,.92);
    --muted: rgba(255,255,255,.62);
    --shadow: 0 18px 50px rgba(0,0,0,.45);
    --brand: #7c3aed;

    border: 1px solid var(--border);
    background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
    box-shadow: var(--shadow);
    border-radius: 18px;
    padding: 12px;
    margin-bottom: 12px;
}

.icon_search{
    display:flex;
    justify-content:flex-end;
    margin-bottom:10px;
}

.child_search{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.child_search_div{
    width:100%;
}

/* IMPORTANT: remove bootstrap column behavior */
.child_search_div_b_col{
    padding:0 !important;
    flex: 1 1 auto;
    max-width: 100% !important;
}

/* ✅ Side-by-side layout */
.searchDiv{
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap: nowrap;              /* keep on one line */
}

/* widths of each control (change if you want) */
.search_multi_select_custom{
    flex: 0 0 320px;               /* 1) field selector */
    min-width: 260px;
}

.search_select{
    flex: 0 0 110px;               /* 2) operator */
    min-width: 90px;
    height: 42px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.05);
    color: var(--text);
    padding: 0 10px;
}

.searchInput,
:deep(.form-control){
    flex: 1 1 auto;                /* 3) value input takes rest */
    min-width: 220px;
    height: 42px;
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(255,255,255,.05) !important;
    color: var(--text) !important;
}

.btnAddFilter,
.btnRemoveFilter{
    flex: 0 0 38px;                /* 4) remove button */
    width: 38px;
    height: 38px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(255,255,255,.05);
    color: var(--text);
    cursor: pointer;
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

/* datepicker input should behave like searchInput */
:deep(.searchDpCustom){
    flex: 1 1 auto;
    min-width: 220px;
}
:deep(.searchDpCustom input){
    width:100% !important;
    height: 42px !important;
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(255,255,255,.05) !important;
    color: rgba(255,255,255,.92) !important;
}

.searchInput::placeholder,
:deep(.form-control::placeholder){
    color: rgba(255,255,255,.45) !important;
}

.searchInput:focus,
.search_select:focus,
:deep(.form-control:focus){
    outline:none;
    box-shadow: 0 0 0 4px rgba(124,58,237,.18) !important;
    border-color: rgba(124,58,237,.45) !important;
}

/* Multiselect */
:deep(.multiselect){
    width:100%;
}
:deep(.multiselect__tags){
    height: 42px;
    min-height: 42px;
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(255,255,255,.05) !important;
    padding: 8px 10px !important;
}
:deep(.multiselect__input),
:deep(.multiselect__single){
    background: transparent !important;
    color: rgba(255,255,255,.92) !important;
}
:deep(.multiselect__content-wrapper){
    border-radius: 14px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(15, 22, 48, .98) !important;
    box-shadow: var(--shadow) !important;
}
:deep(.multiselect__option--highlight){
    background: rgba(124,58,237,.25) !important;
}

/* AND/OR */
.condition_a{
    display:inline-flex;
    margin-top:6px;
    font-weight:900;
    font-size:11px;
    letter-spacing:.25px;
    color: rgba(255,255,255,.75);
    text-decoration:none;
    border: 1px solid rgba(255,255,255,.10);
    background: rgba(255,255,255,.04);
    padding: 6px 10px;
    border-radius: 999px;
    width: fit-content;
}

/* ✅ Only on very small screens allow wrap */
@media (max-width: 720px){
    .searchDiv{
        flex-wrap: wrap;             /* allow wrap on phones */
    }
    .search_multi_select_custom{
        flex: 1 1 100%;
        min-width: 0;
    }
    .search_select{
        flex: 0 0 110px;
    }
    .searchInput,
    :deep(.searchDpCustom){
        flex: 1 1 calc(100% - 120px - 48px);
        min-width: 0;
    }
}
</style>
