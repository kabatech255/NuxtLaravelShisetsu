<template>
  <div class="OthersPanel">
    <BasePanel :title="title">
      <div slot="content" class="BasePanel_row">
        <OthersScheduleLayout :others-daily-schedule="othersDailySchedule" />
      </div>
    </BasePanel>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import BasePanel from '~/components/molecules/BasePanel.vue'
import OthersScheduleLayout from '~/components/molecules/OthersScheduleLayout.vue'
import { BasePanelTitle, OthersSchedule } from '~/types/Dashboard'
import AuthorIcon from '~/components/molecules/AuthorIcon.vue'
@Component({
  components: { BasePanel, AuthorIcon, OthersScheduleLayout },
})
export default class BookmarkPanel extends Vue {
  @Prop({ type: [Array], default: () => [] })
  othersDailySchedule?: OthersSchedule[]

  title: BasePanelTitle = {
    id: 'others',
    value: 'Others',
    prependIcon: '',
    appendIcon: '',
    color: 'black',
    isEdit: false,
  }

  get today(): string {
    const d = new Date()
    const month = d.getMonth() + 1
    const day = d.getDate()
    return `${month}/${day}`
  }
}
</script>
