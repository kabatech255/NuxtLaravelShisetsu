<template>
  <i
    class="Icon material-icons-outlined"
    :tabindex="tabOption"
    :data-tooltip="dataTooltip"
    @click="$emit('click')"
    @keypress.enter="$emit('onKeypressEnter')"
  >
    {{ name }}
  </i>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    name: {
      type: String as PropType<string>,
      default: 'info',
    },
    tabOption: {
      type: [Number, Boolean] as PropType<number | boolean>,
      default: (): boolean => false,
    },
    dataTooltip: {
      type: [String, Number] as PropType<string | number>,
      default: '',
    },
  },
})
</script>
