<template>
  <div class="DatePickBox">
    <ul class="DatePickBox_row">
      <li
        v-for="(selectItem, index) in item.list"
        :key="index"
        class="DatePickBox_col"
      >
        <v-date-picker
          v-model="selectItem.value"
          mode="dateTime"
          :model-config="item.modelConfig"
          :minute-increment="10"
          :min-date="minDateVal(index)"
          is24hr
          @popoverDidShow="(payload) => toggleFlag(selectItem)"
          @popoverDidHide="(payload) => (startFlag = false)"
        >
          <template v-slot="{ inputValue, togglePopover }">
            <div class="DatePickBox_item">
              <label
                class="DatePickBox_label"
                @click="togglePopover({ placement: 'auto-start' })"
                >{{ selectItem.label }}</label
              >
              <button
                class="DatePickBox_button"
                @click="togglePopover({ placement: 'auto-start' })"
              >
                <Icon name="calendar_today" />
              </button>
              <input :value="inputValue" class="DatePickBox_body" readonly />
            </div>
          </template>
        </v-date-picker>
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from 'nuxt-property-decorator'
import { reformatedScheduleDate } from '~/plugins/util'
import { DateField, DateItem } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'
@Component({
  components: { Icon },
})
export default class DatePickBox extends Vue {
  @Prop({ type: Object, required: true }) item!: DateField

  startFlag: boolean = false

  @Watch('startDateMs', { deep: true })
  onChangeStart(val: number, oldVal: number) {
    if (this.startFlag) {
      const diff: number = val - oldVal
      const newEndMs: number = this.endDateMs + diff
      this.item.list[1].value = `${reformatedScheduleDate(newEndMs)}:00`
    }
  }

  @Watch('endDateMs', { deep: true })
  onChangeEnd(val: number, oldVal: number) {
    if (this.startDateMs > this.endDateMs) {
      const diff: number = oldVal - val
      const newStartMs: number = this.startDateMs - diff
      this.item.list[0].value = reformatedScheduleDate(newStartMs)
    }
  }

  get startDateMs(): number {
    return Date.parse(this.item.list[0].value)
  }

  get endDateMs(): number {
    return Date.parse(this.item.list[1].value)
  }

  toggleStartEditor() {
    this.startFlag = !this.startFlag
  }

  toggleFlag(selectItem: DateItem): void {
    if (selectItem.name === 'start') {
      this.toggleStartEditor()
    }
  }

  minDateVal(index: number): any {
    return this.item.list[index].minDate
      ? new Date(this.item.list[index - 1].value)
      : false
  }
}
</script>
