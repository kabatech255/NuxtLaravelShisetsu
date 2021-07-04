<template>
  <div v-scroll-to="to" class="ScrollButton" :class="hidden">
    <slot name="button-content"></slot>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import VueScrollTo from 'vue-scrollto'
import { CircledButtonObj } from '~/types/CircledButton'
Vue.use(VueScrollTo, {
  duration: 800,
  easing: [0.42, 0.0, 1.0, 1.0],
  offset: -50,
})
export default Vue.extend({
  props: {
    to: {
      type: String as PropType<string>,
      default: '#scroll_to_el',
    },
  },
  data: () => ({
    position: 0 as number,
    switchPoint: 0 as number,
    buttonObj: {
      tabIndex: 100,
      iconName: 'keyboard_arrow_up',
    } as CircledButtonObj,
  }),
  computed: {
    hidden() {
      return {
        '--hidden': this.position < this.switchPoint,
      }
    },
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll)
    this.switchPoint = window.innerHeight * 0.667
  },
  methods: {
    handleScroll(): void {
      this.position = window.scrollY
    },
  },
})
</script>
