<template>
  <h3 class="ContentTitle">
    <span class="ContentTitle_value">{{ value }}</span>
  </h3>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    value: {
      type: String as PropType<string>,
      default: 'タイトル',
    },
  },
})
</script>
