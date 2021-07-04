<template>
  <div class="SuggestBox">
    <ul class="u-alignCenter">
      <li
        v-for="(item, index) in suggestList"
        :key="index"
        @click="onClick(item)"
      >
        <span>{{ item[keyName] }}</span>
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    keyName: {
      type: String as PropType<string>,
      default: 'name',
    },
    suggestList: {
      type: Array as PropType<object[]>,
      default: (): object[] => [
        {
          name: 'サジェスト',
        },
      ],
    },
  },
  methods: {
    onClick(item: object): void {
      this.$emit('onSelect', {
        item,
      })
    },
  },
})
</script>
