<template>
  <div v-if="showChart">
    <apexchart
      height="480"
      type="line"
      :options="chartOptions"
      :series="series"
    ></apexchart>
  </div>
</template>

<script>
export default {
  name: "lineChart",
  props: {
    series: {
      type: Array,
      default: []
    },
    labels: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      showChart: false,
      // series: [{
      //   name: "",
      //   data: this.seriesData
      // }],
      chartOptions: {
        chart: {
          height: 430,
          type: "line",
          zoom: {
            enabled: false
          }
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
        theme: {
          monochrome: {
            enabled: true,
            color: this.$vuetify.theme.themes.light.primary,
            shadeTo: "light",
            shadeIntensity: 0.8
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: [5, 7, 5],
          curve: "straight",
          dashArray: [0, 8, 5]
        },
        // title: {
        //   text: 'Product Trends by Month',
        //   align: 'left'
        // },
        markers: {
          size: 0,
          hover: {
            sizeOffset: 6
          }
        },
        grid: {
          borderColor: "#f1f1f1"
        },
        xaxis: {
          categories: this.labels
        }
      }
    };
  },
  mounted() {
    this.showChart = true;
  }
};
</script>
