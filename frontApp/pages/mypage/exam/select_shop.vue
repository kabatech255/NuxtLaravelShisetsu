<template>
  <div class="p-select-shop">
    <div class="p-select-shop-row --fixed">
      <RowVisual
        :class="['--hasContent']"
        image-path="assets/img/exam3_slice.jpg"
      />
    </div>
    <div class="p-select-shop-row --fixed">
      <BreadCrumb :path-list="pathList" />
    </div>
    <div class="p-select-shop-row --fill">
      <div class="Container">
        <div class="u-py15">
          <FormLayout
            :form-data="{ keywordForm, shopForm }"
            title="店舗検索"
            button-label="検査スタート"
            @click="checkStart"
          />
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { mapGetters } from 'vuex'
import RowVisual from '~/components/molecules/RowVisual.vue'
import FormLayout from '~/components/organisms/FormLayout.vue'
import BreadCrumb from '~/components/molecules/BreadCrumb.vue'
import { RadioField, TextField } from '~/types/FormObj'
import { Author } from '~/types/Author'
import { ShopObj } from '~/types/ShopObj'
import { localTitle } from '~/plugins/util'
import PageMixin from '~/mixins/PageMixin'

interface Data {
  keywordForm: TextField
  shopForm: RadioField
  [k: string]: any
}
export type ChildArgObj = {
  item: object
}
export default Vue.extend({
  layout: 'MyPageLayout',
  components: { RowVisual, FormLayout, BreadCrumb },
  mixins: [PageMixin],
  props: {
    author: {
      type: Object as PropType<Author>,
      default: (): Author => ({
        created_at: '',
        deleted_at: null,
        employee_id: 0,
        file_name: null,
        id: 0,
        name: '',
        team_code: 0,
        updated_at: '',
        user: {
          created_at: '',
          deleted_at: null,
          department_code: 0,
          email: '',
          updated_at: '',
        },
      }),
    },
  },
  fetch() {
    this.searchShop()
  },
  fetchOnServer: false,
  async asyncData(app: any): Promise<any> {
    const shopList: any = await app.$axios.$get('/shops').catch((): void => {})
    shopList.forEach((shop: ShopObj): void => {
      shop.label = `${shop.zerofill_code}\n${shop.name}`
    })
    return { shopList }
  },
  data: (): Data => ({
    keywordForm: {
      name: 'keyword',
      label: 'キーワード',
      type: 'text',
      value: '',
      rules: [(): boolean => true],
      placeholder: '店舗名または店舗番号',
      errorMessages: [],
      prependIcon: 'search',
    } as TextField,
    shopForm: {
      name: 'store_code',
      label: '選択中の店舗',
      type: 'radio',
      value: '',
      keys: ['store_code', 'label'],
      list: [],
      verticalFixed: true,
      rules: [
        (shop: ShopObj): boolean | string => !!shop || '店舗を選択してください',
      ],
      errorMessages: [],
      loader: false,
    } as RadioField,
    error: null as any,
    result: '' as any,
    pathList: [
      {
        name: 'TOP',
        path: '/',
      },
      {
        name: 'マイページ',
        path: '/mypage',
      },
      {
        name: '店舗選択',
        path: '/mypage/exam/select_shop',
      },
    ] as object[],
  }),
  computed: {
    ...mapGetters({
      examApi: 'exam/currentExamApi',
    }),
    keyword(): string {
      return this.keywordForm.value
    },
  },
  watch: {
    keyword: {
      async handler(): Promise<any> {
        this.shopForm.loader = true
        await this.searchShop()
        this.shopForm.loader = false
      },
      deep: true,
    },
  },
  methods: {
    searchShop(): void {
      this.shopForm.list = this.shopList.filter(
        (shop: ShopObj): boolean =>
          String(shop.store_code).includes(this.keyword) ||
          shop.name.includes(this.keyword)
      )
    },
    async checkStart(): Promise<boolean | void> {
      if (!this.shopForm.value) {
        return false
      } else {
        await this.start()
      }
    },
    async start(): Promise<void> {
      const now = new Date()
      const submitData = {
        examined_year: now.getFullYear(),
        examined_month: now.getMonth() + 1,
        store_code: this.shopForm.value.store_code,
      }
      await this.$store.dispatch('exam/startExam', submitData)
      this.$router.push({
        name: 'mypage-exam',
        query: { store_code: submitData.store_code },
        params: {
          before: {
            name: '店舗選択',
            path: this.$route.fullPath,
          },
        },
      })
    },
  },

  head: () => ({
    title: localTitle('店舗選択'),
    head: [
      {
        hid: 'description',
        name: 'description',
        content: '施設検査サイトの店舗選択画面です。',
      },
    ],
  }),
})
</script>
<style lang="scss">
.Box {
  position: relative;
  .Box_side {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 20px 0;
    transform: translate3d(0, calc(100% + 10px), 0);
    background: #eeeeee;
  }
}
</style>
