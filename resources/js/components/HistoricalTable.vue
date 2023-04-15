<script setup>
import {ref, defineEmits, computed, reactive} from "vue";
import CandleBarChart from "./CandleBarChart.vue";
import {formatDate, formatTimeStamp} from "../utils/utilities";
const emit = defineEmits([
    'on-view-another-clicked'
])
const props = defineProps([
    'historicalData'
])
let viewInCharts = ref(false);
let localData = ref(props.historicalData);
const formattedData = computed(() => {
   return localData.value.map(data => {
       data.date = formatTimeStamp(data.date)
       data.open = Math.round(data.open)
       data.high = Math.round(data.high)
       data.close = Math.round(data.close)
       data.low = Math.round(data.low)
       return data
   })
})
</script>
<template>
    <div>
        <div class="h-screen p10">
            <div class="relative overflow-x-auto border rounded-lg border-gray-300 p-10">
                <div class="flex justify-end space-x-4 mb-4">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none"
                            @click="viewInCharts = !viewInCharts">
                        {{ viewInCharts ? 'View In Table' : 'View in Charts' }}
                    </button>
                    <!-- View Another Button with margin -->
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded focus:outline-none ml-4" @click="emit('on-view-another-clicked')">
                        View Another
                    </button>
                </div>
                <div v-if="!viewInCharts">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-100 dark:text-gray-400 rounded-t-lg">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Open
                            </th>
                            <th scope="col" class="px-6 py-3">
                                High
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Low
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Close
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Volume
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"
                            v-for="data in formattedData">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ data.date}}
                            </th>
                            <td class="px-6 py-4">
                                {{ data.open }}
                            </td>
                            <td class="px-6 py-4">
                                {{ data.high }}
                            </td>
                            <td class="px-6 py-4">
                                {{ data.low }}
                            </td>
                            <td class="px-6 py-4">
                                {{ data.close }}
                            </td>
                            <th scope="col" class="px-6 py-3">
                                {{ data.volume }}
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <candle-bar-chart :chart-data="historicalData"></candle-bar-chart>
                </div>
            </div>
        </div>
    </div>
</template>
