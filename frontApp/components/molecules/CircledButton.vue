<template>
  <button
    class="CircledButton"
    :tabindex="tabIndex"
    type="button"
    @click="effect"
  >
    <Icon :name="iconName" class="CircledButton_icon" />
    <transition
      name="CircledButton_ripple"
      @enter="rippleEnter"
      @after-enter="afterRippleEnter"
    >
      <span v-if="ripple" ref="ripple" class="CircledButton_ripple" />
    </transition>
  </button>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Icon from '~/components/atoms/Icon.vue'
import { CircledButtonObj } from '~/types/CircledButton'

export default Vue.extend({
  components: { Icon },
  props: {
    iconName: {
      type: String as PropType<string>,
      default: 'keyboard_arrow_up',
    },
    tabIndex: {
      type: Number as PropType<number>,
      default: 1,
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
    toTop(): string {
      return '.Header'
    },
  },
})
</script>
