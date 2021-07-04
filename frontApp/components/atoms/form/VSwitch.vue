<template>
  <v-switch v-model="val" :label="`男: ${resp}`"></v-switch>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'

export default Vue.extend({
  props: {
    val: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  computed: {
    resp(): string {
      return this.val ? 'はい' : 'いいえ'
    },
  },
})
</script>
