<template>
  <section class="IntroductionBox">
    <FadeInBox
      :ref-key="boxName"
      :main-visual-height="positions.mainVisualHeight"
      :current-y="positions.currentY"
    >
      <div slot="fadein-content" class="Container">
        <div class="IntroductionBox_content">
          <div class="IntroductionBox_descWrapper">
            <div class="IntroductionBox_desc">
              <h2 class="IntroductionBox_title">
                {{ title.main }} <span>{{ title.sub }}</span>
              </h2>
              <p class="IntroductionBox_detail">{{ detail }}</p>
              <slot name="desc-option"></slot>
              <div class="IntroductionBox_guest">
                <TestLoginButton :is-login="isLogin" lg />
              </div>
            </div>
          </div>
          <ul class="IntroductionBox_images">
            <li
              v-for="(src, index) in srcList"
              :key="index"
              class="IntroductionBox_image"
              :style="bgImage(src)"
            ></li>
          </ul>
        </div>
      </div>
    </FadeInBox>
  </section>
</template>
<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import { assetPath } from '~/plugins/util'
import FadeInBox from '~/components/molecules/FadeInBox.vue'
import TestLoginButton from '~/components/molecules/TestLoginButton.vue'
import { Positions } from '~/types/Positions'

@Component({
  components: { FadeInBox, TestLoginButton },
})
export default class IntroductionBox extends Vue {
  @Prop({
    type: Object,
    default: () => ({
      main: 'タイトル',
      sub: 'サブ',
    }),
  })
  title?: object

  @Prop({
    type: Array,
    default: () => [],
  })
  srcList?: string[]

  @Prop({
    type: String,
    default: '',
  })
  detail?: string

  @Prop({ type: Object, required: true })
  positions!: Positions

  @Prop({ type: String, default: 'introductionBox' })
  boxName?: string

  @Prop({ type: Boolean, default: false, required: true }) isLogin!: boolean

  bgImage(src: string): object {
    return {
      backgroundImage: `url(${assetPath}/assets/img/${src})`,
    }
  }
}
</script>
