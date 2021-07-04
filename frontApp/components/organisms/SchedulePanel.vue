<template>
  <div id="schedules" class="SchedulePanel">
    <BasePanel
      :title="title"
      event-content="予定を登録する"
      @onAppend="prepareAdd"
    >
      <div slot="content" class="BasePanel_row">
        <CustomCalendar
          :schedules="schedules"
          @onEdit="prepareEdit"
          @onDayClick="prepareAdd"
          @onDelete="prepareDelete"
        />
      </div>
    </BasePanel>
    <Modal :is-show="isShow" @onCancel="closeEditor">
      <div slot="modalContent" class="SchedulePanel_form" :style="heightStyle">
        <h2 class="SchedulePanel_formTitle">スケジュールの{{ submitLabel }}</h2>
        <ul>
          <li class="SchedulePanel_formRow">
            <TextBox
              :item="scheduleFormData.body"
              :class="['--only', errorClass(scheduleFormData.body)]"
              @onInput="validation(scheduleFormData.body)"
              @blur="validation(scheduleFormData.body)"
            />
            <p
              v-if="hasError(scheduleFormData.body)"
              class="SchedulePanel_errorMessage"
            >
              {{ scheduleFormData.body.errorMessages[0] }}
            </p>
          </li>
          <li class="SchedulePanel_formRow">
            <DatePickBox :item="scheduleFormData.newScheduleForm" />
          </li>
          <li class="SchedulePanel_formRow">
            <h3>予定の色</h3>
            <ul class="SchedulePanel_colorList">
              <li
                v-for="(colorItem, index) in scheduleFormData.color.list"
                :key="index"
                class="SchedulePanel_colorItem"
              >
                <button class="SchedulePanel_colorButton">
                  <input
                    :id="colorItem.name"
                    v-model="scheduleFormData.color.value"
                    type="radio"
                    :value="colorItem.name"
                    class="SchedulePanel_colorBox"
                    :style="bgColor(colorItem.code)"
                  />
                  <label
                    :for="colorItem.name"
                    class="SchedulePanel_colorLabel"
                    >{{ colorItem.name }}</label
                  >
                </button>
              </li>
            </ul>
          </li>
          <li class="SchedulePanel_formRow">
            <h3>公開・非公開</h3>
            <div class="SchedulePanel_formBox">
              <div class="SelectableCheckBox --short">
                <div class="SelectableCheckBox_wrap">
                  <input
                    id="is_private"
                    v-model="scheduleFormData.is_private.value"
                    :value="scheduleFormData.is_private.value"
                    type="checkbox"
                    class="SelectableCheckBox_body"
                  />
                  <Icon
                    name="done"
                    class="SelectableCheckBox_done"
                    :class="{ '--done': scheduleFormData.is_private.value }"
                  ></Icon>
                </div>
                <label for="is_private" class="SelectableCheckBox_label">
                  非公開にする
                </label>
              </div>
            </div>
          </li>
          <li class="SchedulePanel_formRow">
            <h3>共有するメンバー</h3>
            <div class="SchedulePanel_formBox">
              <div class="SchedulePanel_boxCol">
                <SelectableCheckBox
                  :item="scheduleFormData.shared_members"
                  @onChange="$emit('onSharedChange')"
                />
              </div>
            </div>
          </li>
          <li class="SchedulePanel_formRow">
            <h3>追加されたメンバー</h3>
            <div class="SchedulePanel_formBox">
              <div class="SchedulePanel_boxCol">
                <div
                  v-for="(member, index) in scheduleFormData.editable_members
                    .list"
                  :key="index"
                  class="SelectableCheckBox --sm"
                >
                  <span class="u-mr15">
                    {{ member.name }}
                  </span>
                  <div class="SelectableCheckBox_wrap">
                    <input
                      :id="`editable_${member.employee_id}`"
                      v-model="scheduleFormData.editable_members.value"
                      :value="member"
                      type="checkbox"
                      class="SelectableCheckBox_body"
                      :data-label="member.name"
                    />
                    <Icon
                      name="done"
                      class="SelectableCheckBox_done"
                      :class="checked(member)"
                    ></Icon>
                  </div>
                  <label
                    :for="`editable_${member.employee_id}`"
                    class="SelectableCheckBox_label"
                  >
                    編集権限を付与する
                  </label>
                </div>
              </div>
            </div>
          </li>
          <li class="SchedulePanel_formRow u-alignCenter">
            <CustomButton
              type="button"
              label="キャンセル"
              :class="['--cancel', 'u-mr10']"
              @click="closeEditor"
            />
            <CustomButton
              :label="submitLabel"
              type="button"
              :class="buttonClass"
              @click="onAdd"
            />
          </li>
        </ul>
      </div>
    </Modal>
    <Modal :is-show="isShowDelete" @onCancel="clear">
      <CancelAlertBox
        slot="modalContent"
        :button-options="['--error']"
        cancel-button-label="戻る"
        @onCancel="clear"
        @onExec="execDelete"
      >
        <p slot="CancelAlertBox_message" class="CancelAlertBox_message">
          一度削除すると元に戻せません。<br />削除してよろしいですか？
        </p>
      </CancelAlertBox>
    </Modal>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from 'nuxt-property-decorator'
import BasePanel from '~/components/molecules/BasePanel.vue'
import Modal from '~/components/organisms/Modal.vue'
import CustomCalendar from '~/components/organisms/CustomCalendar.vue'
import CancelAlertBox from '~/components/molecules/CancelAlertBox.vue'
import DatePickBox from '~/components/molecules/DatePickBox.vue'
import { BasePanelTitle, ScheduleObj } from '~/types/Dashboard'
import CustomButton from '~/components/atoms/CustomButton.vue'
import SelectableCheckBox from '~/components/molecules/SelectableCheckBox.vue'
import { FormInput, FormObj, ValidateRule } from '~/types/FormObj'

import Icon from '~/components/atoms/Icon.vue'
import { formatedScheduleDate } from '~/plugins/util'

@Component({
  components: {
    BasePanel,
    Modal,
    DatePickBox,
    CancelAlertBox,
    CustomButton,
    SelectableCheckBox,
    CustomCalendar,
    Icon,
  },
})
export default class SchedulePanel extends Vue {
  @Prop({ type: Object, default: () => ({}) })
  schedules?: ScheduleObj[]

  @Prop({ type: Object, required: true }) scheduleFormData!: FormObj

  @Watch('validationAll')
  handler(val: boolean): void {
    this.disabledButton = !val
  }

  title: BasePanelTitle = {
    id: 'schedules',
    value: 'Schedule',
    prependIcon: '',
    appendIcon: 'add_circle_outline',
    color: 'orange',
    isEdit: false,
  }

  isShow: boolean = false
  isShowDelete: boolean = false
  modalHeight: string = '200px'
  currentScheduleId: number | null = null
  disabledButton: boolean = true
  buttonOptions: string[] = []
  submitLabel: string = '登録'

  bgColor(colorCode: string): object {
    return {
      backgroundColor: colorCode,
    }
  }

  get buttonClass(): string[] {
    return this.disabledButton
      ? this.buttonOptions.concat(['--disabled'])
      : this.buttonOptions
  }

  get validationAll(): boolean {
    return (
      Object.keys(this.scheduleFormData).filter(
        (key: string): boolean =>
          this.errorFiltering(this.scheduleFormData[key]).length > 0
      ).length === 0
    )
  }

  get heightStyle(): object {
    return {
      maxHeight: this.modalHeight,
    }
  }

  mounted() {
    const height: number = window.innerHeight - 180
    this.modalHeight = `${height}px`
  }

  validation(formItem: FormInput): boolean {
    formItem.errorMessages = this.errorFiltering(formItem)
    return formItem.errorMessages.length === 0
  }

  errorFiltering(formItem: FormInput): (boolean | string)[] {
    if (formItem.name.includes('_confirmation')) {
      const compareIndex: string = formItem.name.replace('_confirmation', '')
      return formItem.rules
        .map((validationFn: ValidateRule) =>
          validationFn([
            formItem.value,
            this.scheduleFormData[compareIndex].value,
          ])
        )
        .filter((result: string | boolean) => typeof result === 'string')
    } else {
      return formItem.rules
        .map((validationFn: ValidateRule) => validationFn(formItem.value))
        .filter((result: string | boolean) => typeof result === 'string')
    }
  }

  hasError(formItem: FormInput): boolean {
    return formItem.errorMessages.length > 0
  }

  errorClass(formItem: FormInput): object {
    return {
      '--error': this.hasError(formItem),
    }
  }

  onAdd() {
    Object.keys(this.scheduleFormData).forEach((key: string) => {
      this.validation(this.scheduleFormData[key])
    })
    if (this.validationAll) {
      this.isShow = false
      this.$emit('onAdd')
    } else {
      return false
    }
  }

  prepareAdd(day: any = null): void {
    this.submitLabel = '登録'
    this.isShow = true
    let startDisp: string = formatedScheduleDate()
    let endDisp: string = formatedScheduleDate()

    if (day !== null) {
      const currentHour = new Date().getHours()
      const currentMinute = Math.floor(new Date().getMinutes() / 10) * 10
      startDisp =
        day.id.replace(/-/g, '/') + `\n${currentHour}:${currentMinute}:00`
      endDisp =
        day.id.replace(/-/g, '/') + `\n${currentHour}:${currentMinute}:00`
    }
    this.$emit('onPrepare', {
      customData: {
        startDisp,
        endDisp,
        body: '',
        colorName: 'blue',
        is_private: false,
        shared_members: [],
        editable_members: [],
      },
    })
  }

  prepareEdit(dateAttr: ScheduleObj): void {
    this.submitLabel = '更新'
    this.isShow = true
    this.$emit('onPrepare', dateAttr, dateAttr.customData.id)
  }

  closeEditor(): void {
    this.isShow = false
  }

  prepareDelete(dateAttr: ScheduleObj): void {
    this.toggleDeleteModal()
    this.currentScheduleId = dateAttr.customData.id
  }

  clear(): void {
    this.toggleDeleteModal()
    this.currentScheduleId = null
  }

  async execDelete() {
    this.toggleDeleteModal()
    await this.$emit('onDelete', this.currentScheduleId)
  }

  toggleDeleteModal(): void {
    this.isShowDelete = !this.isShowDelete
  }

  checked(member: any, isEditable: boolean = true): object {
    const arr = isEditable
      ? this.scheduleFormData.editable_members
      : this.scheduleFormData.shared_members
    return {
      '--done': arr.value.some(
        (m: any): boolean => m.employee_id === member.employee_id
      ),
    }
  }
}
</script>
