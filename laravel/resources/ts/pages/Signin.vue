<template>
  <div class="p-login">
    <div class="Container">
      <form @submit.prevent="signin">
        <div v-if="hasError('login_id')">
          <p v-for="(msg, idx) in errorMessages.login_id" :key="idx">
            {{ msg }}
          </p>
        </div>
        <div class="form-row">
          <input
            v-model="formData.login_id"
            type="text"
            placeholder="Login ID"
          />
        </div>

        <div v-if="hasError('password')">
          <p v-for="(msg, idx) in errorMessages.password" :key="idx">
            {{ msg }}
          </p>
        </div>

        <div class="form-row">
          <input
            v-model="formData.password"
            type="password"
            placeholder="password(at least 8)"
          />
        </div>

        <button>Sign in</button>
      </form>
    </div>
  </div>
</template>
<script lang="ts">
import Vue from 'vue'
import { mapGetters } from 'vuex'
export default Vue.extend({
  data: () => ({
    formData: {
      login_id: '',
      password: '',
    },
  }),
  computed: {
    ...mapGetters({
      errorMessages: 'error/messages',
    }),
  },
  methods: {
    hasError(attr: string): boolean {
      return attr in this.errorMessages
    },
    async signin() {
      await this.$store.dispatch('auth/signin', this.formData)
    },
  },
})
</script>
