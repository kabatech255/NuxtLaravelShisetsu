<template>
  <div class="Card" :class="disabledStyle">
    <div class="Card_disabled">
      <button class="Card_restore" @click="rowClicked">元に戻す</button>
    </div>
    <div class="Card_image">
      <div class="Card_noImage">
        <Icon :class="['Card_noImageIcon']" name="no_photography" />
      </div>
      <RouterLink
        :to="toExam"
        tag="a"
        class="Card_imageSrc"
        :style="bgImage"
      ></RouterLink>
    </div>
    <div class="Card_bottom">
      <h3 class="Card_title">{{ article.title }}</h3>
      <div class="Card_body">
        <span class="Card_desc">{{ article.desc }}</span>
        <span class="Card_tag" :style="tagStyle">{{ article.tag }}</span>
        <span class="Card_icon">
          <MoreInfoLayout
            v-if="hasInfo"
            :info-data="moreInfoData"
            @clickVerticalItem="rowClicked"
          />
        </span>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Prop, Component, Vue } from 'nuxt-property-decorator'
import { CardObject } from '~/types/CardObject'
import { assetPath } from '~/plugins/util'
import Icon from '~/components/atoms/Icon.vue'
import MoreInfoLayout from '~/components/organisms/MoreInfoLayout.vue'

@Component({
  components: { Icon, MoreInfoLayout },
})
export default class Card extends Vue {
  @Prop({ type: Object, required: true }) article!: CardObject
  @Prop({ type: Object, default: () => ({}) }) moreInfoData?: CardObject

  @Prop({ type: Boolean, default: false }) hasInfo?: boolean
  @Prop({ type: Boolean, default: true }) isActive?: boolean
  @Prop({ type: String, required: true, default: 'store_code' })
  queryKey!: string

  get bgImage(): object {
    return this.article.src
      ? {
          backgroundImage: `url(${assetPath}/${this.article.src})`,
        }
      : {
          backgroundImage: `url()`,
        }
  }

  get tagStyle(): object {
    return {
      backgroundColor: this.article.color,
    }
  }

  get disabledStyle() {
    return {
      '--disabled': !this.isActive,
    }
  }

  get toExam(): object {
    return {
      name: 'mypage-exam',
      query: {
        store_code: this.article[this.queryKey],
      },
    }
  }

  rowClicked(): void {
    this.$emit('onClickVerticalItem', this.article)
  }
}
</script>
