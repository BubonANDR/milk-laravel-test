/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
import { createApp } from "vue";
import { createRouter, createWebHistory} from "vue-router";

import App from "./components/App.vue";
import MilkersList from "./components/MilkersList.vue";




const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: "/", component: MilkersList },
        //по заданию все элементы находятся на одной странице
    
    ],
});

const app = createApp(App);
app.use(router);
app.mount("#app");
