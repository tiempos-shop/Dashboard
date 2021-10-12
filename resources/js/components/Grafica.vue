<template>
    <div>
        <div id="chart"></div>
    </div>
</template>
<script>
import ApexCharts from 'apexcharts'
var options = {
  chart: {
    type: 'line',
    height: '400px' 
  },
  series: [{
    name: 'Ventas',
    data: [5,10,12,10,14,20,32,35,40]
  }],
  xaxis: {
    categories: ['ene','feb','mar','abri','mayo','junio','julio', 'agost','sept']
  }
}



export default {
    data() {
        return {
            
        }
    },
    mounted() {
        //var chart = new ApexCharts(document.getElementById("chart"), options);

        //chart.render();
    },
}
</script>