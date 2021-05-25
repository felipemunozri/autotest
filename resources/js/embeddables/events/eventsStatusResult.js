import Vue from "vue";
import Chart from 'chart.js/auto';
import BaseOptions from './baseOptions';
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
            yLabel: "Modelos",
            xLabel: "Cantidad de eventos"
        },
        chart: null,
        status: [
            {
                code: null,
                label: 'No definido',
                color: 'rgba(158,158,158,1)'
            },
            {
                code: 'retrieved',
                label: 'Recuperado',
                color: 'rgba(0,133,207,1)'
            },
            {
                code: 'total-loss',
                label: 'Perdida Total',
                color: 'rgba(33,33,33 ,1)'
            },
            {
                code: 'retrieved-with-damage',
                label: 'Recuperado con daños',
                color: 'rgba(255,152,0,1)'
            },
            {
                code: 'not-retrieved',
                label: 'No Recuperado',
                color: 'rgba(244,67,54,1)'
            }
        ],
        viewType: null,
        columns: [
            {
                field: "label",
                label: "Estado"
            },
            {
                field: "total",
                label: "Cantidad de Eventos",
                centered: true,
                width: 150
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
            if(!chartRef) return;
            
            const labels = [];
            const data = [];
            const colors = [];

            this.events.forEach(event => {
                labels.push(event.label);
                data.push(event.total);
                colors.push(event.color);
            });

            const options = {...BaseOptions};

            options.plugins.legend.display = true;

            this.chart = new Chart(chartRef, {
                type: "doughnut",
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "N° de Eventos",
                            data: data,
                            backgroundColor: colors,
                            borderColor: "rgba(0,133,207,1)",
                            borderWidth: 0,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    ...options,
                    scales:{}
                }
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
            this.events = events.map(event => {
                const { color, label } = this.status.find(s => s.code == event.status);
                return {
                    ...event,
                    color,
                    label
                }
            });
            this.viewType = view_type;

        }
        this.initChart();
    }
}).$mount("#app");
