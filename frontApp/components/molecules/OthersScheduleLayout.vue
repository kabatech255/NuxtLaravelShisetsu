<template>
  <div class="OthersScheduleLayout">
    <h3 class="OthersScheduleLayout_head">
      <span>みんなの予定</span>
      <span class="OthersScheduleLayout_date">（{{ today }}）</span>
    </h3>
    <ul v-if="!!othersDailySchedule.length" class="OthersScheduleLayout_list">
      <li
        v-for="(schedule, index) in othersDailySchedule"
        :key="index"
        class="OthersScheduleLayout_item"
      >
        <div
          v-for="(member, i) in schedule.shared_members"
          :key="i"
          class="OthersScheduleLayout_row"
        >
          <div class="OthersScheduleLayout_author">
            <AuthorIcon :author="member" />
            <span class="u-fs12 u-fontBold u-ml5">{{ member.name }}</span>
          </div>
          <p class="OthersScheduleLayout_value">
            <Icon
              v-if="schedule.is_private"
              name="lock"
              class="OthersScheduleLayout_private"
            />
            <span>{{ schedule.body }}</span>
          </p>
        </div>
      </li>
    </ul>
    <div v-else>
      <p class="Text --emptyMsg u-alignCenter u-fontBold u-px10">
        登録された本日の予定はありません
      </p>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import AuthorIcon from '~/components/molecules/AuthorIcon.vue'
import { OthersSchedule } from '~/types/Dashboard'

@Component({
  components: { AuthorIcon },
})
export default class OthersScheduleLayout extends Vue {
  @Prop({ type: Array, default: () => [] })
  othersDailySchedule?: OthersSchedule[]

  get today(): string {
    const d = new Date()
    const month = d.getMonth() + 1
    const day = d.getDate()
    return `${month}/${day}`
  }
}
</script>
