<template>
  <div id="suadiaMap" style="height: 550px;"></div>
</template>

<script>
import * as Highcharts  from '../../../public/js/highmaps'
import * as saAall  from '../../../public/js/sa-all'
export default {
  name: 'SaudiMap',
  data() {
    return {
      // chartOptions: {
      //   chart: {
      //     map: ,
      //     borderWidth: 1
      //   },
      //   title: {
      //     text: 'Nordic countries'
      //   },
      //   subtitle: {
      //     text: 'Demo of drawing all areas in the map, only highlighting partial data'
      //   },
      //   legend: {
      //     enabled: false
      //   },
      //   series: [{
      //     name: 'Country',
      //     mapData: Highcharts.maps['custom/europe'],
      //     data: [
      //       ['is', 1],
      //       ['no', 1],
      //       ['se', 1],
      //       ['dk', 1],
      //       ['fi', 1]
      //     ],
      //     dataLabels: {
      //       enabled: true,
      //       color: '#FFFFFF',
      //       formatter: function () {
      //         if (this.point.value) {
      //           return this.point.name
      //         }
      //       }
      //     },
      //     tooltip: {
      //       headerFormat: '',
      //       pointFormat: '{point.name}'
      //     }
      //   }]
      // }
    }
  },
  created() {
    const data = [
      ['sa-4293', 0],
      ['sa-tb', 1],
      ['sa-jz', 2],
      ['sa-nj', 3],
      ['sa-ri', 4],
      ['sa-md', 5],
      ['sa-ha', 6],
      ['sa-qs', 7],
      ['sa-hs', 8],
      ['sa-jf', 9],
      ['sa-sh', 10],
      ['sa-ba', 11],
      ['sa-as', 12],
      ['sa-mk', 250]
    ]

    let secondMap = ''
    const clicked = false
    // Create the chart

    Highcharts.mapChart('suadiaMap', {
      chart: {
        map: 'countries/sa/sa-all',
        backgroundColor: 'transparent',
        borderColor: 'transparent',
        borderRadius: 10,
        events: {
          load: function (e) {
            // console.log(this);
            // console.log(e.point)
            // this.mapZoom(0.5);
            secondMap = this

          }
        }
      },
      tooltip:{
        backgroundColor:'#242f3e',
        borderColor:'#242f3e',
        borderRadius:0,
        borderWidth:0,
        footerFormat: '',
        headerFormat:'',
        padding:0,
        pointFormat:'<div class="saudi__tooltip"><span class="one">{ point.name } </span></div>',
        pointFormatter:'',
        style:{
          color:'#fff',
          fontSize:'1.1rem',
          whiteSpace:'nowrap',
          textAlign: 'center'
        },
        useHTML:true
      },
      title: { text: '' },
      mapNavigation: {
        enabled: false
        // buttonOptions: {
        //     verticalAlign: 'bottom'
        // }
      },
      plotOptions:{
        series:{
          point:{
            events:{
              click: function(e) {

              }
            }
          }
        }
      },
      series: [{
        data: data,
        name: 'النماذج',
        states: {
          hover: {
            color :'#6B4B32'
          }
        },
        dataLabels: {
          enabled: true,
          format: '{point.name}'
        }
      }],
      colorAxis: {
        min: 1,
        type: '',
        minColor: '#ff0',
        maxColor: '#ff0',
        stops: [
          [0, '#ff0'],
          // [0.67, '#6C747E'],
          [1, '#ff0']
        ]
      }
    } )
  }
}
</script>

<!-- Add 'scoped' attribute to limit CSS to this component only -->
<style scoped>
.saudi__tooltip {
  background-color: #fff;
  color: #242f3e;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  border-radius: 10px;
}

.saudi__tooltip span {
  padding: 5px 10px;
  display: inline-block;
  -webkit-box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;
}

.saudi__tooltip span.two {
  background-color: #242F3E;
  color: #fff;
  border-radius: 0 10px 10px 0;
}
</style>
