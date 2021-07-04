<template>
  <div class="MainVisual">
    <div class="MainVisual_panel">
      <CCarousel
        indicators
        animate
        :interval="6000"
        height="100%"
        class="MainVisual_list"
      >
        <CCarouselItem class="MainVisual_item" :style="heightStyle">
          <SiteTopVisual :is-login="isLogin" />
        </CCarouselItem>
        <CCarouselItem
          v-for="(panel, index) in panels"
          :key="index"
          class="MainVisual_item"
          :style="heightStyle"
        >
          <div class="MainVisual_row">
            <div class="MainVisual_col --desc" :style="bgColor(panel)">
              <h2 class="MainVisual_title">
                <IconMono :name="panel.icon_name" :width="24" />
                <span class="u-ml10">{{ panel.name }}</span>
              </h2>
              <div>
                <p class="MainVisual_desc">{{ panel.desc }}</p>
              </div>
              <div class="MainVisual_point">
                <h3 class="MainVisual_pointTitle">
                  <IconMono name="assets/img/checkit_white.svg" :width="20" />
                  <span class="u-ml10">ポイント</span>
                </h3>
                <ul class="MainVisual_pointList">
                  <li
                    v-for="(point, idx) in panel.points"
                    :key="idx"
                    class="MainVisual_pointItem"
                  >
                    <p class="MainVisual_pointValue">{{ point }}</p>
                  </li>
                  <li v-if="!!panel.points.length" class="u-pt20">等々</li>
                </ul>
              </div>
              <TestLoginButton
                :is-login="isLogin"
                sm-b12
                class="u-alignCenter"
              />
            </div>
            <div class="MainVisual_col --img" :style="bgImage(panel.src)"></div>
          </div>
        </CCarouselItem>
      </CCarousel>
    </div>
    <button
      v-scroll-to="'#scroll_to_article'"
      class="MainVisual_scroll"
      @click="effect"
    >
      <transition
        name="MainVisual_scrollRipple"
        @enter="rippleEnter"
        @after-enter="afterRippleEnter"
      >
        <span
          v-if="ripple"
          ref="scroll_ripple"
          class="MainVisual_scrollRipple"
        />
      </transition>
      <span>Scroll</span>
      <Icon name="touch_app" class="MainVisual_scrollIcon" />
    </button>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Icon from '~/components/atoms/Icon.vue'
import IconMono from '~/components/atoms/IconMono.vue'
import SiteTopVisual from '~/components/organisms/SiteTopVisual.vue'
import { assetPath } from '~/plugins/util'
import TestLoginButton from '~/components/molecules/TestLoginButton.vue'
import { ScrollObj } from '~/types/Header'
export type PanelObj = {
  icon_name: string
  src: string
  color: string
  name: string
  desc: string
  points: string[]
}
export default Vue.extend({
  components: {
    Icon,
    IconMono,
    SiteTopVisual,
    TestLoginButton,
  },
  props: {
    isLogin: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    panels: {
      type: Array as PropType<PanelObj[]>,
      default: (): PanelObj[] => [
        {
          icon_name: 'exams/1/fire_extinguisher.svg',
          src: 'exams/1/fire_extinguisher.svg',
          color: '#ea6254',
          name: '防災',
          desc:
            '火災の予防、および火災発生時の避難対応に関するチェックをします。',
          points: [
            '非常扉や防火扉等の前に物を置いていないか',
            '避難経路の幅員が確保されているか',
            'タコ足配線など火災の原因が放置されていないか',
            '従業員は避難時に備えて警笛を携帯しているか',
          ],
        },
        {
          icon_name: 'exams/1/fire_extinguisher.svg',
          src: 'exams/1/fire_extinguisher.svg',
          color: '#38c172',
          name: '食品',
          desc:
            'ヒトの口に入れる食品の品質管理を怠ると、お客様の健康被害に繋がりかねません。\n食品衛生法等の関係法令に照らし、要冷商品の温度管理や、売り場を清潔に保っているかをチェックします。',
          points: [
            '非常扉や防火扉等の前に物を置いていないか',
            '消防設備等は使えるか',
            '避難経路の幅員が確保されているか',
            'タコ足配線など火災の原因が放置されていないか',
            '従業員は避難時に備えて警笛を携帯しているか',
          ],
        },
        {
          icon_name: 'exams/1/fire_extinguisher.svg',
          src: 'exams/1/fire_extinguisher.svg',
          color: '#5a91ce',
          name: '高度医療',
          desc:
            'コンタクトレンズやピアッサーなど、誤用すると人体に影響の出る商品は、その取扱いに注意が必要です。\n厚生労働省の通達等に照らし、取扱店に課せられた義務が遵守されているかチェックします',
          points: [
            'コンタクトレンズの在庫場所に温度計が設置されているか',
            '購入者から同意書を取っているか',
            '従業者に対する教育訓練の実施状況等',
            '避難経路の幅員が確保されているか',
          ],
        },
      ],
    },
    mainVisualHeight: {
      type: Number as PropType<number>,
      default: 0,
    },
  },
  data: () => ({
    carouselOption: ['cycle', 'arrows', 'hide-delimiter-bg'] as string[],
    ripple: false as boolean,
    x: 0 as number,
    y: 0 as number,
  }),
  computed: {
    heightStyle(): object {
      return {
        height:
          this.mainVisualHeight === 0 ? '100vh' : this.mainVisualHeight + 'px',
      }
    },
  },
  methods: {
    bgImage(src: string): object {
      return src === null
        ? {}
        : {
            backgroundImage: `url(${assetPath}/${src})`,
          }
    },
    bgColor(panel: PanelObj): object {
      return {
        backgroundColor: panel.color,
      }
    },
    effect(e: any): void {
      this.x = e.offsetX
      this.y = e.offsetY
      this.ripple = !this.ripple
    },
    rippleEnter(): void {
      const rippleComp: any = this.$refs.scroll_ripple
      rippleComp.style.top = `${this.y}px`
      rippleComp.style.left = `${this.x}px`
    },
    afterRippleEnter(): void {
      this.ripple = false
    },
    scrollOption(): ScrollObj {
      return {
        easing: 'ease-in',
        offset: this.offset,
      }
    },
  },
})
</script>
