import Vue, { PropType } from 'vue'
import VueScrollTo from 'vue-scrollto'
import { mapGetters } from 'vuex'
import { assetPath } from '~/plugins/util'
import HeaderLink from '~/components/molecules/HeaderLink.vue'
import AuthNav from '~/components/molecules/AuthNav.vue'
import Icon from '~/components/atoms/Icon.vue'
import { HeaderItem, ScrollObj } from '~/types/Header'
import { Positions } from '~/types/Positions'

Vue.use(VueScrollTo)

export default Vue.extend({
  components: { HeaderLink, AuthNav, Icon },
  props: {
    headerMenu: {
      type: Array as PropType<HeaderItem[]>,
      default: (): HeaderItem[] => [
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
      ],
    },
    positions: {
      type: Object as PropType<Positions>,
      default: (): Positions => ({
        deviceWidth: 0,
        deviceHeight: 0,
        mainVisualHeight: 0,
        breakPoint: 671,
        currentY: 0,
      }),
    },
  },
  data: () => ({
    isShow: false as boolean,
    authMenuSwitch: false as boolean,
    group: null,
    headerPositionY: 0 as number,
    headerHeight: 92 as number,
    offset: -90 as number,
    shouldWhitePath: ['/'],
  }),
  computed: {
    ...mapGetters({
      isLogin: 'auth/isLogin',
      author: 'auth/currentAuthor',
    }),
    isTransparent(): object | string[] {
      const currentPath = this.$route.path
      if (this.headerPositionY > this.switchPoint) {
        return []
      }
      return {
        '--transparent': this.shouldWhitePath.includes(currentPath),
      }
    },
    bgAuthor(): object {
      return {
        backgroundImage: `url(${assetPath}/assets/img/analysis1.jpg)`,
      }
    },
    active(): object {
      return {
        '--active': this.authMenuSwitch,
      }
    },
    switchPoint(): number {
      return this.positions.mainVisualHeight - this.headerHeight
    },
  },
  watch: {
    $route: {
      handler() {
        this.isShow = false
      },
      immediate: true,
    },
  },
  mounted() {
    window.addEventListener('scroll', this.handleScroll)
    const headerComp: any = this.$refs.header
    this.headerHeight = headerComp.clientHeight
  },
  methods: {
    handleScroll(): void {
      this.headerPositionY = window.scrollY
    },
    toggle(): void {
      this.isShow = !this.isShow
    },
    onlyGuest(isOnlyGuest: boolean): boolean {
      return isOnlyGuest && !this.isLogin
    },
    async logout(): Promise<void> {
      let path: string = '/login'
      if (this.$route.fullPath.match(/^\/admin\/.*$/g)) {
        await this.$store.dispatch('admin/logout')
        path = '/'
      } else {
        await this.$store.dispatch('auth/logout')
      }
      this.authMenuSwitch = false
      this.$router.push(path)
    },
    moveTo(headerItem: HeaderItem): void {
      this.isShow = false
      if (Object.prototype.hasOwnProperty.call(headerItem, 'offset')) {
        this.offset = headerItem.offset || -90
      }
      if (headerItem.to.match(/^\//)) {
        this.$router.push(headerItem.to)
      } else {
        this.$scrollTo(headerItem.to, 800, this.scrollOption())
      }
    },
    scrollOption(): ScrollObj {
      return {
        easing: 'ease-in',
        offset: this.offset,
      }
    },
  },
})
