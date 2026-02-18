<template>
    <div class="pagerWrap">
        <div class="pagerTop">
            <div class="left">
                <span class="label">{{ __(('variable.total')) }}</span>
                <span class="value">{{ total }}</span>
            </div>
        </div>

        <div class="pagerMid" v-if="total > perPage">
            <div class="paginationBox">
                <b-pagination
                    v-model="currentPage"
                    @change="currentPageChange"
                    :total-rows="total"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                />
            </div>

            <b-form-input
                class="pageInput"
                @change="changePageInp"
                v-model="currentPageInput"
                type="number"
                min="1"
            />
        </div>

        <div class="pagerBottom">
            <span class="label">{{ __(('variable.show_count')) }}</span>
            <b-form-input class="perPageInput" v-model="perPage" type="number" min="1" />
        </div>
    </div>
</template>

<script>
export default {
    name: "PaginationComponent",
    props: ["paginate"],

    data() {
        return {
            currentPage: this.paginate.currentPage,
            currentPageInput: this.paginate.currentPageInput,
            perPage: this.paginate.perPage,
            lastPage: this.paginate.lastPage,
            total: this.paginate.total,
        };
    },

    watch: {
        "paginate.currentPage"(n) {
            this.currentPage = n;
        },
        "paginate.currentPageInput"(n) {
            this.currentPageInput = n;
        },
        "paginate.perPage"(n) {
            this.perPage = n < 1 ? 1 : n;
            this.currentPage = 1;
            this.paginate.currentPage = 1;
        },
        perPage(n) {
            this.perPage = n < 1 ? 1 : n;
            this.currentPage = 1;
            this.$emit("loadAfterChangePage", this.currentPage, this.perPage);
        },
        "paginate.lastPage"(n) {
            this.lastPage = n;
        },
        "paginate.total"(n) {
            this.total = n;
        },
    },

    methods: {
        changePageInp() {
            this.currentPage = this.currentPageInput > 1 ? Number(this.currentPageInput) : 1;

            if (this.currentPage > this.lastPage) this.currentPage = Number(this.lastPage);
            if (this.currentPageInput > this.lastPage) this.currentPageInput = Number(this.lastPage);

            this.$emit("loadAfterChangePage", this.currentPage, "");
        },

        currentPageChange(page) {
            this.currentPageInput = page;
            this.changePageInp();
        },
    },
};
</script>

<style scoped>
:deep(*) { box-sizing: border-box; }

.my-0 {
    width: 50px !important;
}
:deep(.paginationBox .pagination){
    display: flex !important;
    align-items: center;
    gap: 8px;                 /* space between items */
    flex-wrap: nowrap;
}

/* remove bootstrap flex-fill stretch */
:deep(.paginationBox .page-item){
    flex: 0 0 auto !important;
    display: inline-flex !important;
}

/* buttons */
:deep(.paginationBox .page-link){
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(255,255,255,.05) !important;
    color: rgba(255,255,255,.9) !important;

    transition: all .15s ease;
}

/* hover */
:deep(.paginationBox .page-link:hover){
    background: rgba(124,58,237,.18) !important;
    border-color: rgba(124,58,237,.45) !important;
}

/* active page */
:deep(.paginationBox .page-item.active .page-link){
    background: rgba(6,182,212,.22) !important;
    border-color: rgba(6,182,212,.55) !important;
    font-weight: 800;
}

/* disabled */
:deep(.paginationBox .page-item.disabled .page-link){
    opacity: .35;
    cursor: not-allowed;
}
.pagerWrap{
    --border: rgba(255,255,255,.11);
    --text: rgba(255,255,255,.92);
    --muted: rgba(255,255,255,.62);
    --shadow: 0 18px 50px rgba(0,0,0,.45);
    --brand:#06b6d4;

    margin-top: 12px;
    border: 1px solid var(--border);
    background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.03));
    border-radius: 18px;
    padding: 12px;
    box-shadow: var(--shadow);
    color: var(--text);
}

.pagerTop{
    display:flex;
    justify-content: space-between;
    align-items:center;
    gap: 10px;
}

.left{
    display:flex;
    align-items:center;
    gap: 10px;
}

.label{
    color: var(--muted);
    font-weight: 800;
    font-size: 12px;
}

.value{
    font-weight: 900;
    font-size: 12px;
}

.pagerMid{
    margin-top: 10px;
    display:grid;
    grid-template-columns: 1fr 90px;
    gap: 10px;
    align-items:center;
}

.pageInput,
.perPageInput{
    height: 38px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.05);
    color: var(--text);
}

.pageInput:focus,
.perPageInput:focus{
    outline:none;
    box-shadow: 0 0 0 4px rgba(6,182,212,.16);
    border-color: rgba(6,182,212,.45);
}

.pagerBottom{
    width: 100px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid rgba(255,255,255,.08);

    display:flex;
    justify-content: space-between;
    align-items:center;
    gap: 10px;
}

/* Pagination */
:deep(.pagination){
    margin: 0;
    gap: 6px;
}
:deep(.page-link){
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,.10) !important;
    background: rgba(255,255,255,.04) !important;
    color: rgba(255,255,255,.82) !important;
}
:deep(.page-link:hover){
    background: rgba(255,255,255,.08) !important;
    border-color: rgba(6,182,212,.35) !important;
}
:deep(.page-item.active .page-link){
    background: rgba(6,182,212,.18) !important;
    border-color: rgba(6,182,212,.45) !important;
    color: rgba(255,255,255,.92) !important;
}

@media (max-width: 768px){
    .pagerMid{
        grid-template-columns: 1fr;
    }
}
</style>
