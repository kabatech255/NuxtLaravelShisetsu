<template>
  <div class="CheckBox">
    <input
      :id="item.name"
      v-model="item.value"
      type="checkbox"
      class="CheckBox_body"
      :class="classOptions"
      :data-label="item.label"
      :disabled="setOption('disabled')"
      @change="$emit('change')"
    />
    <Icon name="done" class="CheckBox_done" :class="doneCheck"></Icon>
    <label :for="item.name" class="CheckBox_label" :class="classOptions">
      {{ item.label }}
    </label>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { CheckField } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'

export default Vue.extend({
  components: { Icon },
  props: {
    item: {
      type: Object as PropType<CheckField>,
      default: (): CheckField => ({
        name: '',
        label: 'チェックボックス',
        type: 'text',
        errorMessages: [],
        value: '',
        rules: [() => true],
        options: [],
      }),
    },
  },
  computed: {
    classOptions(): string[] {
      if (Object.prototype.hasOwnProperty.call(this.item, 'class')) {
        return this.item.class
      }
      return []
    },
    doneCheck(): object {
      return {
        '--done': this.item.value,
      }
    },
  },
  methods: {
    toggleEye() {
      this.$emit('toggleEye')
    },
    hasOption(target: string): boolean {
      const options: any = this.item.options
      return options.some((option: string): boolean => option === target)
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
