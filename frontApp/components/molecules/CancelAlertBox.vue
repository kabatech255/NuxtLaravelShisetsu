<template>
  <div class="CancelAlertBox">
    <slot name="CancelAlertBox_message"></slot>
    <div class="HorizontalLayout --justifyCenter">
      <CustomButton
        :label="cancelButtonLabel"
        :class="['--cancel']"
        @click="$emit('onCancel')"
      />
      <CustomButton
        v-if="!execed"
        :label="execButtonLabel"
        class="u-ml15"
        :class="buttonOptions"
        @click="$emit('onExec')"
      />
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import CustomButton from '~/components/atoms/CustomButton.vue'
export default Vue.extend({
  components: { CustomButton },
  props: {
    buttonOptions: {
      type: Array as PropType<string[]>,
      default: (): string[] => [],
    },
    execButtonLabel: {
      type: String as PropType<string>,
      default: '削除',
    },
    cancelButtonLabel: {
      type: String as PropType<string>,
      default: 'キャンセル',
    },
    execed: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
})
</script>
