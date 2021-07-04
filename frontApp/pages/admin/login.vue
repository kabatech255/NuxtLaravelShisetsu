<template>
  <div class="p-login">
    <FormLayout
      :form-data="loginData"
      title="管理者ログイン"
      class="u-pt30"
      @click="login"
    />
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { mapGetters } from 'vuex'
import FormLayout from '~/components/organisms/FormLayout.vue'
import { FormObj } from '~/types/FormObj'
import { localTitle } from '~/plugins/util'
import { Positions } from '~/types/Positions'
import PageMixin from '~/mixins/PageMixin'

interface Data {
  loginData: FormObj
  [k: string]: any
}

export type SubmitType = {
  login_id: string
  password: string
}

export default Vue.extend({
  layout: 'AdminLayout',
  components: { FormLayout },
  mixins: [PageMixin],
  props: {
    positions: {
      type: Object as PropType<Positions>,
      required: true,
    },
  },
  data: (): Data => ({
    loginData: {
      login_id: {
        name: 'login_id',
        label: 'ログインID',
        prependIcon: 'assignment_ind',
        appendIcon: '',
        type: 'text',
        value: '',
        placeholder: '',
        rules: [(v: string) => !!v || 'ログインIDは必須です'],
        errorMessages: [],
        options: ['required', 'counter', 'autofocus'],
      },
      password: {
        name: 'password',
        label: 'パスワード',
        isHidden: true,
        prependIcon: 'lock',
        appendIcon: '',
        type: 'password',
        value: '',
        placeholder: '',
        rules: [
          (v: string) => !!v || 'パスワードは必須です',
          (v: string) =>
            (v && v.length >= 8) || 'パスワードは8文字以上必要です',
        ],
        errorMessages: [],
        options: ['required', 'counter'],
      },
      testLogin: {
        name: 'test_login',
        label: 'ログインID',
        prependIcon: 'assignment_ind',
        appendIcon: '',
        type: 'test_auth',
        value: '',
        placeholder: '',
        rules: [() => true],
        errorMessages: [],
        options: [],
      },
    },
    rules: {
      login_id: [(v: string) => !!v || 'ログインIDは必須です'],
      password: [
        (v: string) => !!v || 'パスワードは必須です',
        (v: string) => (v && v.length >= 8) || 'パスワードは8文字以上必要です',
      ],
    },
    isShow: false,
    prevRoute: null as null | string,
  }),
  computed: {
    ...mapGetters({
      admin: 'admin/currentAdmin',
    }),
  },
  watch: {
    'positions.deviceHeight': {
      handler(val: number): void {
        if (val > 0) {
          this.$store.commit('loader/setWaiting', false)
        }
      },
      immediate: true,
    },
  },
  methods: {
    async login(): Promise<void> {
      const submitData = Object.keys(this.loginData).reduce(
        (loginObj: any, key: string): any => {
          loginObj[key] = this.loginData[key].value
          return loginObj
        },
        {}
      )
      await this.$store.dispatch('admin/login', submitData)
      if (Object.prototype.hasOwnProperty.call(this.$route.params, 'before')) {
        this.$router.push(this.$route.params.before)
      } else {
        this.$router.push({
          name: 'admin-mypage',
        })
      }
    },
  },
  head: () => ({
    title: localTitle('管理者ログイン'),
    head: [
      {
        hid: 'description',
        name: 'description',
        content: '施設検査サイトの管理者ログインページです。',
      },
    ],
  }),
})
</script>
