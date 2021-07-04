<template>
  <div class="DoughnutChartLayout">
    <CChartDoughnut
      style="width: 100%; height: 100%"
      :datasets="datasets"
      :labels="labels"
      :options="options"
    />
    <div class="DoughnutChartLayout_center">
      <DoughnutCenter
        :label="centerLabel"
        :value="centerValue"
        :status-class="centerStatus"
        :unit="centerUnit"
      />
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import DoughnutCenter from '~/components/atoms/DoughnutCenter.vue'

@Component({
  components: { DoughnutCenter },
})
export default class DoughnutChartLayout extends Vue {
  @Prop({ type: Array, default: () => [] })
  datasets?: any[]

  @Prop({ type: Array, default: () => [] })
  labels?: string[]

  @Prop({ type: Object, default: () => ({}) })
  options?: object

  @Prop({ type: String, default: '' })
  centerLabel?: string

  @Prop({ type: [String, Number], default: '' })
  centerValue?: string | number

  @Prop({ type: String, default: '' })
  centerUnit?: string

  @Prop({ type: Array, default: () => [] })
  centerStatus?: string[]
}
</script>
