<template>
  <footer class="Footer u-alignCenter">
    <p>&copy;2020-{{ year }} kensa-system.net All Rights Reserved.</p>
  </footer>
</template>
<script lang="ts">
import Vue from 'vue'
export default Vue.extend({
  computed: {
    year(): number {
      return new Date().getFullYear()
    },
  },
})
</script>
