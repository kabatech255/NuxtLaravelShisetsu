<template>
  <div class="BasePanel">
    <h3 :id="title.id" class="BasePanel_head">
      <Icon
        :name="title.prependIcon"
        class="BasePanel_prependIcon"
        @click="$emit('onPrepend')"
      />
      <span class="BasePanel_titleValue" :style="fontColor">{{
        title.value
      }}</span>
      <Icon
        :name="title.appendIcon"
        class="BasePanel_appendIcon"
        :class="isClick"
        :style="fontColor"
        :data-tooltip="eventContent"
        @click="$emit('onAppend')"
      />
      <slot name="head-attach"></slot>
    </h3>
    <div class="BasePanel_body">
      <slot name="content"></slot>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Icon from '~/components/atoms/Icon.vue'
import { BasePanelTitle } from '~/types/Dashboard'

export default Vue.extend({
  components: { Icon },
  props: {
    title: {
      type: Object as PropType<BasePanelTitle>,
      default: (): BasePanelTitle => ({
        id: '',
        value: 'タイトル',
        prependIcon: '',
        appendIcon: '',
        color: 'red',
        isEdit: false,
      }),
    },
    eventContent: {
      type: String as PropType<string>,
      default: '',
    },
  },
  data: (): any => ({
    colors: {
      red: '#F25151' as string,
      orange: '#ff9800' as string,
      purple: '#9561e2' as string,
      green: '#4dc0b5' as string,
      black: '#444444' as string,
    },
  }),
  computed: {
    fontColor(): object {
      return {
        color: this.colors[this.title.color],
      }
    },
    isClick(): object {
      return {
        '--isClick': this.eventContent !== '',
      }
    },
  },
})
</script>
