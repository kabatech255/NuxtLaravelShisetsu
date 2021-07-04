<template>
  <div class="AuthNav">
    <div class="AuthNav_mask" :class="active" @click="toggleAuthMenu"></div>
    <div class="AuthNav_authorIcon" @click="toggleAuthMenu">
      <AuthorIcon :author="author" />
    </div>
    <nav class="AuthNav_menuWrapper" :class="active">
      <h3 class="AuthNav_menuTitle">{{ author.name }}さん</h3>
      <div class="AuthNav_menu">
        <RouterLink tag="a" :to="mypageLink" class="AuthNav_menuItem"
          >マイページ</RouterLink
        >
        <a class="AuthNav_menuItem" @click="$emit('onClickLogout')">
          ログアウト
        </a>
      </div>
    </nav>
  </div>
</template>
<script lang="ts">
import { Component, Watch, Vue, Prop } from 'nuxt-property-decorator'
import { assetPath } from '~/plugins/util'
import AuthorIcon from '~/components/molecules/AuthorIcon.vue'
import { Author } from '~/types/Author'

@Component({
  components: { AuthorIcon },
})
export default class AuthNav extends Vue {
  @Prop({ type: Object, default: null }) author?: Author | null
  @Prop({ type: String, default: '/mypage' }) mypageLink?: string

  authMenuSwitch: boolean = false

  get bgAuthor(): object {
    return {
      backgroundImage: `url(${assetPath}/assets/img/analysis1.jpg)`,
    }
  }

  get active(): object {
    return {
      '--active': this.authMenuSwitch,
    }
  }

  @Watch('route', { immediate: true })
  handler() {
    this.authMenuSwitch = false
  }

  toggleAuthMenu(): void {
    this.authMenuSwitch = !this.authMenuSwitch
  }
}
</script>
