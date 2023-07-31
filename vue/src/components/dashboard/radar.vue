<template>
  <div v-if="showChart">
    <apexchart
      type="radar"
      height="350"
      :options="chartOptions"
      :series="series"
    ></apexchart>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showChart: false,
      series: [
        {
          name: "Drone 1",
          data: [80, 50, 30, 40, 100, 20]
        },
        {
          name: "Drone 2",
          data: [20, 30, 40, 80, 20, 80]
        },
        {
          name: "Drone 3",
          data: [44, 76, 78, 13, 43, 10]
        }
      ],
      chartOptions: {
        chart: {
          height: 350,
          type: "radar",
          dropShadow: {
            enabled: true,
            blur: 1,
            left: 1,
            top: 1
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
        title: {
          text: ""
        },
        stroke: {
          width: 2
        },
        fill: {
          opacity: 0.1
        },
        markers: {
          size: 0
        },
        xaxis: {
          categories: ["Drone", "2012", "2013", "2014", "2015", "2016"]
        }
      }
    };
  },
  mounted() {
    this.showChart = true;
  }
};
</script>
