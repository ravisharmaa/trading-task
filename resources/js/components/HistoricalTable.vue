<script setup>
import {ref, defineEmits} from "vue";
import CandleBarChart from "./CandleBarChart.vue";
const emit = defineEmits([
    'on-view-another-clicked'
])
defineProps([
    'historicalData'
])
let viewInCharts = ref(false);
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
                            v-for="data in historicalData">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ new Date(data.date * 1000) }}
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
