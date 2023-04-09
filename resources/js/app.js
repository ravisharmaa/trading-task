import './bootstrap';
import {createApp} from "vue";
import Welcome from "./components/Welcome.vue";
import VueApexCharts from 'vue3-apexcharts'
const app = createApp(Welcome)
app.use(VueApexCharts)
app.mount("#app")
