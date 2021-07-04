<template>
  <nav class="TabList">
    <ul class="TabList_menu">
      <li
        v-for="(item, index) in list"
        :key="index"
        class="TabList_item --large"
        :class="active(item[keys[0]])"
        @click="$emit('click', item[keys[0]])"
      >
        {{ item[keys[1]] }}
      </li>
    </ul>
  </nav>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    list: {
      type: Array as PropType<any[]>,
      default: (): any[] => [],
    },
    value: {
      type: Object as PropType<any>,
      default: '',
    },
    keys: {
      type: Array as PropType<string[]>,
      default: (): string[] => ['id', 'name'],
    },
  },
  methods: {
    active(index: number): object {
      return {
        '--active': this.value[this.keys[0]] === index,
      }
    },
  },
})
</script>
