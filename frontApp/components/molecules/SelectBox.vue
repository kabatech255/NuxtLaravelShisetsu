<template>
  <div class="SelectBox">
    <div class="SelectBox_body">
      <select
        id="select_box"
        v-model="item.value"
        class="SelectBox_list"
        :class="classOptions"
        :disabled="setOption('disabled')"
        @focus="escapeLabel"
        @blur="arriveByEmpty"
        @change="changed(item.value)"
      >
        <option
          v-for="(selectItem, index) in item.list"
          :key="index"
          :value="selectItem"
          class="SelectBox_item"
        >
          {{ selectItem[item.labelKey] }}
        </option>
      </select>
      <label
        for="select_box"
        class="SelectBox_label"
        :class="[escape, classOptions]"
      >
        <Icon :name="item.prependIcon" class="SelectBox_prependIcon" />
        {{ item.label }}</label
      >
      <Icon
        name="arrow_drop_down"
        class="SelectBox_appendIcon"
        :class="escape"
      />
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { SelectField } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'
export default Vue.extend({
  components: { Icon },
  props: {
    item: {
      type: Object as PropType<SelectField>,
      default: (): SelectField => ({
        name: '',
        label: '候補',
        type: 'select',
        labelKey: 'name',
        value: '',
        list: [],
        rules: [() => true],
        errorMessages: [],
        options: [],
      }),
    },
  },
  data: () => ({
    isEscape: false as boolean,
  }),
  computed: {
    escape(): object {
      return {
        '--escape': this.isEscape,
      }
    },
    classOptions(): string[] {
      if (Object.prototype.hasOwnProperty.call(this.item, 'class')) {
        return this.item.class
      }
      return []
    },
  },
  watch: {
    'item.value': {
      handler(): void {
        this.arriveByEmpty()
      },
      deep: true,
    },
  },
  methods: {
    escapeLabel(): void {
      this.isEscape = true
    },
    arriveByEmpty(): void {
      this.isEscape = this.item.value !== ''
      this.$emit('blur')
    },
    changed(itemValue: any): void {
      this.$emit('onChange', itemValue)
    },
    setOption(optionName: string): boolean {
      if (Object.prototype.hasOwnProperty.call(this.item, 'options')) {
        const options: any = this.item.options
        return options.some((option: string): boolean => option === optionName)
      }
      return false
    },
  },
})
</script>
