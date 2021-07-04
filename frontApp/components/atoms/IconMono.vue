<template>
  <span class="IconMono" @click="$emit('click')">
    <img :src="src" alt="iconMono" :width="width" />
  </span>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { assetPath } from '~/plugins/util'

export default Vue.extend({
  props: {
    width: {
      type: Number as PropType<number>,
      default: 24,
    },
    name: {
      type: String as PropType<string>,
      default: 'assets/img/mail.svg',
    },
  },
  computed: {
    src(): string {
      return `${assetPath}/${this.name}`
    },
  },
})
</script>
