<template>
  <div class="CircleBox">
    <h4 class="CircleBox_value" :data-label="dataLabel">{{ value }}</h4>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'

@Component
export default class CircleBox extends Vue {
  @Prop({ type: [String, Number], default: '35' })
  value!: string | number

  @Prop({ type: [String, Number], default: '指摘総数' })
  dataLabel!: string | number
}
</script>
