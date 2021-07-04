<template>
  <div class="RadioBox">
    <h3 class="RadioBox_head">
      <span>{{ item.label }}: </span>
      <span class="RadioBox_selectValue">{{ selectValue }}</span>
    </h3>
    <div class="RadioBox_body" :class="fixed">
      <div
        v-for="(radioItem, index) in item.list"
        :key="index"
        class="RadioBox_row"
      >
        <input
          :id="`list_${radioItem[item.keys[0]]}`"
          :ref="`radio_${radioItem[item.keys[0]]}`"
          v-model="item.value"
          type="radio"
          :value="radioItem"
          class="RadioBox_dot"
        />
        <label
          :for="`list_${radioItem[item.keys[0]]}`"
          class="RadioBox_label"
          >{{ radioItem[item.keys[1]] }}</label
        >
      </div>
      <p v-if="!item.list.length" class="RadioBox_noItemMsg">
        検索結果がありません
      </p>
      <div v-if="item.loader" class="RadioBox_loaderBg">
        <vue-loaders
          class="RadioBox_loader"
          name="line-spin-fade-loader"
          color="#444444"
        ></vue-loaders>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { RadioField } from '~/types/FormObj'

export default Vue.extend({
  props: {
    item: {
      type: Object as PropType<RadioField>,
      default: (): RadioField => ({
        name: '',
        label: '候補',
        type: 'radio',
        value: '',
        list: [],
        verticalFixed: true,
        keys: ['store_code', 'name'],
        rules: [() => true],
        errorMessages: [],
        options: [],
      }),
    },
  },
  computed: {
    selectValue(): string {
      return this.item.value ? this.item.value[this.item.keys[1]] : '未選択'
    },
    fixed(): object {
      return {
        '--fixed': this.item.verticalFixed,
      }
    },
  },
  methods: {
    isChecked(radioItem: any): boolean {
      return radioItem[this.item.keys[0]] === this.item.value[this.item.keys[0]]
    },
  },
})
</script>
