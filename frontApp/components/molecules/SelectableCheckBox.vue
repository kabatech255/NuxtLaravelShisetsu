<template>
  <div>
    <div
      v-for="(member, index) in item.list"
      :key="index"
      class="SelectableCheckBox"
    >
      <div class="SelectableCheckBox_wrap">
        <input
          :id="`shared_${member[item.valueKey]}`"
          v-model="item.value"
          :value="member"
          type="checkbox"
          class="SelectableCheckBox_body"
          @change="$emit('onChange')"
        />
        <Icon
          name="done"
          class="SelectableCheckBox_done"
          :class="doneCheck(member)"
        />
      </div>
      <label
        :for="`shared_${member[item.valueKey]}`"
        class="SelectableCheckBox_label"
      >
        {{ member[item.labelKey] }}
      </label>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import { CheckField } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'
@Component({
  components: { Icon },
})
export default class SelectableCheckBox extends Vue {
  @Prop({ type: Object, default: (): CheckField => ({}) })
  item?: CheckField

  doneCheck(member: any): object {
    return {
      '--done': this.item.value.some(
        (m: any): boolean =>
          m[this.item.valueKey] === member[this.item.valueKey]
      ),
    }
  }
}
</script>
