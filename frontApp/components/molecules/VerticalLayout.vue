<template>
  <ul class="VerticalLayout" :class="classOptions">
    <li v-for="(item, index) in list" :key="index" class="VerticalLayout_item">
      <button v-if="item.isShow" class="VerticalLayout_row">
        <span
          :style="itemStyle(item)"
          @click="
            (e) => {
              onClick(e, item)
            }
          "
        >
          <Icon
            v-if="hasIcon"
            :name="item.iconName"
            class="VerticalLayout_icon"
            :class="active(item[keys[0]])"
          />
          <span class="VerticalLayout_label" :class="invalid(item)">{{
            item[keys[1]]
          }}</span>
        </span>
        <Icon
          v-if="hasProp(item, 'appendIcon')"
          :name="item.appendIcon"
          class="VerticalLayout_appendIcon"
          @click="onAppend(item)"
        />
      </button>
    </li>
  </ul>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Icon from '~/components/atoms/Icon.vue'
export default Vue.extend({
  components: { Icon },
  props: {
    list: {
      type: Array as PropType<any[]>,
      default: (): any[] => [],
    },
    keys: {
      type: Array as PropType<string[]>,
      default: ['id', 'name'],
    },
    hasIcon: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    target: {
      type: Object as PropType<any>,
      default: (): any => ({
        id: 0,
      }),
    },
    classOptions: {
      type: Array as PropType<string[]>,
      default: (): string[] => [],
    },
  },
  data: () => ({
    ripple: false as boolean,
    x: 0 as number,
    y: 0 as number,
  }),
  methods: {
    onClick(e: any, item: any): void {
      this.x = e.offsetX
      this.y = e.offsetY
      this.ripple = !this.ripple
      this.$emit('itemClick', item)
    },
    active(arg: any): object {
      return {
        '--active': this.target[this.keys[0]] === arg,
      }
    },
    invalid(item: any): object {
      return !this.hasProp(item, 'validity')
        ? []
        : {
            '--invalid': !item.validity,
          }
    },
    itemStyle(item: any): string {
      return this.hasProp(item, 'style') ? item.style : ''
    },
    hasProp(item: any, propName: string): boolean {
      return Object.prototype.hasOwnProperty.call(item, propName)
    },
    onAppend(item: any): void {
      this.$emit('onAppend', item)
    },
  },
})
</script>
