<template>
  <div class="ScorePanel">
    <BasePanel :title="title">
      <div slot="content" class="BasePanel_row">
        <CustomTable
          v-if="!!scores.length"
          :fields="fields"
          :items="scores"
          width="600px"
          align-center
          ellipsis
          border
        />
        <p v-else class="Text --emptyMsg u-alignCenter u-fontBold">
          ブックマークした指摘内容はありません
        </p>
      </div>
    </BasePanel>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import BasePanel from '~/components/molecules/BasePanel.vue'
import CustomTable from '~/components/molecules/CustomTable.vue'
import { BasePanelTitle } from '~/types/Dashboard'
import { TableField } from '~/types/Table'

@Component({
  components: { BasePanel, CustomTable },
})
export default class BookmarkPanel extends Vue {
  @Prop({ type: Array, default: () => ({}) })
  scores?: any[]

  fields: TableField[] = [
    {
      label: '検査日',
      key: 'examined_at',
      style: 'text-align: center; width: 70px;',
    },
    {
      label: '店舗名',
      key: 'store_name',
      style: 'text-align: center; width: 120px;',
      link: {
        name: 'mypage-exam',
        query: 'store_code',
      },
      ellipsis: true,
    },
    {
      label: '検査名',
      key: 'exam_name',
      style: 'text-align: center; width: 75px;',
    },
    {
      label: '指摘総数',
      key: 'total',
      style: 'text-align: center; width: 70px;',
    },
  ]

  title: BasePanelTitle = {
    id: 'scores',
    value: 'Your Latest',
    prependIcon: '',
    appendIcon: 'score',
    color: 'red',
    isEdit: false,
  }
}
</script>
