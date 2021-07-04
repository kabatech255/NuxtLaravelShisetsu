<template>
  <div class="p-top">
    <MainVisual
      :panels="mainVisualPanels"
      :is-login="isLogin"
      :main-visual-height="positions.mainVisualHeight"
    />
    <div id="scroll_to_article" class="p-top-row">
      <div class="Container">
        <ContentTitle id="sample" value="最新の検査結果" />
        <ArticleListLayout
          :articles="articles"
          class="u-mt20"
          :positions="positions"
        />
      </div>
    </div>
    <div class="p-top-row">
      <ContentTitle id="about" value="機能紹介" />
      <IntroductionBox
        slot="fadein-content"
        :title="introductions.exam.title"
        :src-list="introductions.exam.srcList"
        :detail="introductions.exam.detail"
        :positions="positions"
        :is-login="isLogin"
        box-name="examBox"
        class="u-mt20"
      />
      <IntroductionBox
        slot="fadein-content"
        :title="introductions.aggregate.title"
        :src-list="introductions.aggregate.srcList"
        :detail="introductions.aggregate.detail"
        :positions="positions"
        :is-login="isLogin"
        box-name="aggregateBox"
      >
        <div slot="desc-option" class="u-mt30">
          <ul class="IntroductionBox_icons pl-0">
            <li class="IntroductionBox_iconWrapper">
              <div class="IntroductionBox_icon">
                <Icon class="IntroductionBox_iconSrc" name="date_range" />
                <p class="IntroductionBox_iconText">月別集計</p>
              </div>
            </li>
            <li class="IntroductionBox_iconWrapper">
              <div class="IntroductionBox_icon">
                <Icon class="IntroductionBox_iconSrc" name="store" />
                <p class="IntroductionBox_iconText">店舗別集計</p>
              </div>
            </li>
            <li class="IntroductionBox_iconWrapper">
              <div class="IntroductionBox_icon">
                <Icon
                  class="IntroductionBox_iconSrc"
                  name="align_horizontal_left"
                />
                <p class="IntroductionBox_iconText">項目別集計</p>
              </div>
            </li>
          </ul>
        </div>
      </IntroductionBox>
    </div>
    <div class="p-top-row">
      <ContentTitle id="how_to_exam" value="検査方法" />
      <Usage :usage="usage.exam" :positions="positions" />
      <TestLoginButton
        :is-login="isLogin"
        lg
        color-primary
        class="u-alignCenter u-pb30"
      />
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from 'nuxt-property-decorator'

import { mapGetters } from 'vuex'
import ArticleListLayout from '~/components/organisms/ArticleListLayout.vue'
import MainVisual from '~/components/molecules/MainVisual.vue'
import ContentTitle from '~/components/atoms/ContentTitle.vue'
import Icon from '~/components/atoms/Icon.vue'
import IntroductionBox from '~/components/atoms/IntroductionBox.vue'
import Usage from '~/components/molecules/Usage.vue'
import FadeInBox from '~/components/molecules/FadeInBox.vue'
import TestLoginButton from '~/components/molecules/TestLoginButton.vue'
import { Positions } from '~/types/Positions'
import PageMixin from '~/mixins/PageMixin'
import { HeaderItem } from '~/types/Header'

interface AsyncData {
  mainVisualPanels: object[]
  articles: object[]
}

@Component({
  components: {
    ArticleListLayout,
    MainVisual,
    ContentTitle,
    Icon,
    IntroductionBox,
    Usage,
    FadeInBox,
    TestLoginButton,
  },
  mixins: [PageMixin],
  computed: {
    ...mapGetters({
      isLogin: 'auth/isLogin',
    }),
  },
})
export default class Index extends Vue {
  async asyncData({ app }: any): Promise<AsyncData> {
    const topApi: any = await app.$axios.$get('/').catch((err: any): any => {
      return err.response.status
    })
    return {
      mainVisualPanels: topApi.mainVisualPanels,
      articles: topApi.articles,
    }
  }

  @Prop({ type: Object, required: true }) positions!: Positions

  @Watch('route', { immediate: true })
  handler(): void {
    if (this.positions.mainVisualHeight > 0) {
      this.$store.commit('loader/setWaiting', false)
    }
  }

  headerMenu: HeaderItem[] = [
    {
      label: 'About',
      iconSrc: 'info',
      to: '#about',
    },
    {
      label: 'Usage',
      iconSrc: 'settings_cell',
      to: '#how_to_exam',
    },
  ]

  introductions: object = {
    exam: {
      title: {
        main: '検査機能',
        sub: 'Exam',
      },
      srcList: ['exam1.jpg', 'exam2.jpg', 'exam3.jpg', 'exam4.jpg'],
      detail:
        '検査項目に沿って店舗を巡回・検査することを想定して実装しました。\n指摘画像は検査結果としてデータベースに蓄積されます。',
    },
    aggregate: {
      title: {
        main: '集計機能',
        sub: 'Aggregate',
      },
      srcList: [
        'analysis1.jpg',
        'analysis2.jpg',
        'analysis3.jpg',
        'analysis4.jpg',
      ],
      detail:
        '蓄積された検査結果は月別、支社別などの観点でグラフ化しております。\nこのサイトは社内コンプライアンスの状況分析・改善のためにご活用いただけるよう、アップデートしていく予定です。',
    },
  }

  usage: object = {
    exam: ['step1.png', 'step2.png', 'step3.png'],
  }
}
</script>
