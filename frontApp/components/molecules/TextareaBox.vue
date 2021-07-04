<template>
  <div class="TextareaBox u-mt50">
    <textarea
      :id="item.name"
      v-model="item.value"
      class="TextareaBox_body"
      :class="[escape, classOptions]"
      :data-label="item.label"
      :placeholder="item.placeholder"
      @focus="escapeLabel"
      @blur="arriveByEmpty"
      @input="$emit('onInput')"
    />
    <label :for="item.name" class="TextareaBox_label" :class="escape">
      <Icon :name="item.prependIcon" class="TextareaBox_prependIcon" />
      <span>{{ item.label }}</span>
    </label>
    <Icon
      v-if="item.type === 'password'"
      :name="iconByTextType"
      class="TextareaBox_appendIcon"
      :tab-option="0"
      @click="toggleEye"
      @onKeypressEnter="toggleEye"
    />
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { TextareaField } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'

export default Vue.extend({
  components: { Icon },
  props: {
    item: {
      type: Object as PropType<TextareaField>,
      default: (): TextareaField => ({
        name: '',
        label: 'テキストフィールド',
        type: 'textarea',
        errorMessages: [],
        value: '',
        placeholder: '',
        rules: [() => true],
        options: [],
        class: [],
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
      this.isEscape = !(this.item.value === null || this.item.value === '')
      this.$emit('blur')
    },
    toggleEye() {
      this.$emit('toggleEye')
    },
  },
})
</script>
