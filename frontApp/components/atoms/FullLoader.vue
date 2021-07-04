<template>
  <div class="FullLoader" :class="hidden">
    <div class="FullLoader_content">
      <vue-loaders
        class="FullLoader_entity"
        name="line-scale"
        color="#38c172"
      ></vue-loaders>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    isLoader: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  computed: {
    hidden(): object {
      return {
        '--hidden': !this.isLoader,
      }
    },
  },
})
</script>
