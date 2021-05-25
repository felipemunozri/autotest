import Vue from "vue";
import Chart from "chart.js/auto";
import BaseOptions from "./baseOptions";
import { Table } from "buefy";

import "bulma/css/bulma.css";
import "buefy/dist/buefy.css";

Vue.use(Table);

const template = `
<b-table
 v-if="viewType === 'table'"
 :mobile-cards="false"
 :striped="true"
 :narrowed="true"
 :data="events"
 :columns="columns">
</b-table>
<canvas ref="chart" style="position: relative" height="100%" width="100%" v-else></canvas>
`;

const _data = window.__DATA__;

const app = new Vue({
    template: template,
    data: {
        events: [],
        axiesLabels: {
            yLabel: "Cantidad de eventos",
            xLabel: "Fecha"
        },
        viewType: null,
        chart: null,
        columns: [
            {
                field: "date",
                label: "Fecha"
            },
            {
                field: "total",
                label: "Cantidad de Eventos",
                centered: true,
                width: 200
            }
        ]
    },
    watch: {
        chart(newValue, oldValue) {
            if (newValue && !oldValue) {
                window.addEventListener("afterprint", this.chartResize);
            }
        }
    },
    methods: {
        initChart() {
            const chartRef = this.$refs.chart;
            if (!chartRef) return;

            const labels = [];
            const data = [];

            this.events.forEach(event => {
                labels.push(event.date);
                data.push(event.total);
            });

            const options = { ...BaseOptions };

            options.scales.y.title.text = this.axiesLabels.yLabel;
            options.scales.x.title.text = this.axiesLabels.xLabel;

            this.chart = new Chart(chartRef, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "N° de Eventos",
                            data: data,
                            backgroundColor: "rgba(0,133,207,0.2)",
                            borderColor: "rgba(0,133,207,1)",
                            borderWidth: 4,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options
            });
        },
        chartResize() {
            if (this.chart) {
                this.chart.resize();
            }
        }
    },
    beforeDestroy() {
        window.removeEventListener("afterprint", this.chartResize);
    },
    mounted() {
        if (_data) {
            const { events, view_type } = _data;
            this.events = events;
            this.viewType = view_type;
        }
        this.initChart();
    }
}).$mount("#app");
