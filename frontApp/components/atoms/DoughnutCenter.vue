<template>
  <div class="DoughnutCenter">
    <div class="DoughnutCenter_label">
      {{ label }}
    </div>
    <h4 class="DoughnutCenter_value" :data-unit="unit" :class="statusClass">
      {{ value }}
    </h4>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'

@Component
export default class DoughnutCenter extends Vue {
  @Prop({ type: String, default: '' })
  label?: string

  @Prop({ type: [String, Number], default: '' })
  value?: string | number

  @Prop({ type: String, default: '' })
  unit?: string

  @Prop({ type: Array, default: () => [] })
  statusClass?: string[]
}
</script>
