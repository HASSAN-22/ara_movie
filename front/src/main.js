import { createApp } from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import './assets/tailwind.css'
import './plugins/axios'
import tooltip from "./directives/tooltip.js";
import "@/assets/css/tooltip/tooltip.css";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import { createHead } from '@vueuse/head'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';
import Vue3PersianDatetimePicker from 'vue3-persian-datetime-picker'
import vueDebounce from 'vue-debounce'


const head = createHead()
const debounceConfig = {
    lock: false,
    listenTo: 'keydown',
    defaultTime: '300ms',
    fireOnEmpty: false,
    trim: false
}
const DatePickerConfig ={
    name: 'CustomDatePicker',
    props: {
        format: 'YYYY-MM-DD',
        displayFormat: 'jYYYY-jMM-jDD',
        editable: false,
        inputClass: 'form-control my-custom-class-name text-sm placeholder-gray-500 w-full pl-10 pr-4 py-2 border-b-2 border-yellow-400 outline-none focus:border-green-400',
        placeholder: 'لطفا یک تاریخ انتخاب کنید',
        altFormat: 'YYYY-MM-DD HH:mm',
        color: '#00acc1',
        autoSubmit: false,
        //...
        //... And whatever you want to set as default.
        //...
    }
}

import './assets/import'
import './registerServiceWorker'
const app = createApp(App)

app.directive("tooltip", tooltip);
app.component('DatePicker', Vue3PersianDatetimePicker)
app.component('QuillEditor', QuillEditor)

app
.use(router)
.use(store)
.use(head)
.use(VueSweetalert2)
.use(vueDebounce, debounceConfig)
.use(Vue3PersianDatetimePicker,DatePickerConfig)
.mount('#app')
