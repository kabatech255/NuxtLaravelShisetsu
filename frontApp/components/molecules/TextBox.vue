<template>
  <div class="TextBox">
    <input
      :id="item.name"
      v-model="item.value"
      :type="textType"
      class="TextBox_body"
      :class="[escape, classOptions]"
      :data-label="item.label"
      :placeholder="item.placeholder"
      :autofocus="setOption('autofocus')"
      :disabled="setOption('disabled')"
      @focus="escapeLabel"
      @blur="arriveByEmpty"
      @input="$emit('onInput')"
    />
    <label
      :for="item.name"
      class="TextBox_label"
      :class="[escape, classOptions]"
    >
      <Icon :name="item.prependIcon" class="TextBox_prependIcon" />{{
        item.label
      }}
    </label>
    <Icon
      v-if="item.type === 'password'"
      :name="iconByTextType"
      class="TextBox_appendIcon"
      :tab-option="0"
      @click="toggleEye"
      @onKeypressEnter="toggleEye"
    />
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { TextField } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'

export default Vue.extend({
  components: { Icon },
  props: {
    item: {
      type: Object as PropType<TextField>,
      default: (): TextField => ({
        name: '',
        label: 'テキストフィールド',
        type: 'text',
        isHidden: false,
        errorMessages: [],
        value: '',
        placeholder: '',
        rules: [() => true],
        options: [],
      }),
    },
  },
  data: () => ({
    isEscape: false as boolean,
  }),
  computed: {
    textType(): string {
      return this.item.isHidden ? 'password' : 'text'
    },
    iconByTextType(): string {
      return this.item.isHidden ? 'visibility' : 'visibility_off'
    },
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
    item: {
      handler(item): void {
        if (
          Object.prototype.hasOwnProperty.call(item, 'class') &&
          item.class.some(
            (className: string): boolean => className === '--escape'
          )
        ) {
          this.isEscape = true
        }
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
