import './bootstrap';
import {createApp} from "vue";
import VueApexCharts from 'vue3-apexcharts'
import Notifications from '@kyvg/vue3-notification'
import Welcome from "./components/Welcome.vue";

const app = createApp(Welcome)
app.use(VueApexCharts)
app.use(Notifications)
app.mount("#app")
