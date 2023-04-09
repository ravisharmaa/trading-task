<script setup>
import CompanyForm from "./CompanyForm.vue";
import HistoricalTable from "./HistoricalTable.vue";
import {ref} from "vue";
let showForm = ref(true);
let historicalData = ref([])
const setHistoricalData = (data) => {
    showForm.value = false;
    historicalData.value = data.data
}
</script>
<template>
    <div>
        <div v-if="showForm">
            <company-form @onDataReceived="setHistoricalData"></company-form>
        </div>
        <div v-else>
            <historical-table :historical-data="historicalData"></historical-table>
        </div>
    </div>
</template>

