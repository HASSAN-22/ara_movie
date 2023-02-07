<script>
import { defineComponent } from 'vue'
import { Scatter } from 'vue3-chart-v2'

export default defineComponent({
  name: 'Scatter',
  extends: Scatter,
  props: {
    chartData: {
      type: Object,
      required: true
    },
    chartOptions: {
      type: Object,
      required: false
    }
  },
  mounted () {
    this.renderChart(this.chartData, this.chartOptions)
  }
})
</script>