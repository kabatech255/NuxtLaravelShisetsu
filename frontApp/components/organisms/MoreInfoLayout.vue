<template>
  <div class="MoreInfoLayout" :class="hideClass">
    <div class="MoreInfoLayout_overlay" @click="toggle"></div>
    <div class="MoreInfoLayout_icon">
      <Icon :name="iconName" @click="toggle" />
    </div>
    <div class="MoreInfoLayout_body">
      <Balloon class="--hasBorder" :class="direction">
        <div slot="Balloon_content" class="Balloon_content">
          <slot name="MoreInfoLayout_content"></slot>
          <VerticalLayout
            :list="infoData.list"
            :has-icon="infoData.hasRowIcon"
            :target="infoData.activeTarget"
            :keys="infoData.keys"
            :class-options="infoData.classOptions"
            @itemClick="onItemClick"
          />
        </div>
      </Balloon>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Balloon from '~/components/atoms/Balloon.vue'
import Icon from '~/components/atoms/Icon.vue'
import VerticalLayout from '~/components/molecules/VerticalLayout.vue'
export type InfoData = {
  hasIcon: boolean
  keys: string[]
  list: any[]
  activeTarget: any
}
export default Vue.extend({
  components: { Balloon, Icon, VerticalLayout },
  props: {
    iconName: {
      type: String as PropType<string>,
      default: 'more_vert',
    },
    infoData: {
      type: Object as PropType<any>,
      default: (): any => ({
        hasRowIcon: false,
        keys: ['id', 'name'],
        list: [],
        activeTarget: {
          id: 1,
        },
        classOptions: [],
      }),
    },
    balloonDirection: {
      type: Array as PropType<string[]>,
      default: (): string[] => ['--bottom'],
    },
  },
  data: () => ({
    hidden: true as boolean,
  }),
  computed: {
    hideClass(): object {
      return {
        '--hidden': this.hidden,
      }
    },
    direction(): object {
      return [this.balloonDirection]
    },
  },
  methods: {
    toggle() {
      this.hidden = !this.hidden
    },
    onItemClick(item: any): void {
      this.toggle()
      this.$emit('clickVerticalItem', item)
    },
  },
})
</script>
