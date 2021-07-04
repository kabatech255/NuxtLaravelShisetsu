import { Component, Vue } from 'nuxt-property-decorator'
import { Positions } from '~/types/Positions'
import { HeaderItem } from '~/types/Header'

@Component({
  asyncData({ store, app }: any): void {
    if (app.positions.deviceHeight > 0) {
      store.commit('loader/setWaiting', false)
    }
  },
})
export default class LayoutMixin extends Vue {
  positions: Positions = {
    deviceWidth: 0,
    deviceHeight: 0,
    mainVisualHeight: 0,
    breakPoint: 671,
    currentY: 0,
  }

  headerMenu: HeaderItem[] = []

  fetchHeader(headerMenu: HeaderItem[]): void {
    this.headerMenu = headerMenu
  }

  mounted() {
    this.positions.deviceWidth = window.innerWidth
    this.positions.deviceHeight = window.innerHeight
    this.positions.currentY = this.positions.deviceHeight
    const multipliedBy: number =
      this.positions.deviceWidth < this.positions.breakPoint ? 1 : 0.667
    this.positions.mainVisualHeight =
      Math.floor(this.positions.deviceHeight * multipliedBy * 100) / 100
    window.addEventListener('scroll', this.setCurrentY)
  }

  setCurrentY() {
    this.positions.currentY = window.innerHeight + window.pageYOffset
  }
}
