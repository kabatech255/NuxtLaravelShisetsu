<template>
  <nav class="BreadCrumb">
    <ul class="BreadCrumb_list">
      <li
        v-for="(item, index) in pathList"
        :key="index"
        class="BreadCrumb_item"
      >
        <Icon v-if="index !== 0" name="arrow_right" class="BreadCrumb_icon" />
        <RouterLink
          :to="item.path"
          tag="a"
          class="BreadCrumb_link"
          exact-active-class="--current"
          >{{ item.name }}
        </RouterLink>
      </li>
    </ul>
  </nav>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import Icon from '~/components/atoms/Icon.vue'
import { BreadCrumbObj } from '~/types/BreadCrumbs'

@Component({
  components: { Icon },
})
export default class BreadCrumb extends Vue {
  @Prop({ type: Array, default: () => [] })
  pathList?: BreadCrumbObj[]
}
</script>
