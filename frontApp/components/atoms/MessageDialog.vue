<template>
  <div class="MessageDialog" :class="addClass">
    <p
      v-for="(message, index) in messageList"
      :key="index"
      class="MessageDialog_text"
    >
      {{ message }}
    </p>
  </div>
</template>
<script lang="ts">
import Vue from 'vue'
import { mapGetters } from 'vuex'
import { UNAUTHORIZED } from '~/plugins/util'
export default Vue.extend({
  computed: {
    ...mapGetters({
      messagesObj: 'status/messages',
      status: 'status/status',
    }),
    addClass(): object {
      return {
        '--hidden': !this.messagesObj,
        '--error': this.status >= 400,
      }
    },
    messageList(): string[] {
      if (this.messagesObj === null) {
        return []
      }
      return Object.keys(this.messagesObj).map(
        (key: string): string => this.messagesObj[key][0]
      )
    },
  },
  watch: {
    messagesObj: {
      handler(): void {
        setTimeout((): void => {
          this.$store.commit('status/setStatus', {
            status: null,
            messages: null,
          })
        }, 4000)
      },
      deep: true,
    },
  },
})
</script>
