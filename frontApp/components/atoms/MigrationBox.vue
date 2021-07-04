<template>
  <nav class="MigrationBox">
    <ul class="MigrationBox_list">
      <li
        v-for="(item, index) in boxList"
        :key="index"
        class="MigrationBox_item"
        :class="item.disabledValue"
        @click="$emit('click', item.clickFlag)"
      >
        <span>{{ item.label }}</span>
      </li>
    </ul>
  </nav>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export type ListItem = {
  clickFlag: string
  label: string
  disabledValue: {
    [k: string]: boolean
  }
}
export default Vue.extend({
  props: {
    boxList: {
      type: Array as PropType<ListItem[]>,
      default: (): any[] => [
        {
          clickFlag: 'prev',
          label: '前月',
          disabledValue: {
            '--disabled': false,
          },
        },
      ],
    },
  },
})
</script>
