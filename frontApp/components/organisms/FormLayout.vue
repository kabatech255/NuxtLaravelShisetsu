<template>
  <div class="FormLayout">
    <form class="FormLayout_container" @submit.prevent="onClickEvent">
      <h1 class="FormLayout_title">{{ title }}</h1>
      <div class="FormLayout_body">
        <div
          v-for="(formItem, key) in formData"
          :key="key"
          class="FormLayout_row"
          :class="none(formItem)"
        >
          <TextBox
            v-if="isText(formItem.type)"
            :item="formItem"
            :class="errorClass(formItem)"
            @toggleEye="formItem.isHidden = !formItem.isHidden"
            @onInput="validation(formItem)"
            @blur="validation(formItem)"
          />
          <TextareaBox
            v-if="formItem.type === 'textarea'"
            :item="formItem"
            :class="errorClass(formItem)"
            @toggleEye="formItem.isHidden = !formItem.isHidden"
            @onInput="validation(formItem)"
            @blur="validation(formItem)"
          />
          <RadioBox
            v-else-if="formItem.type === 'radio'"
            :item="formItem"
            :class="errorClass(formItem)"
          />
          <SelectBox
            v-else-if="formItem.type === 'select'"
            :item="formItem"
            :class="errorClass(formItem)"
            @onChange="$emit('onChange')"
          />
          <CheckBox
            v-else-if="formItem.type === 'check'"
            :item="formItem"
            :class="errorClass(formItem)"
            @change="onChecked(formItem)"
          />
          <FileInputBox
            v-if="formItem.type === 'fileList'"
            :item-list="formItem"
            :flag="filesTabFlag"
            :class="errorClass(formItem)"
            @change="validation(formItem)"
          />
          <DatePickBox v-if="formItem.type === 'date'" :item="formItem" />
          <p v-if="hasError(formItem)" class="FormLayout_errorMessage">
            {{ formItem.errorMessages[0] }}
          </p>
        </div>
      </div>
      <div class="FormLayout_row --vertical">
        <CustomButton
          v-if="hasCancel"
          type="button"
          label="キャンセル"
          :class="['--cancel', 'u-mr10']"
          @click="$emit('onCancel')"
        />
        <CustomButton
          v-if="editPermission"
          type="button"
          :label="buttonLabel"
          :class="buttonClass"
          @click="onClickEvent"
        />
      </div>
      <div v-if="hasTestAuthProp">
        <TestLoginButton
          :is-login="false"
          color-primary
          class="u-alignCenter u-pt30"
        />
      </div>
    </form>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { FormObj, FormInput, ValidateRule, FileField } from '~/types/FormObj'
import TextBox from '~/components/molecules/TextBox.vue'
import TextareaBox from '~/components/molecules/TextareaBox.vue'
import RadioBox from '~/components/molecules/RadioBox.vue'
import SelectBox from '~/components/molecules/SelectBox.vue'
import CheckBox from '~/components/molecules/CheckBox.vue'
import FileInputBox from '~/components/molecules/FileInputBox.vue'
import DatePickBox from '~/components/molecules/DatePickBox.vue'
import CustomButton from '~/components/atoms/CustomButton.vue'
import TestLoginButton from '~/components/molecules/TestLoginButton.vue'
export default Vue.extend({
  components: {
    TextBox,
    TextareaBox,
    RadioBox,
    CustomButton,
    FileInputBox,
    SelectBox,
    CheckBox,
    DatePickBox,
    TestLoginButton,
  },
  props: {
    title: {
      type: String as PropType<string>,
      default: 'ログイン',
    },
    buttonLabel: {
      type: String as PropType<string>,
      default: 'ログイン',
    },
    hasCancel: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    editPermission: {
      type: Boolean as PropType<boolean>,
      default: true,
    },
    formData: {
      type: Object as PropType<FormObj>,
      default: (): FormObj => ({
        login_id: {
          name: 'login_id',
          label: 'ログインID',
          prependIcon: 'assignment_ind',
          appendIcon: '',
          type: 'text',
          value: '',
          rules: [(v: string) => !!v || 'ログインIDは必須です'],
          errorMessages: [],
          options: ['required', 'counter'],
        },
        password: {
          name: 'password',
          label: 'パスワード',
          prependIcon: 'lock',
          appendIcon: '',
          type: 'password',
          isHidden: true,
          value: '',
          rules: [
            (v: string) => !!v || 'パスワードは必須です',
            (v: string) =>
              (v && v.length >= 8) || 'パスワードは8文字以上必要です',
          ],
          errorMessages: [],
          options: ['required', 'counter'],
        },
      }),
    },
    buttonOptions: {
      type: Array as PropType<string[]>,
      default: (): string[] => [''],
    },
    filesTabFlag: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  data: () => ({
    isShow: false as boolean,
    valid: true,
    disabledButton: true as boolean,
    preValidation: true as boolean,
  }),
  computed: {
    textType(): string {
      return this.isShow ? 'text' : 'password'
    },
    validationAll(): boolean {
      return (
        Object.keys(this.formData).filter(
          (key: string): boolean =>
            this.errorFiltering(this.formData[key]).length > 0
        ).length === 0
      )
    },
    buttonClass(): string[] {
      return this.disabledButton
        ? this.buttonOptions.concat(['--disabled'])
        : this.buttonOptions
    },
    hasTestAuthProp(): boolean {
      return Object.keys(this.formData).includes('testLogin')
    },
  },
  watch: {
    validationAll: {
      handler(val) {
        this.disabledButton = !val
      },
    },
  },
  mounted() {
    this.disabledButton = !this.validationAll
  },
  methods: {
    onClickEvent(): void | boolean {
      Object.keys(this.formData).forEach((key: string) => {
        this.validation(this.formData[key])
      })
      if (this.validationAll) {
        this.$emit('click')
      } else {
        return false
      }
    },
    isText(formType: string): boolean {
      return formType === 'text' || formType === 'password'
    },
    byType(formType: string): boolean {
      return formType === 'password'
    },
    onChecked(formItem: FormInput): void {
      this.validation(formItem)
      this.$emit('onCheck')
    },
    validation(formItem: FormInput): boolean {
      formItem.errorMessages = this.errorFiltering(formItem)
      return formItem.errorMessages.length === 0
    },
    errorFiltering(formItem: FormInput): (boolean | string)[] {
      if (formItem.type === 'fileList') {
        return formItem.list.flatMap((item: FileField) =>
          item.rules
            .map((validationFn: ValidateRule) => validationFn(item.value))
            .filter((result: string | boolean) => typeof result === 'string')
        )
      } else if (formItem.name.includes('_confirmation')) {
        const compareIndex: string = formItem.name.replace('_confirmation', '')
        return formItem.rules
          .map((validationFn: ValidateRule) =>
            validationFn([formItem.value, this.formData[compareIndex].value])
          )
          .filter((result: string | boolean) => typeof result === 'string')
      } else {
        return formItem.rules
          .map((validationFn: ValidateRule) => validationFn(formItem.value))
          .filter((result: string | boolean) => typeof result === 'string')
      }
    },
    hasError(formItem: FormInput): boolean {
      return formItem.errorMessages.length > 0
    },
    errorClass(formItem: FormInput): object {
      return {
        '--error': this.hasError(formItem),
      }
    },
    none(formItem: FormInput): string[] {
      return Object.prototype.hasOwnProperty.call(formItem, 'class') &&
        formItem.class.some(
          (className: string): boolean => className === '--none'
        )
        ? ['--none']
        : []
    },
  },
})
</script>
