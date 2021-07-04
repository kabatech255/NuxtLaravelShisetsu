<template>
  <div class="ChartLayout">
    <div class="ChartLayout_row">
      <div class="ChartLayout_col u-col-12">
        <ChartCard
          id="monthly"
          :panel-color="currentExamColor"
          :class="['--thin']"
        >
          <div
            v-if="chartData.summary === null"
            slot="chart-card-panel"
            class="ChartCard_loader"
          >
            <vue-loaders
              name="ball-spin-fade-loader"
              color="#fefefe"
            ></vue-loaders>
          </div>
          <CChartBar
            v-else
            slot="chart-card-panel"
            class="ChartCard_graph"
            :datasets="chartData.summary.datasets"
            :labels="chartData.summary.labels"
            :options="chartData.summary.options"
          />
          <div slot="chart-card-desc">
            <div v-if="chartData.summary === null" class="u-py20 u-alignCenter">
              <vue-loaders
                name="ball-spin-fade-loader"
                color="#aaaaaa"
              ></vue-loaders>
            </div>
            <div v-else class="ChartCard_descRow u-mt20">
              <div class="ChartCard_descCol u-sm-col-12 u-col-8">
                <SummaryDescription
                  :written-by="chartData.summary.writtenBy"
                  :value="chartData.summary.description"
                />
              </div>
              <div class="ChartCard_descCol u-sm-col-12 u-col-4 --fix">
                <DoughnutChartLayout
                  :datasets="chartData.rateOfImprove.datasets"
                  :labels="chartData.rateOfImprove.labels"
                  :options="chartData.rateOfImprove.options"
                  :center-label="chartData.rateOfImprove.centerLabel"
                  :center-value="chartData.rateOfImprove.improved_rate"
                  center-unit="％"
                />
              </div>
            </div>
          </div>
        </ChartCard>
      </div>
    </div>
    <div class="ChartLayout_row">
      <div class="ChartLayout_col --alignStretch u-col-6 u-lg-col-12">
        <ChartCard id="per_issue" :panel-color="currentExamColor">
          <div
            v-if="chartData.perIssue === null"
            slot="chart-card-panel"
            class="ChartCard_loader"
          >
            <vue-loaders
              name="ball-spin-fade-loader"
              color="#fefefe"
            ></vue-loaders>
          </div>
          <CChartHorizontalBar
            v-else
            slot="chart-card-panel"
            width="100%"
            :style="chartData.perIssue.style"
            :datasets="chartData.perIssue.datasets"
            :labels="chartData.perIssue.labels"
            :options="chartData.perIssue.options"
          />
          <div slot="chart-card-desc">
            <h3 class="ChartCard_descTitle">指摘内容内訳</h3>
            <div
              v-if="chartData.perIssueDetail === null"
              class="u-py20 u-alignCenter"
            >
              <vue-loaders
                name="ball-spin-fade-loader"
                color="#aaaaaa"
              ></vue-loaders>
            </div>
            <div v-else class="ChartCard_descRow --stretch">
              <div
                v-for="(item, key) in chartData.perIssueDetail"
                :key="key"
                class="ChartCard_descCol u-col-4"
              >
                <DoughnutChartLayout
                  :datasets="item.datasets"
                  :labels="item.labels"
                  :options="item.options"
                  :center-label="item.year"
                  :center-value="item.month"
                  center-unit="月"
                />
              </div>
            </div>
          </div>
        </ChartCard>
      </div>
      <div class="ChartLayout_col --alignStretch u-col-6 u-lg-col-12">
        <ChartCard id="per_branch" :panel-color="currentExamColor">
          <div
            v-if="chartData.perBranch === null"
            slot="chart-card-panel"
            class="ChartCard_loader"
          >
            <vue-loaders
              name="ball-spin-fade-loader"
              color="#fefefe"
            ></vue-loaders>
          </div>
          <CChartHorizontalBar
            v-else
            slot="chart-card-panel"
            width="100%"
            :style="chartData.perBranch.style"
            :datasets="chartData.perBranch.datasets"
            :labels="chartData.perBranch.labels"
            :options="chartData.perBranch.options"
          />
          <div slot="chart-card-desc">
            <h3 class="ChartCard_descTitle">支社別改善率（直近12ヶ月分）</h3>
            <div
              v-if="chartData.improvedRatesPerBranch === null"
              class="u-py20 u-alignCenter"
            >
              <vue-loaders
                name="ball-spin-fade-loader"
                color="#aaaaaa"
              ></vue-loaders>
            </div>
            <div v-else class="ChartCard_descRow --vertical">
              <div
                v-for="(improvedData,
                index) in chartData.improvedRatesPerBranch"
                :key="index"
                class="ChartCard_descCol --fix u-sm-col-6 u-lg-col-3 u-col-4"
              >
                <DoughnutChartLayout
                  :datasets="improvedData.datasets"
                  :labels="improvedData.labels"
                  :options="improvedData.options"
                  :center-label="improvedData.branch_name"
                  :center-value="improvedData.improved_rate"
                  :center-status="status(improvedData.improved_rate)"
                  center-unit="％"
                />
              </div>
            </div>
          </div>
        </ChartCard>
      </div>
    </div>
    <div v-if="chartData.worries === null" class="u-py20 u-alignCenter">
      <vue-loaders name="ball-spin-fade-loader" color="#aaaaaa"></vue-loaders>
    </div>
    <div
      v-else-if="chartData.worries !== undefined"
      id="bookmarks"
      class="ChartLayout_row --alignStart"
    >
      <div
        v-for="(worry, index) in chartData.worries"
        :key="index"
        class="ChartLayout_col u-sm-col-12 u-md-col-6 u-lg-col-4 u-col-3"
      >
        <ChartCard :panel-color="currentExamColor" class="--badge">
          <p slot="chart-card-panel" class="ChartCard_badgeValue">
            {{ worry.worries_count }}
          </p>
          <div slot="chart-card-desc" class="ChartCard_descWrap">
            <div class="ChartCard_right">
              <span class="u-fontBold u-mb5">{{ worry.body }}</span>
              <RouterLink
                :to="`/mypage/exam?store_code=${worry.shop.store_code}`"
                tag="a"
                class="u-fs12 u-primaryLink"
                >（{{ worry.shop.name }}）
              </RouterLink>
            </div>
            <div class="ChartCard_body">
              <div class="ChartCard_image">
                <img :src="worry.primary_file_path" :alt="worry.body" />
              </div>
            </div>
            <div class="ChartCard_bottom">
              <div class="HorizontalLayout --vertical">
                <div class="HorizontalLayout_col u-pr10">
                  <span class="u-fs12 u-fontBold"
                    >検査者: {{ worry.creator.name }}</span
                  >
                </div>
                <div class="HorizontalLayout_col"></div>
              </div>
            </div>
          </div>
        </ChartCard>
      </div>
    </div>
    <div class="ChartLayout_row">
      <div class="ChartLayout_col u-col-12">
        <ChartCard id="shops" :panel-color="currentExamColor" class="--bar">
          <div
            v-if="chartData.shopRanks === null"
            slot="chart-card-panel"
            class="ChartCard_loader"
          >
            <vue-loaders
              name="ball-spin-fade-loader"
              color="#fefefe"
            ></vue-loaders>
          </div>
          <div v-else slot="chart-card-panel">
            <h3 style="font-size: 16px">店舗別指摘総数一覧</h3>
            <p style="margin-top: 10px; font-size: 12px">
              {{ currentExamName }}
            </p>
          </div>
          <div slot="chart-card-desc" class="ChartCard_descWrap">
            <div class="ChartCard_bottom">
              <div
                v-if="chartData.shopRanks === null"
                class="u-py20 u-alignCenter"
              >
                <vue-loaders
                  name="ball-spin-fade-loader"
                  color="#aaaaaa"
                ></vue-loaders>
              </div>
              <CustomTable
                v-else
                :fields="chartData.shopRanks.fields"
                :items="chartData.shopRanks.items"
                align-center
                lattice
                sort
                stripe
                @onSort="execSort"
              />
            </div>
          </div>
        </ChartCard>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import ChartCard from '~/components/molecules/ChartCard.vue'
import CustomTable from '~/components/molecules/CustomTable.vue'
import SummaryDescription from '~/components/molecules/SummaryDescription.vue'
import DoughnutCenter from '~/components/atoms/DoughnutCenter.vue'
import DoughnutChartLayout from '~/components/molecules/DoughnutChartLayout.vue'
import { colorList } from '~/plugins/util'
import { MonthlyLogDetail } from '~/types/MonthlyLogDetail'

@Component({
  components: {
    ChartCard,
    CustomTable,
    SummaryDescription,
    DoughnutCenter,
    DoughnutChartLayout,
  },
})
export default class ChartLayout extends Vue {
  @Prop({ type: Object, default: () => ({}) })
  chartData?: object

  @Prop({
    type: Object,
    default: () => ({
      color: '',
      name: '',
    }),
  })
  currentExam?: object

  get currentExamColor(): string {
    return this.currentExam === null ? colorList.bousai : this.currentExam.color
  }

  get currentExamName(): string {
    return this.currentExam === null ? colorList.bousai : this.currentExam.name
  }

  storeName(worry: MonthlyLogDetail): string {
    return worry.shop === undefined ? '' : worry.shop.name
  }

  status(rate: number): string[] {
    const all = this.chartData.rateOfImprove.improved_rate
    if (rate < all) {
      return ['--worse']
    }
    return []
  }

  execSort(field: any): void {
    this.$emit('onSortShopTable', field)
  }
}
</script>
