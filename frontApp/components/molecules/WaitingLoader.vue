<template>
  <transition name="fade">
    <div v-show="active" class="WaitingLoader">
      <p class="WaitingLoader_msg">Now Loading...</p>
    </div>
  </transition>
</template>
<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'
import { mapGetters } from 'vuex'
@Component({
  computed: {
    ...mapGetters({
      active: 'loader/waitingCheck',
    }),
  },
})
export default class WaitingLoader extends Vue {}
</script>
