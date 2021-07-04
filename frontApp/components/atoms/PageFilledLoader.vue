<template>
  <div class="PageFilledLoader" :class="addHidden">
    <div class="PageFilledLoader_content">
      <vue-loaders
        class="PageFilledLoader_entity"
        name="line-scale"
        color="#38c172"
      ></vue-loaders>
    </div>
  </div>
</template>
<script lang="ts">
import Vue from 'vue'
import { mapGetters } from 'vuex'
export default Vue.extend({
  computed: {
    ...mapGetters({
      isLoader: 'loader/loadCheck',
    }),
    addHidden(): object {
      return {
        '--hidden': !this.isLoader,
      }
    },
  },
})
</script>
