<template>
  <div class="Panel" :style="style" @click="effect">
    <transition
      name="Ripple"
      @enter="rippleEnter"
      @after-enter="afterRippleEnter"
    >
      <span v-if="ripple" ref="ripple" class="Ripple" />
    </transition>
    <div class="Panel_content">
      <div class="Panel_icon">
        <img
          :src="panelData.file_name"
          :alt="`icon_${panelData.name}`"
          class="Panel_iconSrc"
        />
      </div>
      <div class="Panel_text">
        <span>{{ panelData.name }}</span>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'

export type PanelObj = {
  name: string
  file_name: string
  color: string
}
export default Vue.extend({
  props: {
    panelData: {
      type: Object as PropType<PanelObj>,
      default: (): PanelObj => ({
        name: '検査項目',
        file_name: '',
        color: '#ea6254',
      }),
    },
  },
  data: () => ({
    ripple: false,
    x: 0 as number,
    y: 0 as number,
  }),
  computed: {
    style(): object {
      return {
        backgroundColor: this.panelData.color,
      }
    },
  },
  methods: {
    effect(e: any): void {
      this.x = e.offsetX
      this.y = e.offsetY
      this.ripple = !this.ripple
      this.$emit('click')
    },
    rippleEnter(): void {
      const rippleComp: any = this.$refs.ripple
      rippleComp.style.top = `${this.y}px`
      rippleComp.style.left = `${this.x}px`
    },
    afterRippleEnter(): void {
      this.ripple = false
    },
  },
})
</script>
