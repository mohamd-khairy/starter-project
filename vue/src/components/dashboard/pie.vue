<template>
  <div v-if="showChart">
    <apexchart
      width="480"
      type="pie"
      :options="chartOptions"
      :series="series"
    ></apexchart>
  </div>
</template>

<script>
export default {
  props: {
    series: Array,
    labels: Array
  },
  data() {
    return {
      showChart: false,
      // series: [44, 0],
      chartOptions: {
        chart: {
          width: 380,
          type: "pie"
        },
        noData: {
          text: this.$t("general.no_data_available"),
          align: 'center',
          verticalAlign: 'middle',
          offsetX: 0,
          offsetY: 0,
          style: {
            color: this.$vuetify.theme.themes.light.primary,
            fontSize: '20px',
            fontFamily: undefined
          }
        },
        labels: this.labels,
        theme: {
          monochrome: {
            enabled: true,
            color: this.$vuetify.theme.themes.light.primary,
            shadeTo: "light",
            shadeIntensity: 0.8
          }
        },
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200
              },
              legend: {
                position: "bottom"
              }
            }
          }
        ]
      }
      // chartOptions: {
      //   chart: {
      //     width: 380,
      //     type: 'polarArea'
      //   },
      //   // colors: ['#0672de', '#2a8cf0', '#5b66e2', '#6771dc', '#4e57b7'],
      //   // labels: [this.$t('general.work'), this.$t('general.leakage'), this.$t('general.crack'), this.$t('general.chemicalLeakage'),
      //   //   this.$t('general.fires'), this.$t('general.intrudersAndVandals')
      //   // ],
      //   labels:this.labels,
      //   fill: {
      //     opacity: 1
      //   },
      //   stroke: {
      //     width: 1,
      //     colors: undefined
      //   },
      //   yaxis: {
      //     show: false
      //   },
      //   legend: {
      //     position: 'bottom'
      //   },
      //   plotOptions: {
      //     polarArea: {
      //       rings: {
      //         strokeWidth: 0
      //       },
      //       spokes: {
      //         strokeWidth: 0
      //       }
      //     }
      //   },
      //   theme: {
      //     monochrome: {
      //       enabled: true,
      //       shadeTo: 'light',
      //       shadeIntensity: 0.6
      //     }
      //   }
      // }
      // chartOptions: {
      //   chart: {
      //     type: 'polarArea'
      //   },
      //   labels: [this.$t('general.work'), this.$t('general.leakage'), this.$t('general.chemicalLeakage'),
      //     this.$t('general.fires'), this.$t('general.intrudersAndVandals')
      //   ],
      //   stroke: {
      //     // colors: ['#29abe2', '#a4a8ad', '#d5d5d5']
      //   },
      //   plotOptions: {
      //     pie: {
      //       donut: {
      //         labels: {
      //           show: true,
      //           name:'total',
      //           value: 50
      //         }
      //
      //       }
      //
      //     }
      //   },
      //   // colors: ['#29abe2', '#a4a8ad', '#d5d5d5'],
      //   legend: {
      //     position: 'bottom',
      //     horizontalAlign: 'center'
      //
      //   },
      //   responsive: [{
      //     breakpoint: 480,
      //     options: {
      //       chart: {
      //         width: 200
      //       },
      //       legend: {
      //         position: 'bottom'
      //       }
      //     }
      //   }]
      // }
    };
  },
  mounted() {
    this.showChart = true;
  }
};
</script>
