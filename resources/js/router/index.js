import Vue from 'vue'
import VueRouter from 'vue-router'
import ExampleComponent from '../components/DashboardComponent.vue'
import UserComponent from "../components/UserComponent";
import AboutCompanyComponent from "../components/AboutCompanyComponent.vue";
import AboutUsComponent from "../components/AboutUsComponent.vue";
import ServicesComponent from "../components/ServicesComponent.vue";
import ProductsComponent from "../components/ProductsComponent.vue";
import CategoriesComponent from "../components/CategoriesComponent.vue";
import PricesComponent from "../components/PricesComponent.vue";
import PreordersComponent from "../components/PreordersComponent.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: '/:lang/dashboard',
        name: 'Dashboard',
        component: ExampleComponent
    },
    {
        path: '/:lang/dashboard/users',
        name: 'Users',
        component: UserComponent
    },
    {
        path: '/:lang/dashboard/about-company',
        name: 'AboutCompany',
        component: AboutCompanyComponent
    },
    {
        path: '/:lang/dashboard/about-us',
        name: 'AboutUs',
        component: AboutUsComponent
    },
    {
        path: '/:lang/dashboard/services',
        name: 'Services',
        component: ServicesComponent
    },
    {
        path: '/:lang/dashboard/categories',
        name: 'Categories',
        component: CategoriesComponent
    },
    {
        path: '/:lang/dashboard/products',
        name: 'Products',
        component: ProductsComponent
    },
    {
        path: '/:lang/dashboard/prices',
        name: 'Prices',
        component: PricesComponent
    },
    {
        path: '/:lang/dashboard/preorders',
        name: 'Preorders',
        component: PreordersComponent
    },

];
Vue.config.devtools = true;
const router = new VueRouter({
    mode: 'history',
    // linkExactActiveClass: "active",
    linkActiveClass: "linkActiveClass",
    // base: process.env.BASE_URL,
    routes
});

export default router
