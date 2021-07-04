<template>
  <div class="SiteTopVisual">
    <div class="SiteTopVisual_row">
      <div class="SiteTopVisual_col --desc">
        <h1 class="SiteTopVisual_title">
          <span>自社内でコンプライアンスを<br />検査するシステム</span>
        </h1>
        <p class="SiteTopVisual_desc">
          コンプラ検査サイトは、自社が営業中の店舗等を抜き打ちで巡回・検査し、違反箇所をアップロードすることで社内共有するサイトです。
        </p>
        <TestLoginButton :is-login="isLogin" lg md-center class="u-mt30" />
      </div>
      <div class="SiteTopVisual_col">
        <ul class="SiteTopVisual_illustList">
          <li
            class="SiteTopVisual_illustItem"
            :style="bgImage('mono_firefighter.svg')"
          ></li>
          <li
            class="SiteTopVisual_illustItem"
            :style="bgImage('mono_shopping.svg')"
          ></li>
          <li
            class="SiteTopVisual_illustItem"
            :style="bgImage('mono_datagraph.svg')"
          ></li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import TestLoginButton from '~/components/molecules/TestLoginButton.vue'
import { assetPath } from '~/plugins/util'

@Component({
  components: { TestLoginButton },
})
export default class SiteTopVisual extends Vue {
  @Prop({ type: Boolean, default: false })
  isLogin?: boolean

  bgImage(src: string): object {
    return src === null
      ? {}
      : {
          backgroundImage: `url(${assetPath}/assets/img/${src})`,
        }
  }
}
</script>
