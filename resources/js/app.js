import './bootstrap';
import {createApp} from "vue";
import VueApexCharts from 'vue3-apexcharts'
import Welcome from "./components/Welcome.vue";

const app = createApp(Welcome)
app.use(VueApexCharts)
app.mount("#app")
