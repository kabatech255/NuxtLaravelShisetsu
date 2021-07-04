<template>
  <RouterLink :to="to" tag="a" class="Logo">
    <h1 class="Logo_body">
      <span class="Logo_icon">
        <img :src="logoSrc" alt="site_icon" width="28" />
      </span>
      <span class="Logo_title">
        <span class="Logo_titleMain" :data-sub="'RiskExam'">コンプラ検査</span>
      </span>
    </h1>
  </RouterLink>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { assetPath } from '~/plugins/util'
export default Vue.extend({
  name: 'Logo',
  props: {
    to: {
      type: String as PropType<string>,
      default: '/',
    },
  },
  computed: {
    logoSrc(): string {
      return `${assetPath}/assets/img/logo_white.svg`
    },
  },
})
</script>
