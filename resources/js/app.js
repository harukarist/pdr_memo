/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from "vue-router";
import DoCreateComponent from "./components/DoCreateComponent";
import HeaderComponent from "./components/HeaderComponent";
import MypageComponent from "./components/MypageComponent";
import PrepCreateComponent from "./components/PrepCreateComponent";
import PrepCreateComponent2 from "./components/PrepCreateComponent2";
import PrepEditComponent from "./components/PrepEditComponent";
import RecordListComponent from "./components/RecordListComponent";
import ReviewCreateComponent from "./components/ReviewCreateComponent";
import ReviewEditComponent from "./components/ReviewEditComponent";
import TaskListComponent from "./components/TaskListComponent";

window.Vue = require('vue');
Vue.use(VueRouter);

// app.blade.phpにrouter-viewコンポーネントを配置
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'mypage',
            component: MypageComponent
        },
        {
            path: '/mypage',
            name: 'mypage',
            component: MypageComponent
        },
        {
            path: '/records/do/create',
            name: 'do.create',
            component: DoCreateComponent
        },
        {
            path: '/records/prep/create',
            name: 'prep.create',
            component: PrepCreateComponent
        },
        {
            path: '/records/prep/create2',
            name: 'prep.create2',
            component: PrepCreateComponent2
        },
        {
            path: '/records/prep/:recordId/edit',
            name: 'prep.edit',
            component: PrepEditComponent,
            props: true
        },
        {
            path: '/records',
            name: 'record.list',
            component: RecordListComponent
        },
        {
            path: '/records/review/create',
            name: 'review.create',
            component: ReviewCreateComponent
        },
        {
            path: '/records/review/:recordId/edit',
            name: 'review.edit',
            component: ReviewEditComponent,
            props: true
        },
        {
            path: '/tasks',
            name: 'task.list',
            component: TaskListComponent
        },
    ]
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('header-component', HeaderComponent);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router
});
