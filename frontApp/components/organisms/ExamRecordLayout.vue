<template>
  <div class="ExamRecordLayout">
    <div id="record_top" class="ExamRecordLayout_head">
      <div class="ExamRecordLayout_headRow">
        <div class="ExamRecordLayout_headCol --absLeft">
          <div class="HorizontalLayout_col u-mr20 --smOnly">
            <IconMono
              name="assets/img/board_grey3.svg"
              @click="$emit('onClickJudge')"
            />
          </div>
        </div>
        <div class="ExamRecordLayout_headCol --absRight">
          <div class="HorizontalLayout --vertical">
            <div class="HorizontalLayout_col u-mr20 --lgOnly">
              <IconMono
                name="assets/img/board_grey3.svg"
                @click="$emit('onClickJudge')"
              />
            </div>
            <div class="HorizontalLayout_col">
              <MoreInfoLayout
                icon-name="sort"
                :info-data="sortInfoData"
                @clickVerticalItem="onSortClick"
              >
              </MoreInfoLayout>
            </div>
          </div>
        </div>
        <div class="ExamRecordLayout_headCol --fill --smNone">
          <TabList
            :list="examData.list"
            :keys="['exam_code', 'name']"
            :value="examData.value"
            @click="fetchCurrentExam"
          />
        </div>
        <div class="ExamRecordLayout_headCol --fill --smOnly">
          <MigrationBox :box-list="migrationBoxList" @click="migrate" />
        </div>
      </div>
      <div class="ExamRecordLayout_headRow">
        <div class="ExamRecordLayout_headCol --fill --smNone">
          <MigrationBox :box-list="migrationBoxList" @click="migrate" />
        </div>
      </div>
      <!-- ExamRecordLayout_headRow -->
    </div>
    <div v-if="haveDetails" class="ExamRecordLayout_body">
      <BreadCrumb :path-list="breadcrumbs" />
      <div class="u-pt15">
        <Tag
          v-if="isNarrowingDown"
          :label="currentIssueBody"
          :class="['--roundless']"
          @click="$emit('clearNarrowingDown')"
        />
      </div>
      <div class="ExamRecordLayout_pager">
        <Pager
          :total-items-count="narrowedDetails.length"
          :per-page="countPerPage"
          :current-page="currentPage"
          @onLinkClicked="movePager"
        />
      </div>
      <ul class="ExamRecordLayout_list">
        <li
          v-for="(detail, index) in slicedDetails"
          :key="index"
          class="ExamRecordLayout_item"
        >
          <RecordCard
            :monthly-log-detail="detail"
            :is-complete="currentMonthlyLog.is_complete"
            @onUpdateClick="fetchFormValues(detail)"
            @onDeleteClick="switchDeleteModal(detail.id)"
            @onWorryClick="onWorry(detail)"
          />
        </li>
      </ul>
    </div>
    <div v-else class="ExamRecordLayout_body --nothing">
      <BreadCrumb :path-list="breadcrumbs" />
      <div class="u-pt30">
        <Tag
          v-if="isNarrowingDown"
          :label="currentIssueBody"
          :class="['--roundless']"
          @click="$emit('clearNarrowingDown')"
        />
      </div>
      <p class="u-pt30">指摘事項はありません</p>
    </div>
    <div class="ExamRecordLayout_operator">
      <div
        v-if="!currentMonthlyLog.is_complete"
        class="ExamRecordLayout_add u-mt10"
        @click="onAdd"
      >
        <CircledButton icon-name="library_add" :class="['--lg']" />
      </div>
    </div>
    <DialogModal :is-show="isFormModel" @onCancel="hideForm">
      <FormLayout
        slot="modalContent"
        :title="formTitle"
        :button-label="formButtonLabel"
        :button-options="['--outlined', '--min100']"
        :has-cancel="true"
        :edit-permission="editPermission"
        :form-data="detailData"
        :files-tab-flag="isFormModel"
        @onCancel="hideForm"
        @click="storeDetail"
        @onChange="fetchDetailValue"
      />
    </DialogModal>
    <DialogModal :is-show="isDeleteModel" @onCancel="switchDeleteModal(null)">
      <CancelAlertBox
        slot="modalContent"
        :button-options="['--error']"
        cancel-button-label="戻る"
        @onCancel="switchDeleteModal(null)"
        @onExec="deleteDetail"
      >
        <p slot="CancelAlertBox_message" class="CancelAlertBox_message">
          一度削除すると元に戻せません。<br />削除してよろしいですか？
        </p>
      </CancelAlertBox>
    </DialogModal>
    <FullLoader :is-loader="isLoader" />
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import VueScrollTo from 'vue-scrollto'
import IconMono from '~/components/atoms/IconMono.vue'
import Tag from '~/components/atoms/Tag.vue'
import TabList from '~/components/atoms/TabList.vue'
import MigrationBox from '~/components/atoms/MigrationBox.vue'
import FullLoader from '~/components/atoms/FullLoader.vue'
import RecordCard from '~/components/molecules/RecordCard.vue'
import CircledButton from '~/components/molecules/CircledButton.vue'
import Pager from '~/components/molecules/Pager.vue'
import CancelAlertBox from '~/components/molecules/CancelAlertBox.vue'
import MoreInfoLayout from '~/components/organisms/MoreInfoLayout.vue'
import DialogModal from '~/components/organisms/Modal.vue'
import FormLayout from '~/components/organisms/FormLayout.vue'
import BreadCrumb from '~/components/molecules/BreadCrumb.vue'
import { Exam, ExamIssue, ExamIssueDetail } from '~/types/Exam'
import { MonthlyLog } from '~/types/Records'
import { FileField, FormObj, SelectField } from '~/types/FormObj'
import { MonthlyLogDetail } from '~/types/MonthlyLogDetail'
import { BreadCrumbObj } from '~/types/BreadCrumbs'
Vue.use(VueScrollTo)

export type FileValueObj = {
  primary_file: any
  secondary_file: any
  improved_file: any
}
export default Vue.extend({
  components: {
    IconMono,
    Tag,
    TabList,
    MigrationBox,
    CancelAlertBox,
    CircledButton,
    DialogModal,
    FormLayout,
    BreadCrumb,
    FullLoader,
    MoreInfoLayout,
    Pager,
    RecordCard,
  },
  props: {
    currentExam: {
      type: Object as PropType<Exam>,
      default: (): Exam => ({
        exam_code: 1,
        name: '防災',
        color: '#eeeeee',
        file_name: '',
        risk_rank_id: 1,
        exam_issues: [
          {
            id: 1,
            exam_code: 1,
            name: '最重要項目',
            judgement_base: '',
            exam_issue_details: [
              {
                id: 1,
                exam_issue_id: 1,
                issue_content: '非常扉前物品',
                created_by: 1,
              },
            ],
          },
        ],
      }),
    },
    examData: {
      type: Object as PropType<SelectField>,
      default: (): SelectField => ({
        name: 'currentExam',
        label: '選択中の検査',
        type: 'select',
        labelKey: 'name',
        appendIcon: '',
        rules: [(): boolean => true],
        errorMessages: [],
        list: [],
        value: '',
      }),
    },
    currentMonthlyLog: {
      type: Object as PropType<MonthlyLog>,
      default: (): MonthlyLog => ({
        exam_code: 1,
        examinator: {
          created_at: '',
          deleted_at: '',
          employee_id: 1,
          file_name: '',
          id: 1,
          name: '',
          team_code: 1,
          updated_at: '',
        },
        year_month: '',
        examined_year: 2021,
        examined_month: 1,
        monthly_log_details: [
          {
            id: 1,
            body: '',
            file_name: '',
            is_improved: 1,
          },
        ],
      }),
    },
    monthlyLogLength: {
      type: Number as PropType<number>,
      default: 0,
    },
    currentMonthlyLogIndex: {
      type: Number as PropType<number>,
      default: 0,
    },
    narrowedIssueId: {
      type: Number as PropType<number>,
      default: 0,
    },
    lostPrev: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    lostNext: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
  },
  data: () => ({
    isFormModel: false as boolean,
    isDeleteModel: false as boolean,
    isLoader: false as boolean,
    detailData: {
      detailFileList: {
        name: 'detailFileList',
        type: 'fileList',
        value: '',
        rules: [() => true],
        errorMessages: [],
        list: [
          {
            name: 'primary_file',
            type: 'file',
            label: '指摘画像1',
            value: '',
            preview: '',
            rules: [(val) => !!val || '指摘画像1を挿入してください'],
            errorMessages: [],
            accept: 'image/jpeg, image/png, image/gif',
          },
          {
            name: 'secondary_file',
            type: 'file',
            label: '指摘画像2',
            value: '',
            preview: '',
            rules: [() => true],
            errorMessages: [],
            accept: 'image/jpeg, image/png, image/gif',
          },
          {
            name: 'improved_file',
            type: 'file',
            label: '改善画像',
            value: '',
            preview: '',
            rules: [() => true],
            errorMessages: [],
            accept: 'image/jpeg, image/png, image/gif',
          },
        ],
      },
      issue: {
        name: 'issue',
        label: '指摘項目',
        type: 'select',
        value: '',
        list: [],
        labelKey: 'name',
        appendIcon: '',
        rules: [
          (issue: any): boolean | string =>
            !!issue || '指摘項目を選択してください',
        ],
        errorMessages: [],
        loader: false,
      },
      exam_issue_detail_id: {
        name: 'exam_issue_detail_id',
        label: '指摘内容',
        type: 'radio',
        value: '',
        keys: ['id', 'issue_content'],
        list: [],
        verticalFixed: false,
        rules: [
          (issue: any): boolean | string =>
            !!issue || '指摘内容を選択してください',
        ],
        errorMessages: [],
        loader: false,
      },
      note: {
        name: 'note',
        label: '備考欄',
        type: 'textarea',
        value: '',
        placeholder: '80文字以内',
        rules: [
          (val) => val === null || val.length <= 80 || '80文字を超えています',
        ],
        errorMessages: [],
        class: ['--outline'],
      },
    } as FormObj,
    updateFlag: true as boolean,
    selectedMonthlyLogDetailId: null as number | null,
    formTitle: '新規追加' as string,
    formButtonLabel: '追加' as string,
    editPermission: true as boolean,
    sortBoxList: {
      name: '',
      label: '',
      type: 'select',
      value: {
        label: '投稿の新しい順',
        body: ['id', 'desc'],
      },
      list: [
        {
          label: '投稿の新しい順',
          body: ['id', 'desc'],
          iconName: 'radio_button_checked',
          isShow: true,
        },
        {
          label: '投稿の古い順',
          body: ['id', 'asc'],
          iconName: 'radio_button_unchecked',
          isShow: true,
        },
        {
          label: '設問降順',
          body: ['exam_issue_detail_id', 'desc'],
          iconName: 'radio_button_unchecked',
          isShow: true,
        },
        {
          label: '設問昇順',
          body: ['exam_issue_detail_id', 'asc'],
          iconName: 'radio_button_unchecked',
          isShow: true,
        },
        {
          label: '改善済み順',
          body: ['is_improved', 'desc'],
          iconName: 'radio_button_unchecked',
          isShow: true,
        },
        {
          label: '未改善順',
          body: ['is_improved', 'asc'],
          iconName: 'radio_button_unchecked',
          isShow: true,
        },
      ],
      labelKey: 'label',
      rules: [(): boolean => true],
      errorMessages: [],
      options: [],
    } as SelectField,
    hideSortBox: true as boolean,
    // pager関係
    countPerPage: 12 as number,
    currentPage: 1 as number,
    startKey: 0 as number,
    endKey: 12 as number,
  }),
  computed: {
    breadcrumbs(): BreadCrumbObj[] {
      return [
        {
          name: 'TOP',
          path: '/',
        },
        {
          name: 'マイページ',
          path: '/mypage',
        },
        {
          name: '検査画面',
          path: this.$route.fullPath,
        },
      ]
    },
    sortInfoData() {
      return {
        hasRowIcon: true,
        list: this.sortBoxList.list,
        keys: ['label', 'label'],
        activeTarget: this.sortBoxList.value,
        classOptions: ['--min180'],
      }
    },
    selectedIssue(): ExamIssue {
      return this.detailData.issue.value
    },
    narrowedDetails(): MonthlyLogDetail[] {
      if (this.narrowedIssueId === 0) {
        return this.currentMonthlyLog.monthly_log_details
      }
      return this.currentMonthlyLog.monthly_log_details.filter(
        (detail: MonthlyLogDetail): boolean =>
          detail.belongs_to_issue.id === this.narrowedIssueId
      )
    },
    slicedDetails(): MonthlyLogDetail[] {
      return this.narrowedDetails.slice(this.startKey, this.endKey)
    },
    isNarrowingDown(): boolean {
      return this.narrowedIssueId !== 0
    },
    currentIssueBody(): any {
      if (!this.isNarrowingDown) {
        return false
      } else {
        return (
          this.currentExam.exam_issues.filter(
            (issue: ExamIssue): boolean => issue.id === this.narrowedIssueId
          )[0].name + '　×'
        )
      }
    },
    examinedBy(): string {
      return this.currentMonthlyLog.examinator.name
    },
    disabledPrev() {
      return {
        '--disabled': this.lostPrev,
      }
    },
    disabledNext() {
      return {
        '--disabled': this.lostNext,
      }
    },
    haveDetails(): boolean {
      return !!this.narrowedDetails.length
    },
    isImproved(): string {
      return this.fileList.improved_file ? '1' : '0'
    },
    fileList(): FileValueObj {
      return this.detailData.detailFileList.list.reduce(
        (fileListObj: any, fileField: FileField): any => {
          // ''でない = 画像選択またはゴミ箱を押した場合
          if (fileField.value !== '') {
            fileListObj[fileField.name] = fileField.value
          } else {
            fileListObj[fileField.name] = null
          }
          return fileListObj
        },
        {}
      )
    },
    migrationBoxList(): any {
      return [
        {
          clickFlag: 'prev',
          label: '前月',
          disabledValue: this.disabledPrev,
        },
        {
          clickFlag: 'current',
          label: '当月',
          disabledValue: {
            '--disabled': false,
          },
        },
        {
          clickFlag: 'next',
          label: '次月',
          disabledValue: this.disabledNext,
        },
      ]
    },
  },
  watch: {
    selectedIssue: {
      handler(val): void {
        if (val !== '') {
          this.fetchIssueDetailList()
        }
      },
      deep: true,
    },
    currentExam: {
      handler(val): void {
        if (val !== '') {
          this.fetchIssueList()
          this.movePager(1)
        }
      },
      deep: true,
    },
    narrowedIssueId: {
      handler(): void {
        this.movePager(1)
      },
    },
    currentMonthlyLogIndex: {
      handler(): void {
        this.movePager(1)
      },
    },
  },
  mounted() {
    this.fetchIssueList()
  },
  methods: {
    movePager(currentPageNumber: number): void {
      this.currentPage = currentPageNumber
      this.startKey = this.countPerPage * (this.currentPage - 1)
      this.endKey = this.startKey + this.countPerPage
    },
    yearMonth(yearMonth: string): string {
      const str = yearMonth.split('-')
      return `${str[0]}年${str[1]}月`
    },
    movePrev(): void {
      if (!this.lostPrev) {
        this.$emit('onMoveMonth', 'prev')
      }
    },
    moveCurrent(): void {
      this.$emit('onMoveMonth', 'current')
    },
    moveNext(): void {
      if (!this.lostNext) {
        this.$emit('onMoveMonth', 'next')
      }
    },
    migrate(flag: string) {
      this.$emit('onMoveMonth', flag)
    },
    async storeDetail(): Promise<void> {
      this.isLoader = true
      const submitData = new FormData()
      submitData.append('monthlyLogId', this.currentMonthlyLog.id)
      submitData.append(
        'exam_issue_detail_id',
        this.detailData.exam_issue_detail_id.value.id
      )
      submitData.append('primary_file', this.fileList.primary_file)
      submitData.append('secondary_file', this.fileList.secondary_file)
      submitData.append('improved_file', this.fileList.improved_file)
      submitData.append('note', this.detailData.note.value)
      submitData.append('is_improved', this.isImproved)
      const path = this.updateFlag
        ? `/monthly_logs/${this.currentMonthlyLog.id}/details/${this.selectedMonthlyLogDetailId}/update`
        : `/monthly_logs/${this.currentMonthlyLog.id}/details`
      const args: any = {
        path,
        submitData,
      }
      const response: any = await this.$store.dispatch('exam/storeDetail', args)
      this.isFormModel = response.isError
      this.isLoader = false
      if (!(this.updateFlag || response.isError)) {
        // 新規登録が成功したら
        this.onSortClick(this.sortBoxList.list[0])
        this.$scrollTo('#record_top')
        this.$emit('clearNarrowingDown')
        this.movePager(1)
        this.clearFormValue()
      }
    },
    async deleteDetail() {
      this.isLoader = true
      const monthlyLogId = this.currentMonthlyLog.id
      const monthlyLogDetailId = this.selectedMonthlyLogDetailId
      await this.$store.dispatch(
        'exam/deleteDetail',
        `/monthly_logs/${monthlyLogId}/details/${monthlyLogDetailId}/delete`
      )
      this.isLoader = false
      this.isDeleteModel = false
    },
    async onWorry(clickedDetail: MonthlyLogDetail) {
      const monthlyLogId = this.currentMonthlyLog.id
      if (clickedDetail.is_worried) {
        await this.$axios
          .$delete(
            `/monthly_logs/${monthlyLogId}/details/${clickedDetail.id}/unworry`
          )
          .then(() => {
            this.currentMonthlyLog.monthly_log_details.forEach(
              (monthlyLogDetail: MonthlyLogDetail): void => {
                if (monthlyLogDetail.id === clickedDetail.id) {
                  monthlyLogDetail.is_worried = false
                }
              }
            )
          })
          .catch((err: any): void => {
            this.$store.commit('status/setStatus', {
              status: err.response.status,
              messages: err.response.messages,
            })
          })
      } else {
        await this.$axios
          .$put(
            `/monthly_logs/${monthlyLogId}/details/${clickedDetail.id}/worry`
          )
          .then(() => {
            this.currentMonthlyLog.monthly_log_details.forEach(
              (monthlyLogDetail: MonthlyLogDetail): void => {
                if (monthlyLogDetail.id === clickedDetail.id) {
                  monthlyLogDetail.is_worried = true
                }
              }
            )
          })
          .catch((err: any): void => {
            this.$store.commit('status/setStatus', {
              status: err.response.status,
              messages: err.response.messages,
            })
          })
      }
    },
    fetchIssueList(): void {
      const issue: any = this.detailData.issue
      issue.list = this.currentExam.exam_issues
    },
    fetchIssueDetailList(): void {
      const issueDetail: any = this.detailData.exam_issue_detail_id
      issueDetail.list = this.selectedIssue.exam_issue_details
    },
    fetchDetailValue(): void {
      this.detailData.exam_issue_detail_id.value = this.detailData.exam_issue_detail_id.value = this.detailData.issue.value.exam_issue_details[0]
    },
    fetchFormValues(detail: ExamIssueDetail): void {
      this.detailData.issue.value = detail.belongs_to_issue
      this.detailData.exam_issue_detail_id.value = this.detailData.issue.value.exam_issue_details.filter(
        (issueDetail: any) => issueDetail.id === detail.exam_issue_detail_id
      )[0]
      this.detailData.note.value = detail.note
      const fileList: any = this.detailData.detailFileList
      fileList.list.forEach((fileField: FileField) => {
        fileField.value = detail[`${fileField.name}_name`]
        fileField.preview = detail[`${fileField.name}_path`]
      })
      this.formTitle = detail.body
      this.formButtonLabel = '更新'
      this.editPermission = detail.can_delete
      this.updateFlag = true
      this.selectedMonthlyLogDetailId = detail.id
      this.isFormModel = true
    },
    onAdd(): void {
      if (this.updateFlag) {
        this.clearFormValue()
      }
      this.formTitle = '新規追加'
      this.formButtonLabel = '追加'
      this.editPermission = true
      this.updateFlag = false
      this.selectedMonthlyLogDetailId = null
      this.isFormModel = true
    },
    clearFormValue(): void {
      Object.keys(this.detailData).forEach((key: string) => {
        if (key === 'issue') {
          this.detailData.issue.value = this.currentExam.exam_issues[0]
        } else if (key === 'exam_issue_detail_id') {
          const issueDetailList: any = this.detailData[key]
          issueDetailList.list = this.selectedIssue.exam_issue_details
          issueDetailList.value = issueDetailList.list[0]
        } else if (key === 'detailFileList') {
          const fileList: any = this.detailData[key]
          fileList.list.forEach((fileItem: FileField) => {
            fileItem.value = ''
            fileItem.preview = ''
          })
        } else {
          this.detailData[key].value = ''
        }
      })
    },
    hideForm(): void {
      this.isFormModel = false
      this.detailData.detailFileList.errorMessages = []
      if (this.updateFlag) {
        this.clearFormValue()
      }
    },
    switchDeleteModal(monthlyLogDetailId: number | null): void {
      this.isDeleteModel = !this.isDeleteModel
      this.selectedMonthlyLogDetailId = monthlyLogDetailId
    },
    fetchCurrentExam(examCode: number): void {
      this.$emit('onExamClick', examCode)
    },
    toggleSortIcon(): void {
      this.hideSortBox = !this.hideSortBox
    },
    sortingRadio(itemLabel: string): string {
      return this.sortBoxList.value.label === itemLabel
        ? 'radio_button_checked'
        : 'radio_button_unchecked'
    },
    onSortClick(item: any): void {
      this.sortBoxList.value = item
      this.sortBoxList.list.forEach((sortObj: any): void => {
        sortObj.iconName = this.sortingRadio(sortObj.label)
      })
      this.$scrollTo('#record_top')
      this.$emit('onSort', item.body)
    },
  },
})
</script>
