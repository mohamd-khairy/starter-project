<template>
  <div v-if="showChart">
    <apexchart type="bar" height="430" :options="chartOptions" :series="series"></apexchart>
  </div>
</template>

<script>
export default {
  props:{
    series: Array,
    labels: Array,
  },
  data() {
    return {
      showChart: false,
      // series: [{
      //   name: this.$t('charts.drone1'),
      //   data: [44, 55, 41, 64, 22, 43, 21]
      // }, {
      //   name: this.$t('charts.drone2'),
      //   data: [53, 32, 33, 52, 13, 44, 32]
      // }, {
      //   name: this.$t('charts.drone3'),
      //   data: [54, 67, 78, 45, 57, 77, 88]
      // }, {
      //   name: this.$t('charts.drone4'),
      //   data: [98, 59, 45, 65, 23, 45, 43]
      // }],
      chartOptions: {
        chart: {
          type: 'bar',
          height: 430,
          borderRadius: 10
        },
        colors: ['#0672de', '#2a8cf0', '#4998e8', '#0066cc', '#4e57b7'],
        // labels: [this.$t('charts.drone1'), this.$t('charts.drone2'), this.$t('charts.drone3'), this.$t('charts.drone4')],
        plotOptions: {
          bar: {
            // horizontal: true,
            dataLabels: {
              position: 'top'
            }
          }
        },
        dataLabels: {
          enabled: true,
          offsetX: -6,
          style: {
            fontSize: '12px',
            colors: ['#fff']
          }
        },
        stroke: {
          show: true,
          width: 1,
          colors: ['#fff']
        },
        xaxis: {
          categories:this.labels
          // categories:  [this.$t('charts.Station1'), this.$t('charts.Station2'), this.$t('charts.Station3'),
            // this.$t('charts.Station4'), this.$t('charts.Station5'), this.$t('charts.Station6'), this.$t('charts.Station7')]
        }
      }
    }
  },
  mounted() {
    this.showChart = true
  }
}
</script>
