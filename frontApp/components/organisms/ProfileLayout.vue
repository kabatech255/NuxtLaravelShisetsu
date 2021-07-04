<template>
  <div class="ProfileLayout">
    <div class="ProfileLayout_row --fill u-py15">
      <FormLayout
        :form-data="profileData"
        title="プロフィール編集"
        button-label="保存"
        :class="['--fill']"
        @click="update"
        @onCheck="togglePassword"
      />
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import FormLayout from '~/components/organisms/FormLayout.vue'
import { ExceptDateObj } from '~/types/FormObj'
import { assetPath } from '~/plugins/util'
import { Author } from '~/types/Author'
import { Department } from '~/types/Department'

@Component({
  components: { FormLayout },
})
export default class ProfileLayout extends Vue {
  @Prop({ type: Array, default: () => [] })
  departmentList?: Department[]

  @Prop({ type: Object, default: () => ({}) })
  author?: Author

  profileData: ExceptDateObj = {
    name: {
      name: 'name',
      label: '氏名',
      value: '',
      type: 'text',
      rules: [(v: string) => !!v || '氏名は必須です'],
      errorMessages: [],
      options: ['disabled'],
      class: ['--escape'],
      prependIcon: 'face',
    },
    department: {
      name: 'department',
      label: '部署',
      value: '',
      type: 'select',
      list: [],
      labelKey: 'name',
      rules: [(v: string) => !!v || '部署名は必須です'],
      errorMessages: [],
      options: ['disabled'],
      class: ['--escape'],
      prependIcon: 'corporate_fare',
    },
    file: {
      name: 'detailFileList',
      type: 'fileList',
      value: '',
      rules: [() => true],
      errorMessages: [],
      list: [
        {
          name: 'file',
          type: 'file',
          label: '顔写真',
          value: '',
          preview: '',
          rules: [() => true],
          errorMessages: [],
          accept: 'image/jpeg, image/png, image/gif',
        },
      ],
    },
    edit_pass: {
      name: 'edit_pass',
      label: 'パスワードを変更する',
      type: 'check',
      value: false,
      rules: [() => true],
      errorMessages: [],
    },
    current_password: {
      name: 'current_password',
      label: '現在のパスワード',
      isHidden: true,
      prependIcon: 'lock',
      appendIcon: '',
      type: 'password',
      value: '',
      placeholder: '',
      rules: [
        (v: string) =>
          !this.profileData.edit_pass.value ||
          (v && v.length >= 8) ||
          'パスワードは8文字以上必要です',
      ],
      errorMessages: [],
      options: ['counter'],
      class: ['--none'],
    },
    password: {
      name: 'password',
      label: '新しいパスワード',
      isHidden: true,
      prependIcon: 'lock',
      appendIcon: '',
      type: 'password',
      value: '',
      placeholder: '',
      rules: [
        (v: string) =>
          !this.profileData.edit_pass.value ||
          (v && v.length >= 8) ||
          'パスワードは8文字以上必要です',
      ],
      errorMessages: [],
      options: ['counter'],
      class: ['--none'],
    },
    password_confirmation: {
      name: 'password_confirmation',
      label: '新しいパスワード（確認）',
      isHidden: true,
      prependIcon: 'lock',
      appendIcon: '',
      type: 'password',
      value: '',
      placeholder: '',
      rules: [
        ([v, compare_v]: string[]) =>
          !this.profileData.edit_pass.value ||
          v === compare_v ||
          'パスワードが一致しません',
      ],
      errorMessages: [],
      options: ['counter'],
      class: ['--none'],
    },
  }

  togglePassword() {
    if (this.profileData.edit_pass.value) {
      this.profileData.password.class = []
      this.profileData.password_confirmation.class = []
      this.profileData.current_password.class = []
    } else {
      this.profileData.password.class.push('--none')
      this.profileData.password_confirmation.class.push('--none')
      this.profileData.current_password.class.push('--none')
      this.profileData.password.value = ''
      this.profileData.password_confirmation.value = ''
      this.profileData.current_password.value = ''
    }
  }

  mounted(): void {
    this.profileData.department.list = this.departmentList
    this.fetchProfileData()
  }

  fetchProfileData() {
    Object.keys(this.profileData).forEach((key: string): void => {
      if (key === 'department') {
        this.profileData[key].value = this.profileData.department.list.find(
          (department: any): boolean =>
            department.department_code === this.author.user.department_code
        )
      } else if (key === 'file') {
        this.profileData[key].list[0].preview =
          this.author.file_name !== null
            ? `${assetPath}/${this.author.file_name}`
            : null
      } else if (key.includes('password')) {
        this.profileData[key].value = ''
      } else {
        this.profileData[key].value = this.author[key]
      }
    })
  }

  async update() {
    if (
      this.profileData.file.list[0].value === null &&
      this.author.file_name === null
    ) {
      this.profileData.file.list[0].value = ''
    }
    const submitData = new FormData()
    Object.keys(this.profileData).forEach((key: string) => {
      if (key === 'file' && this.profileData[key].list[0].value === '') {
      } else if (key === 'file') {
        submitData.append(key, this.profileData[key].list[0].value)
      } else if (this.profileData[key].value === '') {
      } else {
        submitData.append(key, this.profileData[key].value)
      }
    })
    await this.$store.dispatch('auth/updateProfile', submitData)
  }
}
</script>
