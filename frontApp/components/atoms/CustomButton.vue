<template>
  <button class="Button" tabindex="0" role="button" @click="effect">
    <transition
      name="Button_ripple"
      @enter="rippleEnter"
      @after-enter="afterRippleEnter"
    >
      <span v-if="ripple" ref="ripple" class="Button_ripple" />
    </transition>
    <span class="Button_label">
      {{ label }}
    </span>
  </button>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    label: {
      type: String as PropType<string>,
      default: 'ボタン',
    },
  },
  data: () => ({
    ripple: false as boolean,
    x: 0 as number,
    y: 0 as number,
  }),
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
