<template>
    <div>
        <apexchart type="candlestick" height="350" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
export default {
    props: ['chartData'],
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'candlestick',
                    height: 350
                },
                xaxis: {
                    type: 'datetime'
                },
                yaxis: {
                    tooltip: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Candle chart',
                data: []
            }]
        };
    },

    beforeMount() {
        let formatted = [];
        this.chartData.forEach(data => {
            formatted.push({
                x: new Date(data.date * 1000),
                y: [data.open, data.high, data.low, data.close]
            })
        })
        this.series[0].data = formatted;
    }
}
</script>

<style scoped>

</style>
