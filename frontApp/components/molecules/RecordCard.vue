<template>
  <div class="RecordCard">
    <div class="RecordCard_image" :class="improved" @click="onClickCard">
      <div class="RecordCard_noImage">
        <Icon :class="['RecordCard_noImageIcon']" name="no_photography" />
      </div>
      <div class="RecordCard_imageSrc" :style="bgImage"></div>
      <div v-if="monthlyLogDetail.is_worried" class="RecordCard_bookMark">
        <Icon
          name="bookmark"
          class="RecordCard_bookMarkIcon"
          data-tooltip="ブックマーク中"
        />
      </div>
    </div>
    <div class="RecordCard_bottom">
      <div class="RecordCard_row">
        <div class="HorizontalLayout --justifyBetween --vertical">
          <div class="HorizontalLayout_col --flex">
            <h4 class="RecordCard_title">
              <span class="RecordCard_value">{{ monthlyLogDetail.body }}</span>
            </h4>
          </div>
          <div class="HorizontalLayout_col">
            <MoreInfoLayout
              :info-data="infoData"
              @clickVerticalItem="onClickVerticalItem"
            />
          </div>
        </div>
      </div>
      <div class="RecordCard_row">
        <div class="HorizontalLayout --vertical --justifyBetween">
          <div class="HorizontalLayout_col">
            <p v-if="cannotDelete" class="RecordCard_author">
              {{ monthlyLogDetail.creator.name }}さんが追加しました
            </p>
          </div>
        </div>
      </div>
      <p v-if="monthlyLogDetail.is_improved" class="RecordCard_tag">改善済</p>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import { mapGetters } from 'vuex'
import { MonthlyLogDetail } from '~/types/MonthlyLogDetail'
import MoreInfoLayout from '~/components/organisms/MoreInfoLayout.vue'
import Icon from '~/components/atoms/Icon.vue'
export default Vue.extend({
  components: { Icon, MoreInfoLayout },
  props: {
    monthlyLogDetail: {
      type: Object as PropType<MonthlyLogDetail>,
      default: (): MonthlyLogDetail => ({
        id: 0,
        body: '違反内容',
        file_name: '',
        is_improved: 0,
      }),
    },
    isComplete: {
      type: Number as PropType<0 | 1>,
      default: 0,
    },
  },
  computed: {
    ...mapGetters({
      author: 'auth/currentAuthor',
    }),
    bgImage(): object {
      return this.monthlyLogDetail.primary_file_path !== ''
        ? {
            backgroundImage: `url(${this.monthlyLogDetail.primary_file_path})`,
          }
        : {
            backgroundImage: 'url()',
          }
    },
    improved(): object {
      return {
        '--improved': this.monthlyLogDetail.is_improved,
      }
    },
    infoData(): any {
      return {
        hasRowIcon: false,
        keys: ['label', 'label'],
        list: [
          {
            label: '更新',
            isShow: this.monthlyLogDetail.can_delete,
            onUpdate: true,
            style: 'flex-grow: 1; text-align: left',
          },
          {
            label: '削除',
            isShow: this.monthlyLogDetail.can_delete,
            onDelete: true,
            style: 'flex-grow: 1; text-align: left',
          },
          {
            label: this.monthlyLogDetail.is_worried
              ? 'ブックマークを解除'
              : 'ブックマーク',
            isShow: true,
            onWorry: true,
            style: 'flex-grow: 1; text-align: left',
          },
        ],
        activeTarget: {
          label: '',
        },
        classOptions: ['--min150'],
      }
    },
    cannotDelete(): boolean {
      // "検査中に" "他人が投稿した"ものを表示させたい
      return (
        !!this.author &&
        !(
          !!this.isComplete ||
          this.monthlyLogDetail.created_by === this.author.employee_id
        )
      )
    },
    worryClass(): object {
      return {
        '--worry': this.monthlyLogDetail.is_worried,
      }
    },
  },
  methods: {
    onClickVerticalItem(rowItem: any): void {
      if (Object.prototype.hasOwnProperty.call(rowItem, 'onDelete')) {
        this.$emit('onDeleteClick')
      } else if (Object.prototype.hasOwnProperty.call(rowItem, 'onUpdate')) {
        this.$emit('onUpdateClick')
      } else if (Object.prototype.hasOwnProperty.call(rowItem, 'onWorry')) {
        this.$emit('onWorryClick')
      }
    },
    onClickCard() {
      this.$emit('onUpdateClick')
    },
  },
})
</script>
