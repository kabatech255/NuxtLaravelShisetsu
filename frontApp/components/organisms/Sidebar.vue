<template>
  <aside class="Sidebar">
    <RouterLink to="/" tag="a" class="Sidebar_top --mdnone">
      <Icon name="home" class="Sidebar_topIcon" />
      <span class="Sidebar_topValue">TOP</span>
    </RouterLink>
    <ul class="Sidebar_menu">
      <li v-for="(item, index) in menu" :key="index" class="Sidebar_menuItem">
        <RouterLink
          :to="item.to"
          tag="a"
          class="Sidebar_menuLink"
          active-class="--current"
          exact
        >
          <Icon :name="item.iconSrc" class="Sidebar_menuIcon" />
          <span class="Sidebar_menuValue">{{ item.value }}</span>
        </RouterLink>
      </li>
    </ul>
  </aside>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { SidebarMenu } from '~/types/SidebarMenu'
import Icon from '~/components/atoms/Icon.vue'
export default Vue.extend({
  components: { Icon },
  props: {
    menu: {
      type: Array as PropType<SidebarMenu[]>,
      default: (): SidebarMenu[] => [
        {
          to: '/mypage',
          iconSrc: 'dashboard',
          value: 'dashboard',
        },
        {
          to: '/mypage/exam/select_shop',
          iconSrc: 'rule',
          value: 'exam',
        },
        {
          to: '/mypage/analysis',
          iconSrc: 'align_horizontal_left',
          value: 'aggregate',
        },
        {
          to: '/mypage/profile',
          iconSrc: 'account_box',
          value: 'profile',
        },
      ],
    },
  },
})
</script>
