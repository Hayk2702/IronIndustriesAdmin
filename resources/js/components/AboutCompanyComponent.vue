<template>
    <div class="about-wrap">
        <div class="card glass">
            <div class="card-header">
                <div class="h-title">{{ __('variable.about_company') }}</div>
                <button v-if="can('editaboutcompany')" class="btnSave" :disabled="buttonDisabled" @click="save">
                    {{ __('variable.save') }}
                </button>
            </div>

            <div class="card-body">
                <div class="row-line">
                    <label class="lbl">{{ __('variable.title') }}</label>
                    <b-form-input
                        v-model="form.title"
                        class="form-control glassInput"
                        :class="errors.title ? 'error-border' : ''"
                        :placeholder="__('variable.title')"
                    />
                    <small v-if="errors.title" class="error-msg">{{ errors.title }}</small>
                </div>

                <div class="row-line">
                    <label class="lbl">{{ __('variable.image') }}</label>

                    <input type="file" class="fileInput" accept="image/*" @change="onFile" />
                    <small v-if="errors.image" class="error-msg">{{ errors.image }}</small>

                    <div v-if="previewUrl" class="preview">
                        <img :src="previewUrl" alt="preview" />
                    </div>
                    <div v-else-if="form.image_url" class="preview">
                        <img :src="form.image_url" alt="current" />
                    </div>
                </div>

                <div class="row-line">
                    <label class="lbl">{{ __('variable.description') }}</label>

                    <!-- Replace with your CKEditor component -->
                    <ckeditor :editor="editor" v-model="form.description" :config="editorConfig"></ckeditor>

                    <small v-if="errors.description" class="error-msg">{{ errors.description }}</small>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="loading-overflow">
            <vue-loading class="loading" type="balls" color="#d9544e" :size="{ width: '100px', height: '100px' }" />
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";
import { VueLoading } from "vue-loading-template";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";


export default {
    name: "AboutCompanyComponent",
    components: { Swal, VueLoading },

    props: ["locale"],

    data() {
        return {
            editor: ClassicEditor,
            editorConfig: {
                placeholder: this.__('variable.description'),
            },

            lang: this.locale,
            isLoading: false,
            buttonDisabled: false,
            errors: {},
            file: null,
            previewUrl: "",

            form: {
                id: null,
                title: "",
                description: "",
                image_url: null,
                image_path: null,
            },
        };
    },

    mounted() {
        this.load();
    },

    methods: {
        load() {
            this.isLoading = true;
            axios
                .get(route("about-company.index", { locale: this.lang }))
                .then((resp) => {
                    if (resp && resp.data) {
                        const row = resp.data.data;
                        if (row) {
                            this.form.id = row.id;
                            this.form.title = row.title || "";
                            this.form.description = row.description || "";
                            this.form.image_url = row.image_url || null;
                            this.form.image_path = row.image_path || null;
                        }
                    }
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.showCatchError(err);
                });
        },

        onFile(e) {
            const f = e.target.files && e.target.files[0];
            this.file = f || null;
            this.previewUrl = this.file ? URL.createObjectURL(this.file) : "";
        },

        save() {
            if (this.buttonDisabled) return;
            this.buttonDisabled = true;
            this.errors = {};
            this.isLoading = true;

            const fd = new FormData();
            fd.append("title", this.form.title || "");
            fd.append("description", this.form.description || "");
            if (this.file) fd.append("image", this.file);

            axios
                .post(route("about-company.store", { locale: this.lang }), fd, {
                    headers: { "Content-Type": "multipart/form-data" },
                })
                .then((resp) => {
                    if (resp && resp.data && resp.data.isSuccess) {
                        const row = resp.data.data;
                        this.form.id = row.id;
                        this.form.image_url = row.image_url || null;
                        this.file = null;
                        this.previewUrl = "";

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
                        for (let i in errors) {
                            // works with your FailedValidation format (array)
                            this.$set(this.errors, i, Array.isArray(errors[i]) ? errors[i][0] : errors[i]);
                        }
                    } else {
                        this.showCatchError(err);
                    }
                });
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
:deep(*) { box-sizing: border-box; }

.about-wrap{
    --border: rgba(255,255,255,.11);
    --text: rgba(255,255,255,.92);
    --muted: rgba(255,255,255,.62);
    --shadow: 0 22px 60px rgba(0,0,0,.45);
    --brand: #7c3aed;

    padding: 14px;
    color: var(--text);
}

.glass{
    border-radius: 18px;
    border: 1px solid var(--border);
    background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
    box-shadow: var(--shadow);
    overflow: hidden;
}

.card-header{
    display:flex;
    justify-content: space-between;
    align-items:center;
    padding: 14px;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.h-title{
    font-weight: 900;
    letter-spacing: .2px;
}

.btnSave{
    border: 1px solid rgba(124,58,237,.35);
    background: rgba(124,58,237,.16);
    color: rgba(255,255,255,.92);
    border-radius: 12px;
    padding: 8px 12px;
    font-weight: 900;
}

.btnSave:disabled{ opacity:.6; cursor:not-allowed; }

.card-body{ padding: 14px; }

.row-line{ margin-bottom: 14px; }

.lbl{
    display:block;
    font-size: 12px;
    font-weight: 900;
    color: var(--muted);
    margin-bottom: 6px;
}

.glassInput,
:deep(.form-control){
    border-radius: 12px !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    background: rgba(255,255,255,.05) !important;
    color: rgba(255,255,255,.92) !important;
}

.fileInput{
    width: 100%;
    padding: 10px;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.04);
    color: rgba(255,255,255,.85);
}

.preview{
    margin-top: 10px;
    border: 1px solid rgba(255,255,255,.10);
    border-radius: 14px;
    overflow:hidden;
    max-width: 420px;
}
.preview img{
    width: 100%;
    display:block;
}

.error-border{ border-color: rgba(255, 90, 106, .55) !important; }
.error-msg{ color: rgba(255, 90, 106, .95); font-weight: 800; font-size: 12px; margin-top: 6px; display:block; }

.loading-overflow{
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    backdrop-filter: blur(6px);
    z-index: 9999;
    display: grid;
    place-items: center;
}

/* editor container */
:deep(.ck.ck-editor__main){
    background: transparent !important;
}

/* editable area */
:deep(.ck-editor__editable){
    min-height: 220px;
    background: rgba(15,22,48,.95) !important;   /* dark background */
    color: rgba(255,255,255,.92) !important;     /* visible text */
    border-radius: 12px !important;
}

/* text inside editor */
:deep(.ck-editor__editable p),
:deep(.ck-editor__editable span),
:deep(.ck-editor__editable h1),
:deep(.ck-editor__editable h2),
:deep(.ck-editor__editable h3),
:deep(.ck-editor__editable li){
    color: rgba(255,255,255,.92) !important;
}

/* toolbar */
:deep(.ck-toolbar){
    background: rgba(20,28,60,.95) !important;
    border: 1px solid rgba(255,255,255,.12) !important;
    border-radius: 12px 12px 0 0 !important;
}

/* toolbar buttons */
:deep(.ck-button){
    color: rgba(255,255,255,.85) !important;
}

/* hover buttons */
:deep(.ck-button:hover){
    background: rgba(124,58,237,.25) !important;
}

/* dropdown panels */
:deep(.ck-dropdown__panel){
    background: rgba(20,28,60,.98) !important;
    color: white !important;
}
</style>
