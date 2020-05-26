require('./bootstrap');

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

window.Vue = require('vue');

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

Vue.component('tasks-dashboard', require('./components/dashboard/TasksView.vue').default);
Vue.component('project-selection', require('./components/common/ProjectSelectionFilter.vue').default);


//------- CONTENT -------//
const app = new Vue({
    el: '#app',
}).$mount();
