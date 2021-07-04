<template>
  <div class="ChartCard">
    <div class="ChartCard_panel" :style="[bgColor, bgIcon]">
      <slot name="chart-card-panel"></slot>
    </div>
    <div class="ChartCard_desc">
      <slot name="chart-card-desc"></slot>
    </div>
  </div>
</template>
<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import { assetPath } from '~/plugins/util'
@Component
export default class ChartCard extends Vue {
  @Prop({ type: String, default: '#9e9e9e' })
  panelColor?: string

  @Prop({ type: String, default: '' })
  badgePath?: string

  @Prop({ type: Boolean, default: false })
  hasBar?: boolean

  get bgColor(): object {
    return {
      backgroundColor: this.panelColor,
      boxShadow: `0 2px 12px -4px ${this.panelColor}`,
    }
  }

  get bgIcon(): object {
    const path =
      this.badgePath !== '' ? `url(${assetPath}/${this.badgePath})` : ''
    return {
      backgroundImage: path,
    }
  }
}
</script>
