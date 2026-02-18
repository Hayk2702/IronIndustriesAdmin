<template>
    <div class="navpage">
        <div class="navgrid">


            <!-- Example placeholders for future items (remove if you don't need) -->
            <!--
            <div class="navcard navcard--disabled">
              <div class="navcard__top">
                <div class="navcard__icon navcard__icon--gray"></div>
                <div class="navcard__kicker">{{ __('variable.coming_soon') }}</div>
              </div>
              <div class="navcard__body">
                <div class="navcard__title">{{ __('variable.reports') }}</div>
                <div class="navcard__desc">{{ __('variable.coming_soon_desc') }}</div>
              </div>
              <div class="navcard__footer">
                <span class="navcard__cta">{{ __('variable.locked') }}</span>
                <span class="navcard__arrow">🔒</span>
              </div>
            </div>
            -->
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2/dist/sweetalert2.js";
import "sweetalert2/src/sweetalert2.scss";
import Multiselect from "vue-multiselect";

export default {
    components: {
        Swal,
        Multiselect,
    },

    props: ["locale", "authuser", "roles"],

    watch: {
        locale(newVal) {
            this.lang = newVal;
        },
        authuser(newVal) {
            this.authUser = newVal;
        },
        roles(newVal) {
            this.role = newVal;
        },
    },

    data() {
        return {
            lang: this.locale,
            authUser: this.authuser,
            role: this.roles,
        };
    },

    methods: {
        getRout(name, param = "") {
            return getRout(name, param);
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

        isSuperAdmin() {
            let resp = false;
            (this.role || []).forEach((row) => {
                if (row.slug === "superadmin") resp = true;
            });
            return resp;
        },
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
/* ====== Modern dark/glass dashboard tiles ====== */
.navpage {
    --bg: #0b1020;
    --panel: rgba(255, 255, 255, 0.06);
    --panel2: rgba(255, 255, 255, 0.08);
    --border: rgba(255, 255, 255, 0.11);
    --text: rgba(255, 255, 255, 0.92);
    --muted: rgba(255, 255, 255, 0.62);
    --brand: #7c3aed; /* purple */
    --brand2: #06b6d4; /* cyan */
    --shadow: 0 22px 60px rgba(0, 0, 0, 0.45);
    --r: 18px;

    min-height: 100vh;
    padding: 22px;
    background: radial-gradient(1200px 800px at 10% 10%, rgba(124, 58, 237, 0.22), transparent 55%),
    radial-gradient(1000px 700px at 90% 20%, rgba(6, 182, 212, 0.16), transparent 60%),
    linear-gradient(180deg, var(--bg), #070a14 70%);
    color: var(--text);
}

.navpage__header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
    max-width: 1300px;
    margin: 0 auto 18px;
}

.navpage__title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.navpage__badge {
    width: 14px;
    height: 46px;
    border-radius: 999px;
    background: linear-gradient(180deg, var(--brand), var(--brand2));
    box-shadow: 0 12px 30px rgba(124, 58, 237, 0.35);
}

.navpage__h1 {
    margin: 0;
    font-size: 20px;
    font-weight: 900;
    letter-spacing: 0.2px;
}

.navpage__subtitle {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--muted);
}

.navpage__meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.navpage__chip {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.04);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #22c55e;
    box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
}

.chip_text {
    font-size: 12px;
    color: var(--muted);
}

.chip_text b {
    color: var(--text);
}

/* Grid */
.navgrid {
    max-width: 1300px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

/* Card */
.navcard {
    position: relative;
    overflow: hidden;
    display: block;
    text-decoration: none;
    color: var(--text);
    padding: 16px;
    border-radius: var(--r);
    border: 1px solid var(--border);
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.03));
    box-shadow: var(--shadow);
    transform: translateY(0);
    transition: transform 0.15s ease, border-color 0.15s ease, background 0.15s ease;
    min-height: 170px;
}

.navcard:hover {
    transform: translateY(-2px);
    border-color: rgba(124, 58, 237, 0.35);
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.035));
}

.navcard__glow {
    position: absolute;
    inset: -60px;
    background: radial-gradient(circle at 30% 20%, rgba(124, 58, 237, 0.20), transparent 55%),
    radial-gradient(circle at 80% 30%, rgba(6, 182, 212, 0.12), transparent 55%);
    opacity: 0;
    transition: opacity 0.2s ease;
    pointer-events: none;
}

.navcard:hover .navcard__glow {
    opacity: 1;
}

.navcard__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 14px;
    position: relative;
    z-index: 1;
}

.navcard__icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.35), rgba(6, 182, 212, 0.22));
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
}

.navcard__icon svg {
    width: 30px;
    height: 30px;
    filter: brightness(0) invert(1);
}

.navcard__kicker {
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.3px;
    color: rgba(255, 255, 255, 0.7);
    padding: 6px 10px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    background: rgba(255, 255, 255, 0.04);
}

.navcard__body {
    position: relative;
    z-index: 1;
}

.navcard__title {
    font-size: 15px;
    font-weight: 900;
    margin-bottom: 6px;
}

.navcard__desc {
    font-size: 12px;
    color: var(--muted);
    line-height: 1.5;
    max-width: 38ch;
}

.navcard__footer {
    position: absolute;
    left: 16px;
    right: 16px;
    bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 1;
}

.navcard__cta {
    font-size: 12px;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.85);
    padding: 8px 10px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    background: rgba(255, 255, 255, 0.04);
}

.navcard__arrow {
    font-size: 18px;
    opacity: 0.9;
}

/* Active state (router-link active-class="is-active") */
.navcard.is-active {
    border-color: rgba(6, 182, 212, 0.45);
    background: linear-gradient(180deg, rgba(6, 182, 212, 0.12), rgba(124, 58, 237, 0.08));
}

/* Responsive */
@media (max-width: 1200px) {
    .navgrid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .navpage {
        padding: 16px;
    }

    .navpage__header {
        flex-direction: column;
        align-items: flex-start;
    }

    .navgrid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 520px) {
    .navgrid {
        grid-template-columns: 1fr;
    }

    .navcard {
        min-height: 160px;
    }
}
</style>
