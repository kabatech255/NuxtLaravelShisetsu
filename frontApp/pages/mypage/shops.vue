<template>
  <div class="p-dashboard">
    <p>店舗一覧</p>
    <ul>
      <li v-for="(shop, index) in shopList" :key="index">
        {{ shop.name }}
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'

interface AsyncData {
  shopList: object[]
}
export default Vue.extend({
  layout: 'MyPageLayout',
  props: {
    author: {
      type: Object as PropType<object>,
      default: (): object => ({}),
    },
  },
  async asyncData(app: any): Promise<AsyncData> {
    const shopList = await app.$axios.$get('/shop').catch((err: any): void => {
      return err.response.status
    })
    return { shopList }
  },
})
</script>
