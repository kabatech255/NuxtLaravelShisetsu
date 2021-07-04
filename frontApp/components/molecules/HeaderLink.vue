<template>
  <RouterLink
    v-if="item.to === '/login'"
    :to="item.to"
    active-class="--current"
    :tag="tag"
    class="HeaderLink"
    tabindex="0"
  >
    <Icon :name="item.iconSrc" :class="['HeaderLink_itemMark']" />
    <span class="HeaderLink_menuLabel">{{ item.label }}</span>
  </RouterLink>
  <li v-else class="HeaderLink" tabindex="0" @click="$emit('click', item)">
    <Icon :name="item.iconSrc" :class="['HeaderLink_itemMark']" />
    <span class="HeaderLink_menuLabel">{{ item.label }}</span>
  </li>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Icon from '~/components/atoms/Icon.vue'
import { HeaderItem } from '~/types/Header'

export type DataType = {
  offset: number
}
export default Vue.extend({
  components: { Icon },
  props: {
    item: {
      type: Object as PropType<HeaderItem>,
      required: true,
    },
    tag: {
      type: String as PropType<string>,
      default: 'div',
    },
  },
})
</script>
