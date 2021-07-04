<template>
  <div class="ColorPanel">
    <div class="ColorPanel_overlay" :style="bgColor"></div>
    <div class="ColorPanel_content" :class="fontBlack">
      <slot name="ColorPanel_content"></slot>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { primaryColor } from '~/plugins/util'

export default Vue.extend({
  props: {
    color: {
      type: String as PropType<string>,
      default: primaryColor,
    },
    isFontBlack: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  computed: {
    bgColor(): object {
      return {
        backgroundColor: this.color,
      }
    },
    fontBlack(): object {
      return {
        '--fontBlack': this.isFontBlack,
      }
    },
  },
})
</script>
