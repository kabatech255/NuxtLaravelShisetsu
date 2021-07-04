<template>
  <div class="p-analysis">
    <div class="u-fill">
      <div class="p-analysis-row --fullColumn">
        <div class="p-analysis-col --fixed">
          <RowVisual image-path="assets/img/analysis_flat.svg" />
        </div>
        <div class="p-analysis-col --fill">
          <client-only>
            <nav class="p-analysis-nav">
              <ul class="p-analysis-menu">
                <li class="p-analysis-menuItem --flex">
                  <BreadCrumb
                    :path-list="breadcrumbs"
                    :class="['--borderless', '--paddless']"
                  />
                </li>
                <li class="p-analysis-menuItem">
                  <SelectBox
                    :item="examItem"
                    :class="['--head']"
                    @onChange="changeChart"
                  />
                </li>
              </ul>
            </nav>
            <ChartLayout
              :chart-data="chartData"
              :current-exam="currentExam"
              @onSortShopTable="sortShopRanks"
            />
          </client-only>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import IconMono from '~/components/atoms/IconMono.vue'
import CoreUiCustom from '~/components/atoms/CoreUiCustom.vue'
import WhiteCard from '~/components/atoms/WhiteCard.vue'
import ChartLayout from '~/components/organisms/ChartLayout.vue'
import AnalysisNav from '~/components/organisms/AnalysisNav.vue'
import BreadCrumb from '~/components/molecules/BreadCrumb.vue'
import SelectBox from '~/components/molecules/SelectBox.vue'
import { SelectField } from '~/types/FormObj'
import { HeaderItem } from '~/types/Header'
import { localTitle } from '~/plugins/util'
import { BreadCrumbObj } from '~/types/BreadCrumbs'
import RowVisual from '~/components/molecules/RowVisual.vue'
import PageMixin from '~/mixins/PageMixin'

@Component({
  layout: 'MyPageLayout',

  components: {
    IconMono,
    CoreUiCustom,
    WhiteCard,
    ChartLayout,
    AnalysisNav,
    BreadCrumb,
    SelectBox,
    RowVisual,
  },
  mixins: [PageMixin],

  head: () => ({
    title: localTitle('集計'),
    head: [
      {
        hid: 'description',
        name: 'description',
        content: '施設検査サイトの集計ページです。',
      },
    ],
  }),
})
export default class Analysis extends Vue {
  @Prop({ type: Object, default: {} })
  author!: object

  @Prop({ type: Object, default: () => ({}) })
  colors!: object

  @Prop({ type: Number, default: 1 })
  currentExamCode!: number

  @Prop({ type: [Array, Object], default: null })
  examList!: any

  @Prop({
    type: Object,
    default: () => ({
      summary: null,
      rateOfImprove: null,
      perIssue: null,
      perIssueDetail: null,
      perBranch: null,
      improvedRatesPerBranch: null,
      worries: null,
      shopRanks: null,
    }),
  })
  chartData!: any

  @Prop({
    type: Object,
    default: () => ({
      name: '',
      label: '',
      type: 'select',
      labelKey: 'name',
      value: '',
      list: [],
      rules: [() => true],
      errorMessages: [],
      options: [],
      prependIcon: '',
    }),
  })
  examItem!: SelectField

  shopRanks: any = null

  headerMenu: HeaderItem[] = [
    {
      label: '月別推移',
      iconSrc: 'date_range',
      to: '#monthly',
      offset: -60,
    },
    {
      label: '指摘項目別',
      iconSrc: 'align_horizontal_left',
      to: '#per_issue',
      offset: -60,
    },
    {
      label: '支社別平均',
      iconSrc: 'business',
      to: '#per_branch',
      offset: -60,
    },
    {
      label: '今月のブックマーク上位',
      iconSrc: 'bookmarks',
      to: '#bookmarks',
      offset: -60,
    },
    {
      label: '店舗別一覧',
      iconSrc: 'store',
      to: '#shops',
      offset: -60,
    },
  ]

  async changeChart(exam: any): Promise<void> {
    this.$store.commit('loader/setIsLoader', true)
    await this.$emit('onChangeChart', exam)
  }

  sortShopRanks(field: any) {
    field.sort_status = field.sort_status === 'asc' ? 'desc' : 'asc'
    this.chartData.shopRanks.items.sort((prev: any, next: any) => {
      return field.sort_status === 'desc'
        ? next[field.key] - prev[field.key]
        : prev[field.key] - next[field.key]
    })
  }

  get currentExam(): null | object {
    return !this.examList
      ? {
          color: '#ea6254',
          name: '防災',
        }
      : this.examList[this.currentExamCode]
  }

  get currentExamColor(): string | null {
    return !this.examList ? null : this.examList[this.currentExamCode].color
  }

  get breadcrumbs(): BreadCrumbObj[] {
    return [
      {
        name: 'ページトップ',
        path: '/',
      },
      {
        name: 'マイページ',
        path: '/mypage',
      },
      {
        name: '集計',
        path: this.$route.fullPath,
      },
    ]
  }
}
</script>
