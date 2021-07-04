<template>
  <div class="Modal" :class="hidden">
    <div class="Modal_overlay" @click="$emit('onCancel')"></div>
    <div class="Modal_content">
      <slot name="modalContent"></slot>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    isShow: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  computed: {
    hidden(): object {
      return {
        '--hidden': !this.isShow,
      }
    },
  },
})
</script>
