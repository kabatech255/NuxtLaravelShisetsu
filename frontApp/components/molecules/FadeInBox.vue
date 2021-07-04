<template>
  <div class="FadeInBox" :class="showClass">
    <div :ref="refKey">
      <slot name="fadein-content"></slot>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'

@Component
export default class FadeInBox extends Vue {
  @Prop({ type: String, default: 'fadeInContent' }) refKey!: string

  @Prop({ type: Number, required: true }) currentY!: number
  @Prop({ type: Number, required: true }) mainVisualHeight!: number

  positionY: number = 0

  get isShow(): boolean {
    return this.currentY >= this.switchPoint
  }

  get switchPoint(): number {
    return this.mainVisualHeight + this.positionY
  }

  get showClass(): object {
    return {
      '--show': this.isShow,
    }
  }

  mounted() {
    const section: any = this.$refs[this.refKey]
    this.positionY = section.getBoundingClientRect().top
  }
}
</script>
