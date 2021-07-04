<template>
  <div class="BookmarkCard" :class="wideClass">
    <div class="BookmarkCard_overlay" @click="toggleWide"></div>
    <RouterLink
      :to="`/mypage/exam?store_code=${bookmark.monthly_log.store_code}`"
      tag="button"
      class="BookmarkCard_body"
    >
      <div class="BookmarkCard_bg" :style="examColor"></div>
      <div class="BookmarkCard_thumb">
        <img
          :src="bookmark.primary_file_path"
          :alt="bookmark.body"
          class="BookmarkCard_image"
        />
      </div>
      <div class="BookmarkCard_bottom">
        <div class="BookmarkCard_row">
          <p class="BookmarkCard_col">
            {{ bookmark.body }}
          </p>
        </div>
        <div class="BookmarkCard_row --right">
          <span class="BookmarkCard_col u-fs12">
            {{ bookmark.shop_name }}
          </span>
          <span class="BookmarkCard_col u-fs12 u-ml5">
            {{ bookmark.examined_at }}
          </span>
        </div>
      </div>
    </RouterLink>
  </div>
</template>
<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'

@Component
export default class BookmarkCard extends Vue {
  @Prop({ type: Object, default: () => ({}) })
  bookmark?: any

  isWide: boolean = false

  get examColor(): object {
    return {
      backgroundColor: this.bookmark.exam_color,
    }
  }

  get wideClass(): object {
    return {
      '--wide': this.isWide,
    }
  }

  toggleWide(): void {
    // this.isWide = !this.isWide
  }
}
</script>
