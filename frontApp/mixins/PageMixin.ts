import { Vue, Component, Prop, Watch } from 'nuxt-property-decorator'
import VueScrollTo from 'vue-scrollto'
import { HeaderItem } from '~/types/Header'
import { Positions } from '~/types/Positions'
Vue.use(VueScrollTo)

@Component
export default class PageMixin extends Vue {
  @Prop({ type: Object, required: true }) positions!: Positions

  @Watch('positions.deviceHeight', { immediate: true })
  stopWaitingLoader(val: number): void {
    if (val > 0) {
      this.$store.commit('loader/setWaiting', false)
    }
  }

  headerMenu: HeaderItem[] = [
    {
      label: 'About',
      iconSrc: 'info',
      to: '/#about',
    },
    {
      label: 'Usage',
      iconSrc: 'settings_cell',
      to: '/#how_to_exam',
    },
  ]

  get minHeightStyle(): object {
    return {
      minHeight: this.positions.deviceHeight + 'px',
    }
  }

  created(): void {
    this.$emit('fetchHeaderList', this.headerMenu)
  }

  mounted() {
    const to: null | any[] = this.$route.fullPath.match(/#.*$/g)
    if (to !== null) {
      setTimeout(() => {
        this.$scrollTo(to[0], 1500, {
          easing: 'easeInOutQuart',
          offset: -90,
        })
      }, 1200)
    }
  }
}
