<script setup>
import {reactive} from "vue";
import debounce from 'lodash.debounce'
import ApRequest from "../api/api";
import VueMultiselect from "vue-multiselect";

let formObject = reactive({
    companyName: '',
    startDate: '',
    endDate: '',
    email: ''
})
let symbolsSelectOptions = reactive([]);
let apiRequest = new ApRequest();
let searchable = true
const sendApiRequest = debounce(async (newName) => {
    try {
        if (newName.length >= 2) {
            let data = await apiRequest.getCompanySymbols(newName)
            data.data.map(innerData => {
                symbolsSelectOptions.push({
                    id: innerData.id,
                    name: innerData.symbol
                })
            })
        }
    } catch (error) {
        symbolsSelectOptions = [];
    }
}, 1000)

const submitForm = async (formObject) => {
    const data = {
        'company_symbol' : formObject.companyName.name,
        'start_date': formObject.startDate,
        'end_date': formObject.endDate,
        'email': formObject.email
    }
    await apiRequest.getHistoricalData(data)
}

</script>
<style src="vue-multiselect/dist/vue3-multiselect.css"></style>
<template>
    <div>
        <div class="flex items-center justify-center h-screen bg-gray-200" @submit.prevent="submitForm">
            <form class="bg-white shadow-md rounded px-20 pt-6 pb-8 mb-4 max-w-lg">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="company_label">
                        Company Label
                    </label>
                    <VueMultiselect
                        v-model="formObject.companyName"
                        :options="symbolsSelectOptions"
                        :searchable="searchable"
                        @search-change="sendApiRequest"
                        :preserveSearch="true"
                        placeholder="Type to search"
                        label="name"
                        track-by="code"
                    >
                        <template #noResult>
                            Oops! No elements found
                        </template>
                    </VueMultiselect>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                        Start Date
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="start_date"
                           v-model="formObject.startDate"
                           type="date"
                           placeholder="Start Date">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        End Date
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="end_date"
                           v-model="formObject.endDate"
                           type="date"
                           placeholder="End Date">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email"
                           v-model="formObject.email"
                           type="email"
                           placeholder="Email">
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

