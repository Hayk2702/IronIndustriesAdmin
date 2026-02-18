<template>
    <div class="about-wrap">
        <div class="card glass">
            <div class="card-header">
                <div class="h-title">{{ __('variable.about_us') }}</div>

                <button
                    v-if="can('editaboutus')"
                    class="btnSave"
                    @click="save"
                    :disabled="loading"
                >
                    {{ __('variable.save') }}
                </button>
            </div>

            <div class="card-body">
                <!-- Address -->
                <div class="field">
                    <input
                        v-model="form.address"
                        :placeholder="__('variable.address')"
                        class="glassInput"
                        :class="errors.address ? 'error-border' : ''"
                    />
                    <small v-if="errors.address" class="error-msg">{{ errors.address }}</small>
                </div>

                <!-- Phone -->
                <div class="field">
                    <input
                        v-model="form.phone"
                        :placeholder="__('variable.phone')"
                        class="glassInput"
                        :class="errors.phone ? 'error-border' : ''"
                    />
                    <small v-if="errors.phone" class="error-msg">{{ errors.phone }}</small>
                </div>

                <!-- Lat / Lng -->
                <div class="row2">
                    <div class="field">
                        <input
                            v-model="form.lat"
                            :placeholder="__('variable.lat')"
                            class="glassInput"
                            :class="errors.lat ? 'error-border' : ''"
                        />
                        <small v-if="errors.lat" class="error-msg">{{ errors.lat }}</small>
                    </div>

                    <div class="field">
                        <input
                            v-model="form.lng"
                            :placeholder="__('variable.lng')"
                            class="glassInput"
                            :class="errors.lng ? 'error-border' : ''"
                        />
                        <small v-if="errors.lng" class="error-msg">{{ errors.lng }}</small>
                    </div>
                </div>

                <!-- Working hours -->
                <div class="field">
                    <input
                        v-model="form.working_hours"
                        :placeholder="__('variable.working_hours')"
                        class="glassInput"
                        :class="errors.working_hours ? 'error-border' : ''"
                    />
                    <small v-if="errors.working_hours" class="error-msg">{{ errors.working_hours }}</small>
                </div>

                <h4 class="mt">{{ __('variable.social_links') }}</h4>

                <!-- Social links -->
                <div class="field">
                    <input
                        v-model="form.facebook"
                        :placeholder="__('variable.facebook')"
                        class="glassInput"
                        :class="errors.facebook ? 'error-border' : ''"
                    />
                    <small v-if="errors.facebook" class="error-msg">{{ errors.facebook }}</small>
                </div>

                <div class="field">
                    <input
                        v-model="form.instagram"
                        :placeholder="__('variable.instagram')"
                        class="glassInput"
                        :class="errors.instagram ? 'error-border' : ''"
                    />
                    <small v-if="errors.instagram" class="error-msg">{{ errors.instagram }}</small>
                </div>

                <div class="field">
                    <input
                        v-model="form.linkedin"
                        :placeholder="__('variable.linkedin')"
                        class="glassInput"
                        :class="errors.linkedin ? 'error-border' : ''"
                    />
                    <small v-if="errors.linkedin" class="error-msg">{{ errors.linkedin }}</small>
                </div>

                <div class="field">
                    <input
                        v-model="form.telegram"
                        :placeholder="__('variable.telegram')"
                        class="glassInput"
                        :class="errors.telegram ? 'error-border' : ''"
                    />
                    <small v-if="errors.telegram" class="error-msg">{{ errors.telegram }}</small>
                </div>

                <div class="field">
                    <input
                        v-model="form.viber"
                        :placeholder="__('variable.viber')"
                        class="glassInput"
                        :class="errors.viber ? 'error-border' : ''"
                    />
                    <small v-if="errors.viber" class="error-msg">{{ errors.viber }}</small>
                </div>

                <div class="field">
                    <input
                        v-model="form.whatsapp"
                        :placeholder="__('variable.whatsapp')"
                        class="glassInput"
                        :class="errors.whatsapp ? 'error-border' : ''"
                    />
                    <small v-if="errors.whatsapp" class="error-msg">{{ errors.whatsapp }}</small>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";

export default {
    name: "AboutUsComponent",

    props: ["locale"],

    data() {
        return {
            loading: false,
            errors: {},

            form: {
                address: "",
                phone: "",
                lat: "",
                lng: "",
                working_hours: "",
                facebook: "",
                instagram: "",
                linkedin: "",
                telegram: "",
                viber: "",
                whatsapp: "",
            },
        };
    },

    mounted() {
        this.load();
    },

    methods: {
        load() {
            axios
                .get(route("about-us.index", { locale: this.locale }))
                .then((r) => {
                    if (r && r.data && r.data.data) {
                        this.form = { ...this.form, ...r.data.data };
                    }
                })
                .catch((err) => this.showCatchError(err));
        },

        save() {
            if (this.loading) return;

            this.loading = true;
            this.errors = {};

            axios
                .post(route("about-us.store", { locale: this.locale }), this.form)
                .then((resp) => {
                    this.loading = false;

                    Swal.fire({
                        icon: "success",
                        title: resp?.data?.message || this.__("variable.updated_successfully"),
                        timer: 900,
                        showConfirmButton: false,
                    });
                })
                .catch((err) => {
                    this.loading = false;

                    // ✅ Validation errors from FailedValidation
                    if (err && err.response && err.response.data && typeof err.response.data === "object") {
                        const errors = err.response.data;
                        for (let key in errors) {
                            this.$set(
                                this.errors,
                                key,
                                Array.isArray(errors[key]) ? errors[key][0] : errors[key]
                            );
                        }
                        return;
                    }

                    this.showCatchError(err);
                });
        },

        showCatchError(err) {
            if (err && err.response && err.response.data) {
                Swal.fire({
                    icon: "error",
                    title: `${err.response.data.message || "Error"}`,
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

.card-body{
    padding: 14px;
}

.field{
    margin-bottom: 10px;
}

.glassInput{
    width:100%;
    padding:10px 12px;
    border-radius:12px;
    background:rgba(255,255,255,.07);
    color:rgba(255,255,255,.92);
    border:1px solid rgba(255,255,255,.15);
    outline:none;
}

.glassInput::placeholder{
    color: rgba(255,255,255,.45);
}

.glassInput:focus{
    box-shadow: 0 0 0 4px rgba(124,58,237,.18);
    border-color: rgba(124,58,237,.45);
}

.row2{
    display:flex;
    gap:10px;
}
.row2 .field{
    flex: 1 1 0;
    margin-bottom: 10px;
}

.btnSave{
    border: 1px solid rgba(124,58,237,.35);
    background: rgba(124,58,237,.16);
    color: rgba(255,255,255,.92);
    border-radius: 12px;
    padding: 8px 12px;
    font-weight: 900;
}

.btnSave:disabled{
    opacity:.6;
    cursor:not-allowed;
}

.mt{
    margin-top: 14px;
    margin-bottom: 10px;
    font-weight: 900;
    color: rgba(255,255,255,.86);
}

.error-border{
    border-color: rgba(255, 90, 106, .55) !important;
}

.error-msg{
    display:block;
    margin-top: 6px;
    color: rgba(255, 90, 106, .95);
    font-weight: 800;
    font-size: 12px;
}
</style>
