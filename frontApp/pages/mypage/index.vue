<template>
  <main class="p-dashboard" :style="minHeightStyle">
    <div class="u-fill">
      <div class="p-dashboard-row --fullColumn">
        <div class="p-dashboard-col --fixed">
          <RowVisual image-path="assets/img/dashboard.jpg" />
        </div>
        <div class="p-dashboard-col --fill">
          <nav class="p-dashboard-nav">
            <ul class="p-dashboard-nav-row">
              <li class="p-dashboard-nav-col --flex">
                <BreadCrumb
                  :path-list="breadcrumbs"
                  :class="['--borderless', '--paddless']"
                />
              </li>
              <li class="p-dashboard-nav-col --u-xl">
                <div
                  class="p-dashboard-nav-mask"
                  :class="hidden"
                  @click="toggleOthers"
                ></div>
                <Icon
                  name="perm_contact_calendar"
                  class="p-dashboard-nav-icon --tip"
                  data-tooltip="みんなの予定"
                  @click="toggleOthers"
                />
                <div class="p-dashboard-nav-child" :class="hidden">
                  <OthersScheduleLayout
                    :others-daily-schedule="othersDailySchedule"
                  />
                </div>
              </li>
            </ul>
          </nav>
          <client-only>
            <ul class="DashboardGrid">
              <li class="DashboardGrid_box --bookmark">
                <BookmarkPanel :bookmarks="bookmarks" @onWorried="onWorry" />
              </li>
              <li class="DashboardGrid_box --calendar">
                <SchedulePanel
                  :schedules="schedules"
                  :schedule-form-data="scheduleFormData"
                  @onAdd="submitSchedule"
                  @onPrepare="fetchScheduleAttr"
                  @onDelete="deleteSchedule"
                  @onSharedChange="fetchEditableList"
                />
              </li>
              <li class="DashboardGrid_box --score">
                <ScorePanel :scores="scores" />
              </li>
              <li class="DashboardGrid_box --todo">
                <TodoPanel
                  :todo="todo"
                  :todo-form="todoForm"
                  @onTodoClick="toggleDone"
                  @onDelete="deleteTodo"
                  @onAdd="storeTodo"
                />
              </li>
              <li class="DashboardGrid_box --others">
                <OthersPanel :others-daily-schedule="othersDailySchedule" />
              </li>
            </ul>
          </client-only>
        </div>
      </div>
    </div>
    <FullLoader :is-loader="isLoader" />
  </main>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import RowVisual from '~/components/molecules/RowVisual.vue'
import BookmarkPanel from '~/components/organisms/BookmarkPanel.vue'
import ScorePanel from '~/components/organisms/ScorePanel.vue'
import TodoPanel from '~/components/organisms/TodoPanel.vue'
import SchedulePanel from '~/components/organisms/SchedulePanel.vue'
import OthersPanel from '~/components/organisms/OthersPanel.vue'
import { Author } from '~/types/Author'
import FullLoader from '~/components/atoms/FullLoader.vue'
import Icon from '~/components/atoms/Icon.vue'
import OthersScheduleLayout from '~/components/molecules/OthersScheduleLayout.vue'
import BreadCrumb from '~/components/molecules/BreadCrumb.vue'
import { Todo, ScheduleRecord, ScheduleObj } from '~/types/Dashboard'
import { BreadCrumbObj } from '~/types/BreadCrumbs'
import {
  reformatedScheduleDate,
  localTitle,
  scheduleColorList,
} from '~/plugins/util'
import { HeaderItem } from '~/types/Header'
import ScheduleHandler from '~/mixins/ScheduleHandler'
import TodoHandler from '~/mixins/TodoHandler'
import BookmarkHandler from '~/mixins/BookmarkHandler'
import PageMixin from '~/mixins/PageMixin'

@Component({
  layout: 'MyPageLayout',
  components: {
    RowVisual,
    BookmarkPanel,
    ScorePanel,
    TodoPanel,
    SchedulePanel,
    OthersPanel,
    FullLoader,
    Icon,
    OthersScheduleLayout,
    BreadCrumb,
  },
  mixins: [TodoHandler, ScheduleHandler, BookmarkHandler, PageMixin],
})
export default class Index extends Vue {
  @Prop({ type: Object, required: true }) author!: Author

  async asyncData(app: any): Promise<any> {
    const dashboardApi = await app.$axios
      .$get('/dashboard')
      .catch((err: any): any => {
        return { err }
      })

    return {
      scores: dashboardApi.scores,
      bookmarks: dashboardApi.bookmarks,
      todo: {
        list: dashboardApi.todos.map((todo: Todo): object => {
          todo.iconName = todo.icon_name
          todo.isShow = true
          todo.style =
            'text-align: left; flex-grow: 1; display: flex; align-items: center;'
          todo.appendIcon = 'remove_circle_outline'
          return todo
        }),
        keys: ['id', 'body'],
      },
      othersDailySchedule: dashboardApi.othersDailySchedule,
      schedules: {
        masks: {
          input: 'YYYY-MM-DD hh:mm',
          title: 'YYYY年M月',
          dayPopover: 'YYYY-M-D hh:mm',
        },
        locale: 'ja_JP',
        attributes: [
          ...dashboardApi.schedules.map(
            (schedule: ScheduleRecord, index: number): ScheduleObj => {
              const d = new Date(schedule.start)
              const h: number = d.getHours()
              const mm: string = `00${d.getMinutes()}`.slice(-2)

              return {
                key: index + 2,
                customData: {
                  id: schedule.id,
                  title: `${h}:${mm}\n${schedule.body}`,
                  body: schedule.body,
                  colorName: schedule.color,
                  startDisp: reformatedScheduleDate(schedule.start),
                  endDisp: reformatedScheduleDate(schedule.end),
                  shared_members: schedule.shared_members,
                  editable_members: schedule.editable_members,
                  can_edit: schedule.can_edit,
                  is_private: schedule.is_private,
                  style: `background: ${
                    scheduleColorList[schedule.color].code
                  }; color: #fefefe`,
                  isOpen: false,
                },
                dates: [
                  {
                    start: schedule.start,
                    end: schedule.end,
                  },
                ],
              }
            }
          ),
        ],
      },
    }
  }

  isShowOthers: boolean = false
  isLoader: boolean = false
  headerMenu: HeaderItem[] = [
    {
      label: 'スケジュール',
      iconSrc: 'event',
      to: '#schedules',
      offset: -60,
    },
    {
      label: 'ブックマーク',
      iconSrc: 'bookmarks',
      to: '#bookmarks',
      offset: -60,
    },
    {
      label: '最近の検査結果',
      iconSrc: 'score',
      to: '#scores',
      offset: -60,
    },
    {
      label: 'TODO',
      iconSrc: 'task',
      to: '#todos',
      offset: -60,
    },
  ]

  get hidden(): object {
    return {
      '--hidden': !this.isShowOthers,
    }
  }

  get today(): string {
    const d = new Date()
    const month = d.getMonth() + 1
    const day = d.getDate()
    return `${month}/${day}`
  }

  get breadcrumbs(): BreadCrumbObj[] {
    return [
      {
        name: 'ページトップ',
        path: '/',
      },
      {
        name: 'マイページ',
        path: this.$route.fullPath,
      },
    ]
  }

  toggleOthers() {
    this.isShowOthers = !this.isShowOthers
  }

  head() {
    return {
      title: localTitle('マイページ'),
      head: [
        {
          hid: 'description',
          name: 'description',
          content: '施設検査サイトのマイページトップです。',
        },
      ],
    }
  }
}
</script>
