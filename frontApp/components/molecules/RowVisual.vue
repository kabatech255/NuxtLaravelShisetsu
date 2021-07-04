<template>
  <div class="RowVisual" :style="bgImage">
    <div class="RowVisual_inner">
      <h1
        class="RowVisual_title"
        :data-subtitle="dataSubTitle"
        :style="titleStyle"
      >
        {{ rowVisualTitle }}
      </h1>
      <slot name="RowVisual_inner"></slot>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { assetPath } from '~/plugins/util'
export default Vue.extend({
  props: {
    imagePath: {
      type: String as PropType<string>,
      default: 'assets/img/analysis1.jpg',
    },
    rowVisualTitle: {
      type: [String, Number] as PropType<string>,
      default: '',
    },
    dataSubTitle: {
      type: [String, Number] as PropType<number | string>,
      default: '',
    },
    titleStyle: {
      type: Object as PropType<object>,
      default: (): object => ({}),
    },
  },
  computed: {
    bgImage(): object {
      return {
        backgroundImage: `url(${assetPath}/${this.imagePath})`,
      }
    },
  },
})
</script>
