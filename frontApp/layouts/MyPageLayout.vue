<template>
  <div class="MyPageLayout">
    <MessageDialog />
    <div class="PageContent --mypage">
      <div class="MyPageLayout_side">
        <Sidebar />
      </div>
      <div class="MyPageLayout_main">
        <MyPageHeader
          id="scroll_to_header"
          :header-menu="headerMenu"
          :positions="positions"
        />
        <RouterView
          :author="author"
          :positions="positions"
          :chart-data="chartData"
          :exam-item="examItem"
          :exam-list="examList"
          :current-exam-code="currentExamCode"
          @fetchHeaderList="fetchHeader"
          @onChangeChart="changeChartExam"
        />
      </div>
    </div>
    <ScrollButton to="#scroll_to_header" :class="['--barUp']">
      <CircledButton
        slot="button-content"
        :tab-index="100"
        icon-name="keyboard_arrow_up"
        :class="['--lg']"
      />
    </ScrollButton>
    <PageFilledLoader />
  </div>
</template>
<script lang="ts">
import { Component, Watch, Vue } from 'nuxt-property-decorator'
import { mapGetters } from 'vuex'
import ScrollButton from '~/components/molecules/ScrollButton.vue'
import CircledButton from '~/components/molecules/CircledButton.vue'
import PageFilledLoader from '~/components/atoms/PageFilledLoader.vue'
import MyPageHeader from '~/components/organisms/MyPageHeader.vue'
import Sidebar from '~/components/organisms/Sidebar.vue'
import MessageDialog from '~/components/atoms/MessageDialog.vue'
import { UNAUTHORIZED } from '~/plugins/util'
import LayoutMixin from '~/mixins/LayoutMixin'
import { SelectField } from '~/types/FormObj'

@Component({
  name: 'MyPageLayout',
  middleware: 'guestCheck',
  mixins: [LayoutMixin],
  components: {
    ScrollButton,
    CircledButton,
    MyPageHeader,
    Sidebar,
    PageFilledLoader,
    MessageDialog,
  },
  computed: {
    ...mapGetters({
      statusCode: 'status/status',
      author: 'auth/currentAuthor',
    }),
  },
})
export default class MyPageLayout extends Vue {
  @Watch('statusCode')
  onError(val: number): void {
    if (val === UNAUTHORIZED) {
      this.$router.push({
        name: 'login',
        params: {
          before: this.$route.fullPath,
        },
      })
    }
  }

  chartDataList: any = null
  examList: any = null
  summary: any = null
  currentExamCode: number = 1

  chartData: any = {
    summary: null,
    rateOfImprove: null,
    perIssue: null,
    perIssueDetail: null,
    perBranch: null,
    improvedRatesPerBranch: null,
    worries: null,
    shopRanks: null,
  }

  examItem: SelectField = {
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
  }

  async mounted(): Promise<void> {
    await this.fetchChartData()
    const list: any = Object.keys(this.examList).map(
      (examCode: string): object => {
        return {
          examCode,
          name: this.examList[examCode].name,
          color: this.examList[examCode].color,
        }
      }
    )
    this.examItem.value = list[0]
    this.examItem.list = list
    await this.fetchShopRanks()
  }

  async fetchChartData(): Promise<void> {
    await this.$axios
      .$get(`/analysis/${this.currentExamCode}`)
      .then((response) => {
        this.chartDataList = response.chartData
        this.examList = response.examList
        this.summary = response.summary
        this.setChartData()
      })
      .catch((err: any): any => {
        return err.response
      })
  }

  async fetchShopRanks(): Promise<void> {
    await this.$axios
      .$get(`/analysis/shop_ranks/${this.currentExamCode}`)
      .then((response) => {
        this.chartData.shopRanks = response
      })
      .catch((err) => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async changeChartExam(exam: any): Promise<void> {
    this.chartData.shopRanks = null
    this.currentExamCode = Number(exam.examCode)
    await this.fetchChartData().then(() => {
      this.$store.commit('loader/setIsLoader', false)
    })
    await this.fetchShopRanks()
  }

  setChartData(): void {
    this.chartData.summary = this.fetchSummary()
    this.chartData.rateOfImprove = this.fetchImprovedRate()
    this.chartData.perIssue = this.fetchPerIssue()
    this.chartData.perIssueDetail = this.fetchPerIssueDetail()
    this.chartData.perBranch = this.fetchPerBranch()
    this.chartData.improvedRatesPerBranch = this.fetchImprovedRatePerBranch()
    this.chartData.worries = this.chartDataList.worries
  }

  fetchSummary(): object {
    return {
      description: this.summary.description,
      writtenBy: this.summary.written_by,
      datasets: [
        // 指摘数推移
        {
          data: this.chartDataList.summary.datasets.total,
          backgroundColor: 'rgba(254, 254, 254, 0.8)',
          label: '指摘総数',
          // 改善済のグラフを前面にする
          order: 1,
          yAxisID: 'y-1',
        },
        // 前月比
        {
          data: this.chartDataList.summary.datasets.compare,
          label: '前月比（％）',
          type: 'line',
          lineTension: 0,
          fill: false,
          backgroundColor: 'rgba(0, 0, 0, 0)',
          borderColor: 'rgba(0, 0, 0, 0)',
          order: 2,
          yAxisID: 'y-2',
        },
        // 改善数
        {
          data: this.chartDataList.summary.datasets.improvedCount,
          type: 'line',
          label: '改善数',
          lineTension: 0,
          backgroundColor: 'rgba(254, 254, 254, 0.5)',
          borderColor: 'rgba(254, 254, 254, 0.8)',
          // このグラフを前面にする
          order: 3,
          yAxisID: 'y-1',
        },
      ],
      labels: this.chartDataList.summary.labels,
      options: {
        title: {
          display: true,
          text: '月別指摘総数推移',
          fontColor: 'rgba(254, 254, 254, 1)',
          fontSize: 12,
        },
        layout: {
          padding: {
            top: 10,
            right: 0,
            bottom: 10,
            left: 10,
          },
        },
        legend: {
          display: true,
          labels: {
            fontColor: 'rgba(254, 254, 254, 1)',
          },
        },
        scales: {
          yAxes: [
            {
              id: 'y-1',
              gridLines: {
                color: 'rgba(254, 254, 254, 0.2)',
                zeroLineColor: 'rgba(254, 254, 254, 0.2)',
              },
              // labelsのフォントカラー
              ticks: {
                fontColor: 'rgba(254, 254, 254, 1)',
                suggestedMin: 0,
              },
              position: 'left',
            },
            {
              id: 'y-2',
              gridLines: {
                color: 'rgba(254, 254, 254, 0)',
                zeroLineColor: 'rgba(254, 254, 254, 0)',
              },
              ticks: {
                fontColor: 'rgba(254, 254, 254, 0)',
                suggestedMin: 0,
              },
              position: 'right',
            },
          ],
          xAxes: [
            {
              gridLines: {
                color: 'rgba(254, 254, 254, 0.2)',
                zeroLineColor: 'rgba(254, 254, 254, 0.2)',
              },
              // labelsのフォントカラー
              ticks: {
                fontColor: 'rgba(254, 254, 254, 1)',
              },
              categoryPercentage: 0.8,
              barPercentage: 0.5,
            },
          ],
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    }
  }

  fetchImprovedRate(): object {
    return {
      improved_rate: this.chartDataList.rateOfImprove.improved_rate,
      centerLabel: '総改善率',
      datasets: [
        {
          backgroundColor: ['#38c172', '#eeeeee'],
          data: this.chartDataList.rateOfImprove.data,
        },
      ],
      labels: ['改善数', '未改善数'],
      options: {
        legend: {
          display: false,
        },
        cutoutPercentage: 80,
        tooltips: { enabled: true },
        responsive: true,
        maintainAspectRatio: false,
      },
    }
  }

  fetchPerIssue(): object {
    return {
      style: {
        position: 'absolute',
        top: '0',
        left: '0',
        width: '100%',
        // ( グラフ1本あたりのスペース[=10px / 3] * 3ヶ月分 + 余白30px ) * exam_issues.length + 目盛りの高さ
        height:
          ((8 / 0.7) * 3 + 30) * this.chartDataList.perIssue.labels.length +
          60 +
          'px',
      },
      datasets: [
        {
          data: this.chartDataList.perIssue.datasets[0].data,
          backgroundColor: 'rgba(254, 254, 254, 0.7)',
          label: this.chartDataList.perIssue.datasets[0].label,
          type: 'horizontalBar',
          fill: false,
          lineTension: 0,
        },
        {
          data: this.chartDataList.perIssue.datasets[1].data,
          backgroundColor: 'rgba(254, 254, 254, 0.7)',
          label: this.chartDataList.perIssue.datasets[1].label,
          type: 'horizontalBar',
          fill: false,
          lineTension: 0,
        },
        {
          data: this.chartDataList.perIssue.datasets[2].data,
          backgroundColor: 'rgba(254, 254, 254, 1)',
          label: this.chartDataList.perIssue.datasets[2].label,
          type: 'horizontalBar',
          fill: false,
          lineTension: 0,
        },
      ],
      labels: this.chartDataList.perIssue.labels,
      options: {
        title: {
          display: true,
          text: '項目別指摘総数（直近3ヶ月）',
          fontColor: 'rgba(254, 254, 254, 1)',
          fontSize: 12,
        },
        layout: {
          padding: {
            top: 10,
            right: 0,
            bottom: 0,
            left: 10,
          },
        },
        legend: {
          display: false,
        },
        scales: {
          yAxes: [
            {
              gridLines: {
                color: 'rgba(254, 254, 254, 0.2)',
                zeroLineColor: 'rgba(254, 254, 254, 0.2)',
              },
              // labelsのフォントカラー
              ticks: {
                fontColor: 'rgba(254, 254, 254, 1)',
              },
              categoryPercentage: 0.7,
              barPercentage: 0.7,
            },
          ],
          xAxes: [
            {
              gridLines: {
                color: 'rgba(254, 254, 254, 0.2)',
                zeroLineColor: 'rgba(254, 254, 254, 0.2)',
              },
              position: 'top',
              // labelsのフォントカラー
              ticks: {
                fontColor: 'rgba(254, 254, 254, 1)',
                suggestedMin: 0,
              },
            },
          ],
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    }
  }

  fetchPerIssueDetail(): any[] {
    return this.chartDataList.perIssueDetail.map(
      (countsPerIssueDetail: any): object => {
        const doughnutLabelColors: string[] = this.setColorArr()
        const start: number =
          countsPerIssueDetail.labels.length < 8
            ? countsPerIssueDetail.labels.length - 1
            : 6
        const length: number =
          doughnutLabelColors.length - countsPerIssueDetail.labels.length
        doughnutLabelColors.splice(start, length)
        return {
          year: `${String(countsPerIssueDetail.label).slice(0, 4)}年`,
          month: Number(String(countsPerIssueDetail.label).slice(4)),
          datasets: [
            {
              backgroundColor: doughnutLabelColors,
              data: countsPerIssueDetail.data,
            },
          ],
          labels: countsPerIssueDetail.labels,
          options: {
            legend: {
              display: false,
            },
            cutoutPercentage: 80,
            responsive: true,
            maintainAspectRatio: false,
          },
        }
      }
    )
  }

  fetchPerBranch(): object {
    return {
      style: {
        position: 'absolute',
        top: '0',
        left: '0',
        width: '100%',
        // ( グラフ1本あたりのスペース[=10px / 3] * 3ヶ月分 + 余白30px ) * exam_issues.length + 目盛りの高さ
        height:
          ((8 / 0.7) * 3 + 30) * this.chartDataList.perBranch.labels.length +
          60 +
          'px',
      },
      datasets: [
        {
          data: this.chartDataList.perBranch.datasets[0].data,
          backgroundColor: 'rgba(254, 254, 254, 0.7)',
          label: this.chartDataList.perBranch.datasets[0].label,
          type: 'horizontalBar',
          fill: false,
          lineTension: 0,
        },
        {
          data: this.chartDataList.perBranch.datasets[1].data,
          backgroundColor: 'rgba(254, 254, 254, 0.7)',
          label: this.chartDataList.perBranch.datasets[1].label,
          type: 'horizontalBar',
          fill: false,
          lineTension: 0,
        },
        {
          data: this.chartDataList.perBranch.datasets[2].data,
          backgroundColor: 'rgba(254, 254, 254, 1)',
          label: this.chartDataList.perBranch.datasets[2].label,
          type: 'horizontalBar',
          fill: false,
          lineTension: 0,
        },
      ],
      labels: this.chartDataList.perBranch.labels,
      options: {
        title: {
          display: true,
          text: '支社別平均指摘総数（直近3ヶ月）',
          fontColor: 'rgba(254, 254, 254, 1)',
          fontSize: 12,
        },
        layout: {
          padding: {
            top: 10,
            right: 0,
            bottom: 0,
            left: 10,
          },
        },
        legend: {
          display: false,
        },
        scales: {
          yAxes: [
            {
              gridLines: {
                color: 'rgba(254, 254, 254, 0.2)',
                zeroLineColor: 'rgba(254, 254, 254, 0.2)',
              },
              // labelsのフォントカラー
              ticks: {
                fontColor: 'rgba(254, 254, 254, 1)',
              },
              categoryPercentage: 0.7,
              barPercentage: 0.7,
            },
          ],
          xAxes: [
            {
              gridLines: {
                color: 'rgba(254, 254, 254, 0.2)',
                zeroLineColor: 'rgba(254, 254, 254, 0.2)',
              },
              position: 'top',
              // labelsのフォントカラー
              ticks: {
                fontColor: 'rgba(254, 254, 254, 1)',
                suggestedMin: 0,
              },
            },
          ],
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    }
  }

  fetchImprovedRatePerBranch(): any[] {
    return this.chartDataList.improvedRatePerBranch.map(
      (branchData: any): object => ({
        improved_rate: branchData.improved_rate,
        branch_name: branchData.branch_name,
        datasets: [
          {
            backgroundColor: ['#38c172', '#eeeeee'],
            data: [
              branchData.improved_total,
              branchData.total - branchData.improved_total,
            ],
          },
        ],
        labels: ['改善数', '未改善数'],
        options: {
          legend: {
            display: false,
          },
          cutoutPercentage: 80,
          responsive: true,
          maintainAspectRatio: false,
        },
      })
    )
  }

  setColorArr(): string[] {
    return [
      '#ee95ba',
      '#ff5252',
      '#f57c00',
      '#ffc321',
      '#eced99',
      '#38c172',
      '#c1cce3',
      '#c1cce3',
      '#c1cce3',
      '#e7e9ed',
    ]
  }
}
</script>
