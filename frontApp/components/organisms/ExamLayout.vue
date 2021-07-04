<template>
  <div v-if="!isUnFetch" class="ExamLayout">
    <div class="ExamLayout_col --main">
      <ExamRecordLayout
        :exam-data="examData"
        :current-exam="currentExam"
        :current-monthly-log="currentMonthlyLog"
        :monthly-log-length="currentMonthlyLogs.length"
        :current-monthly-log-index="currentMonthlyLogIndex"
        :narrowed-issue-id="narrowedIssueId"
        :lost-prev="lostPrev"
        :lost-next="lostNext"
        @onMoveMonth="moveMonth"
        @clearNarrowingDown="clearNarrowing"
        @onSort="sort"
        @onExamClick="setCurrentExamCode"
        @onClickJudge="toggleIssueList"
      />
    </div>
    <div class="ExamLayout_col --side" :class="active">
      <div class="ExamLayout_sideMask" @click="toggleIssueList"></div>
      <IssueListLayout
        :exam-data="examData"
        :current-issues="currentExamIssues"
        :is-complete="currentMonthlyLog.is_complete"
        :current-monthly-logs="currentMonthlyLogs"
        :current-monthly-log="currentMonthlyLog"
        :monthly-log-details="monthlyLogDetails"
        :narrowed-issue-id="narrowedIssueId"
        @onSelectChange="setCurrentExamCode"
        @onNarrowDown="narrowing"
        @onComplete="showCompleteModal"
      />
    </div>
    <CompleteModal
      :is-show="isShowCompleteModal"
      @onCancel="isShowCompleteModal = false"
    >
      <CancelAlertBox
        slot="modalContent"
        :button-options="['--error']"
        :execed="!!currentMonthlyLog.is_complete"
        cancel-button-label="戻る"
        exec-button-label="検査完了"
        @onCancel="isShowCompleteModal = false"
        @onExec="complete"
      >
        <p
          v-if="currentMonthlyLog.is_complete"
          slot="CancelAlertBox_message"
          class="CancelAlertBox_message"
        >
          検査完了済みです。
        </p>
        <div
          v-else
          slot="CancelAlertBox_message"
          class="CancelAlertBox_message"
        >
          <p>検査を完了させると次の作業が<br />できなくなります。</p>
          <ul class="CancelAlertBox_list">
            <li>指摘内容の追加・更新・削除</li>
            <li>検査完了状態の解除</li>
          </ul>
          <p>検査完了してよろしいですか？</p>
        </div>
      </CancelAlertBox>
    </CompleteModal>
    <FullLoader :is-loader="isLoader" />
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { mapGetters } from 'vuex'
import ExamRecordLayout from '~/components/organisms/ExamRecordLayout.vue'
import IssueListLayout from '~/components/organisms/IssueListLayout.vue'
import CompleteModal from '~/components/organisms/Modal.vue'
import CancelAlertBox from '~/components/molecules/CancelAlertBox.vue'
import FullLoader from '~/components/atoms/FullLoader.vue'
import { MonthlyLogDetail } from '~/types/MonthlyLogDetail'
import { SelectField } from '~/types/FormObj'
import { MonthlyLog } from '~/types/Records'
import { ExamIssue } from '~/types/Exam'

export default Vue.extend({
  components: {
    ExamRecordLayout,
    IssueListLayout,
    CompleteModal,
    CancelAlertBox,
    FullLoader,
  },
  props: {
    isUnFetch: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  data: () => ({
    narrowedIssueId: 0 as number,
    hideIssueList: true as boolean,
    isShowCompleteModal: false as boolean,
    isLoader: false as boolean,
  }),
  computed: {
    ...mapGetters({
      examList: 'exam/examList',
      currentShop: 'exam/currentShop',
      currentExam: 'exam/currentExam',
      currentMonthlyLogs: 'exam/currentMonthlyLogs',
      currentMonthlyLogIndex: 'exam/currentMonthlyLogIndex',
    }),
    currentMonthlyLog(): MonthlyLog {
      return this.currentMonthlyLogs[this.currentMonthlyLogIndex]
    },
    examData(): SelectField {
      return {
        name: 'exam',
        label: '',
        type: '',
        labelKey: 'name',
        rules: [(): boolean => true],
        errorMessages: [],
        list: this.examList,
        value: this.currentExam,
        prependIcon: '',
      }
    },
    currentExamIssues(): ExamIssue[] {
      return this.currentExam.exam_issues
    },
    monthlyLogDetails(): MonthlyLogDetail[] {
      return this.currentMonthlyLogs[this.currentMonthlyLogIndex]
        .monthly_log_details
    },
    active(): object {
      return {
        '--hidden': this.hideIssueList,
      }
    },
    alertBoxMessage(): string {
      return this.currentMonthlyLog.is_complete
        ? '検査完了済みです。'
        : '検査を完了させると指摘内容の追加等一切の操作ができなくなります。よろしいですか？'
    },
    // 一番古いレコードのときは「前月」ボタンを押せなくする
    lostPrev(): boolean {
      return this.currentMonthlyLogIndex === this.currentMonthlyLogs.length - 1
    },
    // 最新レコードのときは「次月」ボタンを押せなくする
    lostNext(): boolean {
      return this.currentMonthlyLogIndex === 0
    },
  },
  watch: {
    $route: {
      handler() {
        this.$store.dispatch('exam/moveMonth', -this.currentMonthlyLogIndex)
      },
      immediate: true,
    },
  },
  methods: {
    yearMonth(yearMonth: string) {
      const str = yearMonth.split('-')
      return `${str[0]}年${str[1]}月`
    },
    setCurrentExamCode(index: number): void {
      this.setNarrowedIssueId(0)
      this.$store.dispatch('exam/moveExam', index)
    },
    clearNarrowing(): void {
      this.setNarrowedIssueId(0)
      this.hideIssueList = true
    },
    narrowing(issueId: number): void {
      this.setNarrowedIssueId(issueId)
      this.hideIssueList = true
    },
    moveMonth(arrow: string): void {
      if (arrow === 'prev') {
        this.$store.dispatch('exam/moveMonth', 1)
      } else if (arrow === 'next') {
        this.$store.dispatch('exam/moveMonth', -1)
      } else {
        this.$store.dispatch('exam/moveMonth', -this.currentMonthlyLogIndex)
      }
    },
    sort(orderBy: string[]) {
      this.$store.dispatch('exam/order', orderBy)
    },
    toggleIssueList(): void {
      this.hideIssueList = !this.hideIssueList
    },
    setNarrowedIssueId(issueId: number): void {
      this.narrowedIssueId = issueId
    },
    showCompleteModal(): void {
      this.isShowCompleteModal = true
    },
    async complete(): Promise<void> {
      this.isLoader = true
      await this.$store.dispatch('exam/complete', this.currentMonthlyLog.id)
      this.isLoader = false
      this.isShowCompleteModal = false
    },
  },
})
</script>
