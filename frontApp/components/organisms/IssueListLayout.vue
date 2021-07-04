<template>
  <div class="IssueListLayout">
    <div class="IssueListLayout_head">
      <div class="IssueListLayout_dashboard">
        <CoreUiCustom class="IssueListLayout_core">
          <div slot="coreui-custom-content" class="IssueListLayout_graphNav">
            <CCarousel
              indicators
              animate
              height="100%"
              class="IssueListLayout_graphList"
            >
              <CCarouselItem class="IssueListLayout_graphItem" :style="bgColor">
                <div class="IssueListLayout_card">
                  <div class="IssueListLayout_cardHead">
                    <SelectBox
                      :item="examData"
                      :class="['--white', '--large', '--hasIndex']"
                      :style="selectBgColor"
                      @onChange="fetchCurrentExam"
                    />
                  </div>
                  <div class="IssueListLayout_cardBody">
                    <CircleBox
                      :value="monthlyLogDetails.length"
                      :data-label="`${currentMonthlyLog.year_month.replace(
                        '-',
                        '年'
                      )}月\n指摘総数`"
                    />
                  </div>
                </div>
              </CCarouselItem>
              <CCarouselItem class="IssueListLayout_graphItem" :style="bgColor">
                <WhiteCard>
                  <div
                    slot="WhiteCard_content"
                    class="IssueListLayout_graphWrap"
                  >
                    <h4 class="IssueListLayout_graphTitle">
                      {{
                        currentMonthlyLog.year_month.replace('-', '年')
                      }}月度構成比
                    </h4>
                    <div class="IssueListLayout_graphBody">
                      <CChartDoughnut
                        :style="doughnutData.style"
                        :datasets="doughnutData.datasets"
                        :labels="doughnutData.labels"
                        :options="doughnutData.options"
                      />
                    </div>
                  </div>
                </WhiteCard>
              </CCarouselItem>
              <CCarouselItem class="IssueListLayout_graphItem" :style="bgColor">
                <WhiteCard>
                  <div
                    slot="WhiteCard_content"
                    class="IssueListLayout_graphWrap"
                  >
                    <h4 class="IssueListLayout_graphTitle">最近6ヶ月の推移</h4>
                    <div class="IssueListLayout_graphBody">
                      <CChartLine
                        slot="WhiteCard_content"
                        style="width: 100%; height: 100%"
                        :datasets="chartLineData.datasets"
                        :labels="chartLineData.labels"
                        :options="chartLineData.options"
                      />
                    </div>
                  </div>
                </WhiteCard>
              </CCarouselItem>
            </CCarousel>
          </div>
        </CoreUiCustom>
      </div>
    </div>
    <div class="IssueListLayout_body">
      <div class="IssueListLayout_bodyRow">
        <h2 class="IssueListLayout_label">判定項目</h2>
        <nav class="IssueListLayout_nav">
          <ul class="IssueListLayout_menu">
            <li
              v-for="(issue, index) in currentIssues"
              :key="index"
              class="IssueListLayout_item"
            >
              <p class="IssueListLayout_col u-mr10" @click="onNarrow(issue.id)">
                <Icon
                  :name="narrowingRadio(issue.id)"
                  class="IssueListLayout_narrowDown"
                  :class="narrowing(issue.id)"
                />
              </p>
              <p
                class="IssueListLayout_col --flex u-py10"
                @click="onNarrow(issue.id)"
              >
                {{ issue.name }}
              </p>
              <p class="IssueListLayout_col --help">
                <Icon
                  name="help_outline"
                  class="IssueListLayout_help"
                  :data-desc="issue.judgement_base"
                />
              </p>
              <p class="IssueListLayout_col">
                <Icon
                  :name="judge(issue.id)"
                  :data-tooltip="judgeMean(issue.id)"
                  :class="judgeClass(issue.id)"
                  class="IssueListLayout_mark"
                />
              </p>
            </li>
          </ul>
        </nav>
      </div>
      <div class="IssueListLayout_operator">
        <CustomButton
          label="検査完了"
          :class="completeButtonClass"
          @click="$emit('onComplete')"
        />
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import VueScrollTo from 'vue-scrollto'
import max from 'lodash/max'
import findIndex from 'lodash/findIndex'
import last from 'lodash/last'
import { Exam, ExamIssue } from '~/types/Exam'
import { MonthlyLogDetail } from '~/types/MonthlyLogDetail'
import { MonthlyObjPerIssue, ChartLine } from '~/types/Aggregate'
import SelectBox from '~/components/molecules/SelectBox.vue'
import CustomButton from '~/components/atoms/CustomButton.vue'
import { SelectField } from '~/types/FormObj'
import Icon from '~/components/atoms/Icon.vue'
import CoreUiCustom from '~/components/atoms/CoreUiCustom.vue'
import WhiteCard from '~/components/atoms/WhiteCard.vue'
import CircleBox from '~/components/atoms/CircleBox.vue'
import { changeBrightness } from '~/plugins/util'
import { MonthlyLog } from '~/types/Records'
Vue.use(VueScrollTo)

export default Vue.extend({
  components: {
    SelectBox,
    CustomButton,
    Icon,
    CoreUiCustom,
    WhiteCard,
    CircleBox,
  },
  props: {
    examData: {
      type: Object as PropType<SelectField>,
      default: (): SelectField => ({
        name: 'currentExam',
        label: '選択中の検査',
        type: 'select',
        labelKey: 'name',
        rules: [(): boolean => true],
        errorMessages: [],
        list: [],
        value: '',
        prependIcon: '',
      }),
    },
    currentMonthlyLogs: {
      type: Array as PropType<MonthlyLog[]>,
      default: (): MonthlyLog[] => [
        {
          exam_code: 1,
          examinator: {
            created_at: '',
            deleted_at: null,
            employee_id: 0,
            file_name: null,
            id: 0,
            name: '',
            team_code: 0,
            updated_at: '',
          },
          year_month: '2020-01',
          examined_year: 2020,
          examined_month: 1,
          monthly_log_details: [
            {
              id: 1,
              body: '',
              file_name: '',
              is_improved: 0,
            },
          ],
        },
      ],
    },
    currentIssues: {
      type: Array as PropType<any[]>,
      default: (): any[] => [],
    },
    monthlyLogDetails: {
      type: Array as PropType<MonthlyLogDetail[]>,
      default: (): MonthlyLogDetail[] => [
        {
          id: 1,
          body: '',
          file_name: '',
          is_improved: 0,
        },
      ],
    },
    currentMonthlyLog: {
      type: Object as PropType<MonthlyLog>,
      default: (): MonthlyLog => ({
        exam_code: 1,
        examinator: {
          created_at: '',
          deleted_at: null,
          employee_id: 0,
          file_name: null,
          id: 0,
          name: '',
          team_code: 0,
          updated_at: '',
        },
        year_month: '2020-01',
        examined_year: 2020,
        examined_month: 1,
        monthly_log_details: [
          {
            id: 1,
            body: '',
            file_name: '',
            is_improved: 0,
          },
        ],
      }),
    },
    narrowedIssueId: {
      type: Number as PropType<number>,
      default: 0,
    },
    graphColorList: {
      type: Array as PropType<string[]>,
      default: (): string[] => [
        '#e94440',
        '#f57c00',
        '#ffe121',
        '#54ad57',
        '#21c3d8',
        '#e7e9ed',
      ],
    },
  },
  data: () => ({
    clientW: 0 as number,
  }),
  computed: {
    completeButtonClass(): object {
      return {
        '--pink': true,
        '--rounded': true,
        '--outlined': !this.currentMonthlyLog.is_complete,
      }
    },
    bgColor(): object {
      return {
        backgroundColor: this.examData.value.color,
      }
    },
    selectBgColor(): object {
      return {
        backgroundColor: changeBrightness(this.examData.value.color, 15),
      }
    },
    monthlyCountPerIssue(): number[] {
      return this.currentIssues.map(
        (issue: ExamIssue): number =>
          this.monthlyLogDetails.filter(
            (detail: MonthlyLogDetail): boolean =>
              detail.belongs_to_issue.id === issue.id
          ).length
      )
    },
    currentIssueNames(): string[] {
      return this.currentIssues.map((issue: ExamIssue): string => issue.name)
    },
    forDoughnutArr(): MonthlyObjPerIssue[] {
      return this.currentIssues
        .map(
          (issue: ExamIssue): MonthlyObjPerIssue => {
            const count = this.monthlyLogDetails.filter(
              (detail: MonthlyLogDetail): boolean =>
                detail.belongs_to_issue.id === issue.id
            ).length
            return {
              name: issue.name,
              count,
            }
          }
        )
        .sort(
          (prev: MonthlyObjPerIssue, next: MonthlyObjPerIssue) =>
            next.count - prev.count
        )
    },
    doughnutData(): any {
      return {
        style: {
          width: '100%',
          height: '100%',
        },
        datasets: [
          {
            backgroundColor: this.graphColorList,
            data: this.forDoughnutArr.map(
              (data: MonthlyObjPerIssue): number => data.count
            ),
          },
        ],
        labels: this.forDoughnutArr.map(
          (data: MonthlyObjPerIssue): string => data.name
        ),
        options: {
          legend: {
            display: false,
          },
          responsive: true,
          maintainAspectRatio: false,
        },
      }
    },
    forChartLineArr(): ChartLine[] {
      return this.currentIssues
        .map((currentIssue: ExamIssue): any => {
          // 指摘項目ごとの月別指摘数
          const countArr: number[] = this.descedMonthlyLogs.map(
            (monthlyLog: MonthlyLog): number =>
              monthlyLog.monthly_log_details.filter(
                (monthlyLogDetail: MonthlyLogDetail): boolean =>
                  monthlyLogDetail.belongs_to_issue.id === currentIssue.id
              ).length
          )
          const idx = findIndex(
            this.forDoughnutArr,
            (data: MonthlyObjPerIssue): boolean =>
              data.name === currentIssue.name
          )
          return {
            data: countArr,
            label: currentIssue.name,
            fill: false,
            lineTension: 0,
            borderColor: this.graphColorList[idx],
            borderWidth: 1,
            pointBorderColor: this.graphColorList[idx],
            pointBackgroundColor: this.graphColorList[idx],
            pointBorderWidth: 1,
          }
        })
        .sort(
          (prevData: ChartLine, nextData: ChartLine): any =>
            last(nextData.data) - last(prevData.data)
        )
    },
    descedMonthlyLogs(): MonthlyLog[] {
      return this.currentMonthlyLogs
        .slice(0, 6)
        .sort(
          (prev: MonthlyLog, next: MonthlyLog): any =>
            Date.parse(prev.created_at) - Date.parse(next.created_at)
        )
    },
    countList(): any {
      return this.currentIssues.map((currentIssue: ExamIssue): any =>
        this.descedMonthlyLogs.map(
          (monthlyLog: MonthlyLog): number =>
            monthlyLog.monthly_log_details.filter(
              (monthlyLogDetail: MonthlyLogDetail): boolean =>
                monthlyLogDetail.belongs_to_issue.id === currentIssue.id
            ).length
        )
      )
    },
    chartLineData() {
      return {
        datasets: this.forChartLineArr,
        labels: this.descedMonthlyLogs.map((monthlyLog: MonthlyLog) =>
          monthlyLog.year_month.replace('-', '.')
        ),
        options: {
          legend: {
            display: false,
          },
          scales: {
            yAxes: [
              {
                ticks: {
                  suggestedMax: max(
                    this.countList.map((nums: number[]) => max(nums))
                  ),
                  suggestedMin: 0,
                  stepSize: 5,
                  // callback: (value, index, values) => {
                  //   return value
                  // },
                  callback: (value: number) => {
                    return value
                  },
                },
              },
            ],
          },
          responsive: true,
          maintainAspectRatio: false,
        },
      }
    },
  },
  methods: {
    fetchCurrentExam(itemValue: Exam): void {
      this.$emit('onSelectChange', itemValue.exam_code)
    },
    judge(issueId: number): string {
      if (this.isImproved(issueId)) {
        return 'change_history'
      } else if (this.hasLogDetail(issueId)) {
        return 'close'
      } else {
        return 'brightness_1'
      }
    },
    judgeMean(issueId: number): string {
      if (this.isImproved(issueId)) {
        return 'すべて改善済み'
      } else if (this.hasLogDetail(issueId)) {
        return '未改善あり'
      } else {
        return '指摘なし'
      }
    },
    judgeClass(issueId: number): string[] {
      if (this.isImproved(issueId)) {
        return ['--improved']
      } else if (this.hasLogDetail(issueId)) {
        return ['--hasLogDetail']
      } else {
        return []
      }
    },
    hasLogDetail(issueId: number): boolean {
      return this.monthlyLogDetails.some(
        (monthlyLogDetail: MonthlyLogDetail): boolean =>
          monthlyLogDetail.exam_issue_detail.exam_issue.id === issueId
      )
    },
    isImproved(issueId: number): boolean {
      const logDetails: any = this.monthlyLogDetails.filter(
        (monthlyLogDetail: MonthlyLogDetail): boolean =>
          monthlyLogDetail.belongs_to_issue.id === issueId
      )
      if (logDetails.length === 0) {
        return false
      }
      return (
        logDetails.filter(
          (monthlyLogDetail: MonthlyLogDetail): boolean =>
            !monthlyLogDetail.is_improved
        ).length === 0
      )
    },
    narrowingRadio(issueId: number): string {
      return this.narrowedIssueId === issueId
        ? 'radio_button_checked'
        : 'radio_button_unchecked'
    },
    narrowing(issueId: number): string[] {
      if (this.narrowedIssueId === issueId) {
        return ['--narrow']
      } else {
        return []
      }
    },
    onNarrow(issueId: number) {
      this.$scrollTo('#record_top')
      this.$emit('onNarrowDown', issueId)
    },
  },
})
</script>
<style lang="scss">
.carousel-inner {
  height: 100%;
  overflow: initial;
}
</style>
