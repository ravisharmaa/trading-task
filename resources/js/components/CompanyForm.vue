<script setup>
import {reactive, ref, defineEmits} from "vue";
import debounce from 'lodash.debounce'
import ApRequest from "../api/api";
import VueMultiselect from "vue-multiselect";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {formatDate} from "../utils/utilities";
const emit = defineEmits(['on-data-received']);
// properties
let formObject = ref({
    companyName: '',
    startDate: new Date(),
    endDate: new Date(),
    email: ''
})
let formErrors = ref([]);
let symbolsSelectOptions = reactive([]);
let apiRequest = new ApRequest();
let isSearchLoading = ref(false);
const sendApiRequest = debounce(async (newName) => {
    try {
        if (newName.length >= 2) {
            isSearchLoading.value = true
            let data = await apiRequest.getCompanySymbols(newName)
            data.data.map(innerData => {
                symbolsSelectOptions.push({
                    id: innerData.id,
                    name: innerData.symbol
                })
            })
            isSearchLoading.value = false;
        }
    } catch (error) {
        isSearchLoading.value = false;
        symbolsSelectOptions = [];
    }
}, 1000)

const submitForm = async () => {
    let value = formObject.value;
    const formData = {
        'company_symbol' :value.companyName?.name,
        'start_date': formatDate(value.startDate),
        'end_date': formatDate(value.endDate),
        'email': value.email
    }
    try {
        let data = await apiRequest.getHistoricalData(formData)
        if (data.status === 200 && data.data.length > 0) {
            emit('on-data-received', data);
        }
    } catch (error) {
        if (error.response.status === 422) {
            formErrors.value = error.response?.data?.errors
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue3-multiselect.css"></style>
<template>
    <div>
        <div class="flex items-center justify-center h-screen bg-gray-300">
            <form class="bg-white shadow-md rounded px-20 pt-6 pb-8 mb-4 max-w-lg"  @submit.prevent="submitForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="company_label">
                        Company Label
                    </label>
                    <VueMultiselect
                        v-model="formObject.companyName"
                        :options="symbolsSelectOptions"
                        :searchable="true"
                        @search-change="sendApiRequest"
                        :preserveSearch="true"
                        placeholder="Type 3 characters to search"
                        label="name"
                        track-by="code"
                        :loading="isSearchLoading"
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
                    <VueDatePicker
                        v-model="formObject.startDate"
                        :enable-time-picker="false"
                        :format="formatDate"
                        :clearable="false"
                        :month-change-on-scroll="false"
                        auto-apply no-swipe/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        End Date
                    </label>
                    <VueDatePicker v-model="formObject.endDate" :enable-time-picker="false" :format="formatDate" :clearable="false" auto-apply no-swipe />
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
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

