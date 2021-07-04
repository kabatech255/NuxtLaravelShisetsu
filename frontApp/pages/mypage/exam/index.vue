<template>
  <div class="p-exam" :style="minHeightStyle">
    <div class="p-exam-row --fixed">
      <client-only>
        <RowVisual
          :image-path="rowVisualPath"
          :row-visual-title="pageTitle"
          :data-sub-title="pageSubTitle"
          :class="['--hasContent']"
        >
          <nav slot="RowVisual_inner" style="height: 100%">
            <InfoPanelList
              :info-panel-item="{ examinedBy, exam: infoPanelExam }"
              is-separate
            />
          </nav>
        </RowVisual>
      </client-only>
    </div>
    <div class="p-exam-row --sm" :class="hiddenClass">
      <client-only>
        <RowVisual
          :image-path="rowVisualPath"
          :class="['--hasContent']"
          style="height: initial; padding-top: 35px"
        >
          <div slot="RowVisual_inner">
            <h2 class="RowVisual_innerTop">
              <span class="RowVisual_innerRow">
                <small class="RowVisual_sub">{{ pageSubTitle }}</small>
                <strong class="RowVisual_innerTitle">{{ pageTitle }}</strong>
              </span>
            </h2>
            <InfoPanelList
              :info-panel-item="{ examinedBy, exam: infoPanelExam }"
            />
          </div>
        </RowVisual>
      </client-only>
    </div>
    <div class="p-exam-row --fill">
      <client-only>
        <ExamLayout :is-un-fetch="isUnFetch" />
      </client-only>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'

import { mapGetters } from 'vuex'
import RowVisual from '~/components/molecules/RowVisual.vue'
import InfoPanelList from '~/components/organisms/InfoPanelList.vue'
import ExamLayout from '~/components/organisms/ExamLayout.vue'
import { Author } from '~/types/Author'
import PageMixin from '~/mixins/PageMixin'
import { localTitle } from '~/plugins/util'

export default Vue.extend({
  layout: 'MyPageLayout',
  components: {
    RowVisual,
    ExamLayout,
    InfoPanelList,
  },
  mixins: [PageMixin],
  middleware: 'examApiCheck',
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
  async fetch({ store, query, redirect }) {
    await store.dispatch('exam/getExamApi', query.store_code)
    if (store.state.status.statusCode !== null) {
      redirect('/mypage/exam/select_shop')
    }
  },
  fetchOnServer: false,
  data: () => ({
    isShow: true as boolean,
    positionY: 0 as number,
  }),
  computed: {
    ...mapGetters({
      examApi: 'exam/currentExamApi',
      isUnFetch: 'exam/isUnFetch',
      currentExam: 'exam/currentExam',
      currentMonthlyLogs: 'exam/currentMonthlyLogs',
      currentMonthlyLogIndex: 'exam/currentMonthlyLogIndex',
    }),
    shopTitleStyle(): object {
      return {
        top: '0',
        left: '50%',
        height: '58px',
        transform: 'translate3d(-50%, -100%, 0)',
        lineHeight: '58px',
      }
    },
    rowVisualPath(): string {
      if (!this.examApi) {
        return 'assets/img/exam_slice.jpg'
      } else if (this.examApi.shop.file_name === null) {
        return 'assets/img/exam_slice.jpg'
      } else {
        return this.examApi.shop.file_name.replace('.jpg', '_split.jpg')
      }
    },
    pageTitle(): string {
      return !this.examApi ? '' : this.examApi.shop.name
    },
    pageSubTitle(): string {
      return !this.examApi ? '' : this.examApi.shop.zerofill_code
    },
    examColor(): string {
      return !this.currentExam ? '#eeeeee' : this.currentExam.color
    },
    examinedBy(): Author {
      if (!this.currentMonthlyLogs) {
        return {
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
        }
      }
      const currentMonthlyLog: any = this.currentMonthlyLogs[
        this.currentMonthlyLogIndex
      ]
      return currentMonthlyLog.examinator
    },
    examinedDate(): string | number {
      if (!this.currentMonthlyLogs) {
        return ''
      }
      const date = new Date(
        this.currentMonthlyLogs[this.currentMonthlyLogIndex].examined_at
      )
      return date.getDate()
    },
    examinedMonth(): string | number {
      if (!this.currentMonthlyLogs) {
        return ''
      }
      return this.currentMonthlyLogs[this.currentMonthlyLogIndex].examined_month
    },
    examinedYear(): string {
      if (!this.currentMonthlyLogs) {
        return ''
      }
      return this.currentMonthlyLogs[this.currentMonthlyLogIndex].examined_year
    },
    examIconName(): string {
      return !this.currentExam ? '#eeeeee' : this.currentExam.icon_name
    },
    examName(): string {
      return !this.currentExam ? '防災' : this.currentExam.name
    },
    hiddenClass(): object {
      return {
        '--hidden': !this.isShow,
      }
    },
    infoPanelExam(): any {
      return {
        color: this.examColor,
        name: this.examName,
        iconName: this.examIconName,
        year: this.examinedYear,
        month: this.examinedMonth,
        date: this.examinedDate,
      }
    },
  },
  watch: {
    isUnFetch: {
      handler(val) {
        if (val) {
          this.$router.push('/mypage/exam/select_shop')
        }
      },
    },
  },
  mounted() {
    this.$store.dispatch('exam/fetchExamApi')
    window.addEventListener('scroll', this.handleScroll)
  },
  methods: {
    handleScroll(): void {
      this.isShow = this.positionY > window.scrollY
      this.positionY = window.scrollY
    },
  },

  head: () => ({
    title: localTitle('検査画面'),
    head: [
      {
        hid: 'description',
        name: 'description',
        content: '施設検査サイトの検査画面です。',
      },
    ],
  }),
})
</script>
<style lang="scss">
.RowVisual_value {
  position: absolute;
  top: 0;
  left: 0;
  color: white;
}
</style>
