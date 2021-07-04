import { Component, Vue } from 'nuxt-property-decorator'
import { CardObject } from '~/types/CardObject'

@Component
export default class BookmarkHandler extends Vue {
  async onWorry(bookmark: CardObject): Promise<void> {
    bookmark.is_worried = !bookmark.is_worried
    if (bookmark.is_worried) {
      // ブックマーク再登録
      await this.worry(bookmark)
    } else {
      // ブックマーク解除
      await this.disworry(bookmark)
    }
  }

  async worry(bookmark: CardObject): Promise<void> {
    await this.$axios
      .$put(
        `/monthly_logs/${bookmark.monthly_log_id}/details/${bookmark.id}/worry`
      )
      .then((): void => {
        this.$store.commit('status/setStatus', {
          status: 200,
          messages: {
            success: ['ブックマークを元に戻しました'],
          },
        })
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async disworry(bookmark: CardObject) {
    await this.$axios
      .$delete(
        `/monthly_logs/${bookmark.monthly_log_id}/details/${bookmark.id}/unworry`
      )
      .then(() => {
        this.$store.commit('status/setStatus', {
          status: 204,
          messages: {
            success: ['「ページ移動」や「再読み込み」の操作後は元に戻せません'],
          },
        })
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }
}
