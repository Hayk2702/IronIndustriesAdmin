<template>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar__top">
                <router-link class="brand" :to="`/${lang}/dashboard`">
                    <img class="brand__logo" src="/img/favicon.png" alt="logo"/>
                    <span class="brand__text">{{ __('variable.app_name') }}</span>
                </router-link>

                <button class="icon_btn d-lg-none sidebar_close_icon" type="button" aria-label="Close sidebar">
                    <i class="ti-close"></i>
                </button>
            </div>

            <div class="sidebar__nav">
                <ul id="sidebar_menu" class="menu metismenu">
                    <li v-if="can('showusers')">
                        <router-link class="menu__item" :to="`/${lang}/dashboard/users`">
                            <span class="menu__icon">
                <!-- keep your SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 832 1024">
                                    <path fill="currentColor"
                                          d="M773 832q-24 52-73 102t-109 90H241q-56-37-103.5-88T64 832Q0 704 0 320q0-35 11-96.5T32 128l160-96Q291 0 419 0q121 0 221 32l160 96q10 34 21 95.5t11 96.5q0 384-59 512zM271 960h294q88-61 139-160l-64 32h-64l-48-64H304l-48 64h-64l-64-32q43 90 143 160zm497-768L606 92q-33-11-65-17l-29 117q-7 27-23 45.5T448 256h-64q-24 0-40.5-19.5T320 192L291 76q-30 6-60 16L64 192v224q0 55 16 137.5T96 672q0 24 6 51l125 62l61-81h256l61 81l125-62q6-28 6-51q0-34 16-118.5T768 416V192zM512 416l192-32v64l-192 64v-96zm-384-32l192 32v96l-192-64v-64z"/>
                                </svg>
                            </span>
                            <span class="menu__title">{{ __('variable.user') }}</span>
                        </router-link>
                    </li>

                    <li v-if="can('showaboutcompany')">
                        <router-link class="menu__item" :to="`/${lang}/dashboard/about-company`">
                            <span class="menu__icon">
                <!-- keep your SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 832 1024">
                                    <path fill="currentColor"
                                          d="M773 832q-24 52-73 102t-109 90H241q-56-37-103.5-88T64 832Q0 704 0 320q0-35 11-96.5T32 128l160-96Q291 0 419 0q121 0 221 32l160 96q10 34 21 95.5t11 96.5q0 384-59 512zM271 960h294q88-61 139-160l-64 32h-64l-48-64H304l-48 64h-64l-64-32q43 90 143 160zm497-768L606 92q-33-11-65-17l-29 117q-7 27-23 45.5T448 256h-64q-24 0-40.5-19.5T320 192L291 76q-30 6-60 16L64 192v224q0 55 16 137.5T96 672q0 24 6 51l125 62l61-81h256l61 81l125-62q6-28 6-51q0-34 16-118.5T768 416V192zM512 416l192-32v64l-192 64v-96zm-384-32l192 32v96l-192-64v-64z"/>
                                </svg>
                            </span>
                            <span class="menu__title">{{ __('variable.about_company') }}</span>
                        </router-link>
                    </li>
                    <li v-if="can('showaboutus')">
                        <router-link class="menu__item" :to="`/${lang}/dashboard/about-us`">
                            <span class="menu__icon">
                <!-- keep your SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 832 1024">
                                    <path fill="currentColor"
                                          d="M773 832q-24 52-73 102t-109 90H241q-56-37-103.5-88T64 832Q0 704 0 320q0-35 11-96.5T32 128l160-96Q291 0 419 0q121 0 221 32l160 96q10 34 21 95.5t11 96.5q0 384-59 512zM271 960h294q88-61 139-160l-64 32h-64l-48-64H304l-48 64h-64l-64-32q43 90 143 160zm497-768L606 92q-33-11-65-17l-29 117q-7 27-23 45.5T448 256h-64q-24 0-40.5-19.5T320 192L291 76q-30 6-60 16L64 192v224q0 55 16 137.5T96 672q0 24 6 51l125 62l61-81h256l61 81l125-62q6-28 6-51q0-34 16-118.5T768 416V192zM512 416l192-32v64l-192 64v-96zm-384-32l192 32v96l-192-64v-64z"/>
                                </svg>
                            </span>
                            <span class="menu__title">{{ __('variable.about_us') }}</span>
                        </router-link>
                    </li>
                    <li v-if="can('showservices')">
                        <router-link class="menu__item" :to="`/${lang}/dashboard/services`">
                            <span class="menu__icon">
                <!-- keep your SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 832 1024">
                                    <path fill="currentColor"
                                          d="M773 832q-24 52-73 102t-109 90H241q-56-37-103.5-88T64 832Q0 704 0 320q0-35 11-96.5T32 128l160-96Q291 0 419 0q121 0 221 32l160 96q10 34 21 95.5t11 96.5q0 384-59 512zM271 960h294q88-61 139-160l-64 32h-64l-48-64H304l-48 64h-64l-64-32q43 90 143 160zm497-768L606 92q-33-11-65-17l-29 117q-7 27-23 45.5T448 256h-64q-24 0-40.5-19.5T320 192L291 76q-30 6-60 16L64 192v224q0 55 16 137.5T96 672q0 24 6 51l125 62l61-81h256l61 81l125-62q6-28 6-51q0-34 16-118.5T768 416V192zM512 416l192-32v64l-192 64v-96zm-384-32l192 32v96l-192-64v-64z"/>
                                </svg>
                            </span>
                            <span class="menu__title">{{ __('variable.services') }}</span>
                        </router-link>
                    </li>
                    <li v-if="can('showproducts')">
                        <router-link class="menu__item" :to="`/${lang}/dashboard/products`">
                            <span class="menu__icon">
                <!-- keep your SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 832 1024">
                                    <path fill="currentColor"
                                          d="M773 832q-24 52-73 102t-109 90H241q-56-37-103.5-88T64 832Q0 704 0 320q0-35 11-96.5T32 128l160-96Q291 0 419 0q121 0 221 32l160 96q10 34 21 95.5t11 96.5q0 384-59 512zM271 960h294q88-61 139-160l-64 32h-64l-48-64H304l-48 64h-64l-64-32q43 90 143 160zm497-768L606 92q-33-11-65-17l-29 117q-7 27-23 45.5T448 256h-64q-24 0-40.5-19.5T320 192L291 76q-30 6-60 16L64 192v224q0 55 16 137.5T96 672q0 24 6 51l125 62l61-81h256l61 81l125-62q6-28 6-51q0-34 16-118.5T768 416V192zM512 416l192-32v64l-192 64v-96zm-384-32l192 32v96l-192-64v-64z"/>
                                </svg>
                            </span>
                            <span class="menu__title">{{ __('variable.products') }}</span>
                        </router-link>
                    </li>
                </ul>
            </div>

            <div class="sidebar__bottom">
                <div class="mini_profile">
                    <div class="mini_profile__avatar">{{ (authUser?.name || 'U').slice(0, 1).toUpperCase() }}</div>
                    <div class="mini_profile__meta">
                        <div class="mini_profile__name">{{ authUser['name'] }}</div>
                        <button class="link_btn" type="button" @click.prevent="logout">
                            {{ __(('variable.exit')) }}
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <main class="main">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar__left">
                    <button class="icon_btn d-lg-none" type="button" aria-label="Open menu">
                        <i class="ti-menu"></i>
                    </button>

                    <!--                    <button class="icon_btn d-none d-lg-inline-flex open_miniSide" type="button" aria-label="Toggle sidebar">-->
                    <!--                        <img :src="'/img/menu.png'" style="width: 22px; height: 22px" alt="menu" />-->
                    <!--                    </button>-->

                    <div v-if="currentRouteName == 'Dashboard'" class="langs">
                        <button class="lang_btn" type="button" @click="setLanguage('am')" aria-label="Հայերեն">
                            <img :src="'/img/am.png'" alt="am"/>
                        </button>
                        <button class="lang_btn" type="button" @click="setLanguage('ru')" aria-label="Русский">
                            <img :src="'/img/ru.png'" alt="ru"/>
                        </button>
                        <button class="lang_btn" type="button" @click="setLanguage('en')" aria-label="English">
                            <img :src="'/img/en.png'" alt="en"/>
                        </button>
                    </div>
                </div>

                <div class="topbar__center">
                    <span class="app_title">{{ __('variable.app_name') }}</span>
                </div>

                <!--
                                <div class="topbar__right">
                                    <div class="profile">
                                        <div class="profile__avatar">{{ (authUser?.name || 'U').slice(0, 1).toUpperCase() }}</div>
                                        <div class="profile__name">{{ authUser['name'] }}</div>

                                        <div class="profile__menu">
                                            <button class="profile__menu_btn" type="button">
                                                <i class="ti-angle-down"></i>
                                            </button>

                                            <div class="profile__dropdown">
                                                <div class="profile__dropdown_name">{{ authUser['name'] }}</div>
                                                <button class="danger_btn" type="button" @click.prevent="logout">
                                                    {{ __(('variable.exit')) }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                -->
            </header>

            <!-- Content -->
            <section class="content">
                <div class="content__inner">
                    <router-view :locale="lang" :authuser="authUser" :roles="role"/>
                </div>
            </section>

            <!-- Footer -->
            <footer class="footer">
                <div class="footer__inner">
                    <p>2026 © <span class="footer__brand"></span></p>
                </div>
            </footer>
        </main>
    </div>
</template>

<script>
import axios from "axios";
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
        $route() {
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

        logout() {
            let self = this;
            axios
                .post(route("logout", {locale: self.lang}))
                .then(() => {
                    this.$router.go("Dashboard");
                })
                .catch((error) => {
                    this.showCatchError(error);
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

        isSuperAdmin() {
            let resp = false;
            (this.role || []).forEach((row) => {
                if (row.slug === "superadmin") resp = true;
            });
            return resp;
        },

        setLanguage(language) {
            let self = this;
            axios
                .post(route("setLocale", {locale: this.lang, lang: language}))
                .then((data) => {
                    if (data && data.data && data.data.isSuccess) {
                        window.location.href = data.data.redirectUrl;
                    }
                })
                .catch((err) => {
                    self.showCatchError(err);
                });
        },
    },

    computed: {
        currentRouteName() {
            return this.$route.name;
        },
    },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
/* ---------- Theme (modern dark + glass) ---------- */

.sidebar #sidebar_menu > li > a {
    position: relative;
    overflow: hidden;
    text-decoration: none;
    color: var(--text);
    border-radius: 10px;
    border: 1px solid var(--border);
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.03));
    box-shadow: var(--shadow);
    transform: translateY(0);
    transition: transform 0.15s ease, border-color 0.15s ease, background 0.15s ease;
}

.layout {
    --bg: #0b1020;
    --bg2: #0e1630;
    --panel: rgba(255, 255, 255, 0.06);
    --panel2: rgba(255, 255, 255, 0.08);
    --border: rgba(255, 255, 255, 0.10);
    --text: rgba(255, 255, 255, 0.92);
    --muted: rgba(255, 255, 255, 0.65);
    --brand: #7c3aed; /* purple */
    --brand2: #06b6d4; /* cyan */
    --danger: #ff5a6a;
    --shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
    --radius: 16px;

    min-height: 100vh;
    display: grid;
    grid-template-columns: 280px 1fr;
    background: radial-gradient(1200px 800px at 10% 10%, rgba(124, 58, 237, 0.24), transparent 50%),
    radial-gradient(1000px 800px at 90% 20%, rgba(6, 182, 212, 0.18), transparent 55%),
    linear-gradient(180deg, var(--bg), #070a14 70%);
    color: var(--text);
}

/* ---------- Sidebar ---------- */
.sidebar {
    position: sticky;
    top: 0;
    height: 100vh;
    padding: 16px;
    border-right: 1px solid var(--border);
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.sidebar__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 10px;
    border: 1px solid var(--border);
    background: var(--panel);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--text);
}

.brand__logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.35));
}

.brand__text {
    font-weight: 800;
    letter-spacing: 0.2px;
    font-size: 14px;
    opacity: 0.95;
}

.sidebar__nav {
    flex: 1;
    overflow: auto;
    padding-right: 4px;
}

.sidebar__nav::-webkit-scrollbar {
    width: 6px;
}

.sidebar__nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.12);
    border-radius: 99px;
}

.menu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 10px;
}

.menu__item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 12px;
    border-radius: 14px;
    border: 1px solid transparent;
    background: rgba(255, 255, 255, 0.04);
    text-decoration: none;
    color: var(--text);
    transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
}

.menu__item:hover {
    background: rgba(255, 255, 255, 0.07);
    border-color: rgba(124, 58, 237, 0.35);
    transform: translateY(-1px);
}

.menu__icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.22), rgba(6, 182, 212, 0.16));
    border: 1px solid rgba(255, 255, 255, 0.10);
}

.menu__icon svg {
    color: rgba(255, 255, 255, 0.92);
}

.menu__title {
    font-weight: 700;
    letter-spacing: 0.2px;
    font-size: 14px;
}

.sidebar__bottom {
    border: 1px solid var(--border);
    background: var(--panel);
    border-radius: var(--radius);
    padding: 12px;
}

.mini_profile {
    display: flex;
    align-items: center;
    gap: 10px;
}

.mini_profile__avatar {
    width: 38px;
    height: 38px;
    border-radius: 999px;
    display: grid;
    place-items: center;
    font-weight: 800;
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.45), rgba(6, 182, 212, 0.35));
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.mini_profile__meta {
    display: grid;
    gap: 2px;
}

.mini_profile__name {
    font-weight: 700;
    font-size: 13px;
}

.link_btn {
    background: transparent;
    border: none;
    padding: 0;
    margin: 0;
    color: var(--muted);
    font-weight: 600;
    font-size: 12px;
    cursor: pointer;
}

.link_btn:hover {
    color: #fff;
    text-decoration: underline;
}

/* ---------- Main ---------- */
.main {
    min-height: 100vh;
    display: grid;
    grid-template-rows: auto 1fr auto;
}

/* ---------- Topbar ---------- */
.topbar {
    position: sticky;
    top: 0;
    z-index: 5;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border);
    background: rgba(11, 16, 32, 0.65);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 12px;
}

.topbar__left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.topbar__center {
    display: flex;
    justify-content: center;
}

.app_title {
    font-weight: 900;
    letter-spacing: 0.35px;
    padding: 8px 14px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.04);
}

.topbar__right {
    display: flex;
    justify-content: flex-end;
}

.icon_btn {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.04);
    color: var(--text);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.15s ease, background 0.15s ease;
}

.icon_btn:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-1px);
}

/* Languages */
.langs {
    display: inline-flex;
    gap: 8px;
    padding: 6px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.03);
}

.lang_btn {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(255, 255, 255, 0.04);
    display: grid;
    place-items: center;
    cursor: pointer;
    transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease;
}

.lang_btn:hover {
    transform: translateY(-1px);
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(6, 182, 212, 0.35);
}

.lang_btn img {
    width: 18px;
    height: 18px;
}

/* Profile */
.profile {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 999px;
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.04);
}

.profile__avatar {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    display: grid;
    place-items: center;
    font-weight: 900;
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.45), rgba(6, 182, 212, 0.35));
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.profile__name {
    font-weight: 700;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
}

.profile__menu {
    position: relative;
    display: inline-flex;
    align-items: center;
}

.profile__menu_btn {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    background: rgba(255, 255, 255, 0.04);
    color: rgba(255, 255, 255, 0.9);
    cursor: pointer;
}

/* simple hover dropdown without extra JS */
.profile__dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    min-width: 220px;
    padding: 10px;
    border-radius: 16px;
    border: 1px solid var(--border);
    background: rgba(15, 22, 48, 0.92);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow: var(--shadow);
    opacity: 0;
    transform: translateY(-6px);
    pointer-events: none;
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.profile__menu:hover .profile__dropdown {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.profile__dropdown_name {
    font-weight: 800;
    font-size: 13px;
    padding: 6px 8px 10px;
    color: rgba(255, 255, 255, 0.92);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    margin-bottom: 10px;
}

.danger_btn {
    width: 100%;
    border: 1px solid rgba(255, 90, 106, 0.35);
    background: rgba(255, 90, 106, 0.10);
    color: rgba(255, 255, 255, 0.92);
    padding: 10px 12px;
    border-radius: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: transform 0.15s ease, background 0.15s ease;
}

.danger_btn:hover {
    background: rgba(255, 90, 106, 0.18);
    transform: translateY(-1px);
}

/* ---------- Content ---------- */
.content {
    padding: 18px;
}

.content__inner {
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 16px;
    min-height: calc(100vh - 160px);
}

/* ---------- Footer ---------- */
.footer {
    padding: 14px 18px 18px;
}

.footer__inner {
    border: 1px solid var(--border);
    background: rgba(255, 255, 255, 0.03);
    border-radius: var(--radius);
    padding: 12px 14px;
    text-align: center;
    color: var(--muted);
}

.footer__brand {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 800;
}

/* ---------- Responsive ---------- */
@media (max-width: 992px) {
    .layout {
        grid-template-columns: 1fr;
    }

    .sidebar {
        position: relative;
        height: auto;
        border-right: none;
        border-bottom: 1px solid var(--border);
    }

    .content__inner {
        min-height: auto;
    }
}

/* Keep compatibility with your existing classes (optional) */
.nav_div,
.languages_div,
.language_a {
    display: none; /* replaced by new topbar layout */
}
</style>
