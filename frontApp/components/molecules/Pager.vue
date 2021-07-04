<template>
  <div class="Pager">
    <ul class="Pager_list">
      <li
        v-for="i in totalPage"
        :key="i"
        class="Pager_item"
        :class="currentClass(i)"
        @click="movePage(i)"
      >
        {{ i }}
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
export default Vue.extend({
  props: {
    perPage: {
      type: Number as PropType<number>,
      default: 12,
    },
    totalItemsCount: {
      type: Number as PropType<number>,
      default: 12,
    },
    currentPage: {
      type: Number as PropType<number>,
      default: 1,
    },
  },
  computed: {
    totalPage(): number {
      return Math.ceil(this.totalItemsCount / this.perPage)
    },
  },
  methods: {
    currentClass(pageNumber: number): object {
      return {
        '--current': this.currentPage === pageNumber,
      }
    },
    movePage(pageNumber: number): void {
      this.$emit('onLinkClicked', pageNumber)
    },
  },
})
</script>
