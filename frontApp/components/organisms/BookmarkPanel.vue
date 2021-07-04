<template>
  <div id="bookmarks" class="BookmarkPanel">
    <BasePanel :title="title">
      <div slot="content" class="BasePanel_row">
        <ul v-if="!!bookmarks.length" class="BookmarkPanel_row">
          <li
            v-for="(bookmark, index) in bookmarks"
            :key="index"
            class="BookmarkPanel_col"
          >
            <Card
              :article="bookmark"
              :more-info-data="moreInfoData(bookmark)"
              :is-active="bookmark.is_worried"
              query-key="store_code"
              has-info
              @onClickVerticalItem="onWorried"
            />
          </li>
        </ul>
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
import Card from '~/components/molecules/Card.vue'
import { BasePanelTitle } from '~/types/Dashboard'
import { CardObject } from '~/types/CardObject'
@Component({
  components: { BasePanel, Card },
})
export default class BookmarkPanel extends Vue {
  @Prop({ type: [Object, Array], default: () => ({}) })
  bookmarks?: CardObject[]

  title: BasePanelTitle = {
    id: 'bookmarks',
    value: 'Bookmark',
    prependIcon: '',
    appendIcon: 'bookmarks',
    color: 'green',
    isEdit: false,
  }

  moreInfoData(bookmark: any): object {
    return {
      hasRowIcon: false,
      keys: ['label', 'label'],
      list: [
        {
          label: bookmark.is_worried ? 'ブックマークを解除' : 'ブックマーク',
          isShow: true,
          onWorry: true,
        },
      ],
      activeTarget: {
        label: '',
      },
      classOptions: ['--min150'],
    }
  }

  onWorried(bookmark: any) {
    this.$emit('onWorried', bookmark)
  }
}
</script>
