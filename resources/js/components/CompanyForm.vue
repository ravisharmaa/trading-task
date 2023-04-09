<script setup>
import {reactive, ref, defineEmits} from "vue";
import debounce from 'lodash.debounce'
import ApiRequest from "../api/api";
import VueMultiselect from "vue-multiselect";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {formatDate} from "../utils/utilities";
import useVuelidate from "@vuelidate/core";
import {required, email} from "@vuelidate/validators";
import Loading from "vue-loading-overlay"
import 'vue-loading-overlay/dist/css/index.css';
const emit = defineEmits(['on-data-received']);

let formObject = ref({
    companyName: '',
    startDate: new Date(),
    endDate: new Date(),
    email: ''
})
const validationRules = {
    companyName: {required},
    startDate: {required},
    endDate: {required},
    email: {required, email}
}

const v$ = useVuelidate(validationRules, formObject);
let formErrors = ref([]);
let symbolsSelectOptions = reactive([]);
let apiRequest = new ApiRequest();
let isSearchLoading = ref(false);
let visible = ref(false);
let showSimpleToast = ref(false);
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

const showToast = () => {
    showSimpleToast.value = true;
    setTimeout(fn => {
        showSimpleToast.value = false
    }, 3000)
}
const submitForm = async () => {
    const result = await v$.value.$validate();
    if (result) {
        let value = formObject.value;
        const formData = {
            'company_symbol' :value.companyName?.name,
            'start_date': formatDate(value.startDate),
            'end_date': formatDate(value.endDate),
            'email': value.email
        }
        try {
            visible.value = true;
            let data = await apiRequest.getHistoricalData(formData)
            if (data.status === 200 && data.data.data.length > 0) {
                emit('on-data-received', data.data);
            } else {
                formObject.value.email = '';
                formObject.value.companyName = ''
                v$.value.$reset()
                visible.value = false;
                showToast()
            }

        } catch (error) {
            debugger;
            if (error.response.status === 422) {
                visible.value = false;
                formErrors.value = error.response?.data?.errors
            }
        }
    }
}
</script>
<style src="vue-multiselect/dist/vue3-multiselect.css"></style>
<template>
    <div class="relative">
        <loading v-model:active="visible" :can-cancel="false" :height="40" :is-full-page="false"></loading>
        <button class="absolute top-0 right-0 mr-4 mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" v-if="showSimpleToast" type="button">
           Oops!! No data available
        </button>
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
                    <div class="mt-1 text-red-500 text-sm" v-for="error in v$.companyName.$errors">
                        {{error.$message}}
                    </div>
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
                    <div class="mt-1 text-red-500 text-sm" v-for="error in v$.startDate.$errors">
                        {{error.$message}}
                    </div>
                    <div class="mt-1 text-red-500 text-sm" v-if="formErrors?.start_date">
                        {{formErrors?.start_date[0]}}
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                        End Date
                    </label>
                    <VueDatePicker v-model="formObject.endDate" :enable-time-picker="false" :format="formatDate" :clearable="false" auto-apply no-swipe />
                    <div class="mt-1 text-red-500 text-sm" v-for="error in v$.endDate.$errors">
                        {{error.$message}}
                    </div>
                    <div class="mt-1 text-red-500 text-sm" v-if="formErrors?.end_date">
                        {{formErrors?.end_date[0]}}
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        Email
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                            focus:outline-none focus:shadow-outline"
                           id="email"
                           v-model="formObject.email"
                           type="email"
                           placeholder="Email">
                    <div class="mt-1 text-red-500 text-sm" v-for="error in v$.email.$errors">
                        {{error.$message}}
                    </div>
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

