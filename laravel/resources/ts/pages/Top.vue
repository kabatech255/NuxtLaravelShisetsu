<template>
  <div class="p-top">
    <h1>Welcome!!</h1>
    <div class="Button">ボタンです</div>
    <div class="BorderButton">ボタンです</div>
    <RouterLink to="/signin" tag="p">ログイン</RouterLink>
    <p @click="logout()">ログアウト</p>
  </div>
</template>
<script lang="ts">
import Vue from "vue";
export default Vue.extend({
  methods: {
    async logout() {
      await this.$store.dispatch("auth/signout");
    },
  },
});
</script>
