import { Component, Prop, Watch, Vue } from 'nuxt-property-decorator'
import {
  formatedScheduleDate,
  reformatedScheduleDate,
  scheduleColorList,
} from '~/plugins/util'
import { FormObj } from '~/types/FormObj'
import {
  ScheduleObj,
  ScheduleData,
  ScheduleRecord,
  SharedMember,
} from '~/types/Dashboard'
import { Author } from '~/types/Author'

@Component
export default class ScheduleHandler extends Vue {
  @Prop({ type: Object, required: true }) author!: Author

  @Watch('route', { immediate: true })
  async fetchMemberList() {
    await this.fetchAllMember()
  }

  schedules: ScheduleData = {
    masks: {
      input: '',
      title: '',
      dayPopover: '',
    },
    attributes: [],
  }

  scheduleFormData: FormObj = {
    newScheduleForm: {
      name: 'schedule',
      type: 'date',
      rules: [() => true],
      errorMessages: [],
      value: null,
      modelConfig: {
        type: 'string',
        mask: 'YYYY/MM/DD HH:mm:ss',
      },
      list: [
        {
          name: 'start',
          label: '予定開始日時',
          value: formatedScheduleDate(),
          minDate: false,
        },
        {
          name: 'end',
          label: '予定終了日時',
          value: formatedScheduleDate(),
          minDate: true,
        },
      ],
      updateFlag: 0,
    },
    color: {
      name: 'color',
      label: 'カラー',
      type: 'select',
      list: scheduleColorList,
      value: 'blue',
      labelKey: 'name',
      rules: [(val: string | object) => !!val || '色は必須です'],
      errorMessages: [],
    },
    body: {
      name: 'content',
      type: 'text',
      value: '',
      label: '内容',
      placeholder: '50文字以内',
      rules: [
        (val: string) => !!val || '予定の内容は必須です',
        (val: string) => val.length <= 50 || '予定の内容は50文字までです',
      ],
      errorMessages: [],
    },
    is_private: {
      name: 'is_private',
      label: '非公開にする',
      type: 'check',
      rules: [
        (val) =>
          val !== null || typeof val === 'boolean' || '入力形式が不適切です',
      ],
      errorMessages: [],
      value: false,
    },
    shared_members: {
      name: 'shared_members',
      label: '共有者',
      type: 'select',
      labelKey: 'name',
      valueKey: 'employee_id',
      list: [],
      value: [],
      rules: [() => true],
      errorMessages: [],
    },
    editable_members: {
      name: 'editable_members',
      label: '編集権限者',
      type: 'multicheck',
      labelKey: 'name',
      valueKey: 'employee_id',
      list: [],
      value: [],
      rules: [() => true],
      errorMessages: [],
    },
  }

  hm(date: string): string {
    const d = new Date(date)
    const h: number = d.getHours()
    const mm: string = `00${d.getMinutes()}`.slice(-2)
    return `${h}:${mm}`
  }

  async fetchAllMember() {
    await this.$axios
      .$get('/schedule_member_list')
      .then((response: SharedMember[]): void => {
        // 共有したい相手の選択肢から自分を削除
        this.sliceOwner(response)
        this.scheduleFormData.shared_members.list = response
      })
      .catch((err: any) => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  fetchScheduleAttr(attr: any, updateFlag: number = 0) {
    this.scheduleFormData.newScheduleForm.list[0].value =
      attr.customData.startDisp
    this.scheduleFormData.newScheduleForm.list[1].value =
      attr.customData.endDisp
    this.scheduleFormData.body.value = attr.customData.body
    this.scheduleFormData.color.value = attr.customData.colorName
    this.scheduleFormData.is_private.value = attr.customData.is_private
    const sharedMembers = this.scheduleFormData.shared_members.list.filter(
      (member: SharedMember) =>
        attr.customData.shared_members.length > 0 &&
        attr.customData.shared_members.some(
          (attrMember: SharedMember): boolean =>
            attrMember.employee_id === member.employee_id
        )
    )
    // カレンダーには表示させてた自分をモーダルでは削除
    this.sliceOwner(sharedMembers)
    this.scheduleFormData.shared_members.value = sharedMembers
    this.scheduleFormData.editable_members.list = sharedMembers

    this.scheduleFormData.editable_members.value = this.scheduleFormData.editable_members.list.filter(
      (member: SharedMember) =>
        attr.customData.editable_members.length > 0 &&
        attr.customData.editable_members.some(
          (attrMember: SharedMember): boolean =>
            attrMember.employee_id === member.employee_id
        )
    )

    this.scheduleFormData.newScheduleForm.updateFlag = updateFlag
  }

  fetchEditableList(): void {
    this.scheduleFormData.editable_members.list = this.scheduleFormData.shared_members.value
    // ↓共有相手から外された人は、編集権限も外される
    this.scheduleFormData.editable_members.value.forEach(
      (member: SharedMember, index: number) => {
        if (
          !this.scheduleFormData.editable_members.list.some(
            (m: SharedMember) => member.employee_id === m.employee_id
          )
        ) {
          this.scheduleFormData.editable_members.value.splice(index, 1)
        }
      }
    )
  }

  async submitSchedule(): Promise<void> {
    const submitData = new FormData()
    submitData.append(
      'start',
      this.scheduleFormData.newScheduleForm.list[0].value
    )
    submitData.append(
      'end',
      this.scheduleFormData.newScheduleForm.list[1].value
    )
    submitData.append('body', this.scheduleFormData.body.value)
    submitData.append('color', this.scheduleFormData.color.value)
    submitData.append(
      'is_private',
      this.scheduleFormData.is_private.value ? 1 : 0
    )
    submitData.append(
      'shared_members',
      this.scheduleFormData.shared_members.value.map(
        (m: SharedMember) => m.employee_id
      )
    )
    submitData.append(
      'editable_members',
      this.scheduleFormData.editable_members.value.map(
        (m: SharedMember) => m.employee_id
      )
    )
    if (this.scheduleFormData.newScheduleForm.updateFlag > 0) {
      await this.updateSchedule(submitData)
    } else {
      await this.storeSchedule(submitData)
    }
  }

  async storeSchedule(submitData: any): Promise<void> {
    await this.$axios
      .$post(
        `/examinator/${this.author.employee_id}/schedule/store`,
        submitData
      )
      .then((response: ScheduleRecord): void => {
        const newSchedule = {
          key: this.schedules.attributes.length,
          customData: {
            id: response.id,
            title: `${this.hm(response.start)}\n${response.body}`,
            body: response.body,
            colorName: response.color,
            startDisp: reformatedScheduleDate(response.start),
            endDisp: reformatedScheduleDate(response.end),
            shared_members: response.shared_members,
            editable_members: response.editable_members,
            can_edit: response.can_edit,
            is_private: response.is_private,
            style: `background: ${
              scheduleColorList[response.color].code
            }; color: #fefefe`,
            isOpen: false,
          },
          dates: [
            {
              start: response.start,
              end: response.end,
            },
          ],
        }
        this.schedules.attributes.push(newSchedule)
        this.clearFormValues()
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async updateSchedule(submitData: any): Promise<void> {
    const config: any = {
      headers: {
        'content-type': 'multipart/form-data',
      },
    }
    config.headers['X-HTTP-Method-Override'] = 'PUT'
    await this.$axios
      .$post(
        `/examinator/${this.author.employee_id}/schedule/${this.scheduleFormData.newScheduleForm.updateFlag}/update`,
        submitData,
        config
      )
      .then((response: any): void => {
        this.schedules.attributes.forEach(
          (attr: ScheduleObj, index: number) => {
            if (attr.customData.id === response.id) {
              attr.customData = {
                id: response.id,
                title: `${this.hm(response.start)}\n${response.body}`,
                body: response.body,
                colorName: response.color,
                startDisp: reformatedScheduleDate(response.start),
                endDisp: reformatedScheduleDate(response.end),
                shared_members: response.shared_members,
                editable_members: response.editable_members,
                can_edit: response.can_edit,
                is_private: Number(response.is_private),
                style: `background: ${
                  scheduleColorList[response.color].code
                }; color: #fefefe`,
                isOpen: false,
              }
              attr.dates = [
                {
                  start: response.start,
                  end: response.end,
                },
              ]
              this.schedules.attributes.splice(index, 1)
              this.schedules.attributes.push(attr)
            }
          }
        )
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async deleteSchedule(scheduleId: number): Promise<void> {
    await this.$axios
      .delete(
        `/examinator/${this.author.employee_id}/schedule/${scheduleId}/delete`
      )
      .then((): void => {
        this.schedules.attributes.forEach(
          (s: ScheduleObj, index: number): void => {
            if (s.customData.id === scheduleId) {
              this.schedules.attributes.splice(index, 1)
            }
          }
        )
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  clearFormValues(): void {
    this.scheduleFormData.body.value = ''
    this.scheduleFormData.body.errorMessages = []
    this.scheduleFormData.color.value = 'blue'
    this.scheduleFormData.is_private.value = false
    this.scheduleFormData.shared_members.value = []
    this.scheduleFormData.editable_members.list = []
    this.scheduleFormData.editable_members.value = []
    this.scheduleFormData.newScheduleForm.list.forEach((item: any): void => {
      item.value = formatedScheduleDate()
    })
  }

  sliceOwner(members: Author[] | SharedMember[]) {
    members.forEach((member: Author | SharedMember, index: number): void => {
      if (this.author.employee_id === member.employee_id) {
        members.splice(index, 1)
      }
    })
  }
}
