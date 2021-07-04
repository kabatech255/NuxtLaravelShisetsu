<template>
  <div class="AdminLayout">
    <MessageDialog />
    <div class="PageContent --mypage">
      <div v-if="isAdmin" class="AdminLayout_side">
        <Sidebar />
      </div>
      <div class="AdminLayout_content">
        <Header
          id="scroll_to_header"
          :header-menu="headerMenu"
          :positions="positions"
        />
        <main class="AdminLayout_main">
          <RouterView
            :admin="admin"
            :positions="positions"
            @fetchHeaderList="fetchHeader"
          />
        </main>
      </div>
    </div>
    <ScrollButton to="#scroll_to_header" :class="['--barUp']">
      <CircledButton
        slot="button-content"
        :tab-index="100"
        icon-name="keyboard_arrow_up"
        :class="['--lg']"
      />
    </ScrollButton>
    <PageFilledLoader />
  </div>
</template>
<script lang="ts">
import { Component, Watch, Vue } from 'nuxt-property-decorator'
import { mapGetters } from 'vuex'
import ScrollButton from '~/components/molecules/ScrollButton.vue'
import CircledButton from '~/components/molecules/CircledButton.vue'
import PageFilledLoader from '~/components/atoms/PageFilledLoader.vue'
import Header from '~/components/admin/organisms/Header.vue'
import Sidebar from '~/components/organisms/Sidebar.vue'
import MessageDialog from '~/components/atoms/MessageDialog.vue'
import { UNAUTHORIZED } from '~/plugins/util'
import LayoutMixin from '~/mixins/LayoutMixin'

@Component({
  name: 'AdminLayout',
  middleware: 'adminCheck',
  mixins: [LayoutMixin],
  components: {
    ScrollButton,
    CircledButton,
    Header,
    Sidebar,
    PageFilledLoader,
    MessageDialog,
  },
  computed: {
    ...mapGetters({
      statusCode: 'status/status',
      admin: 'admin/currentAdmin',
      isAdmin: 'admin/isAdmin',
    }),
  },
})
export default class MyPageLayout extends Vue {
  @Watch('statusCode')
  onError(val: number): void {
    if (val === UNAUTHORIZED) {
      this.$router.push({
        name: 'admin-login',
        params: {
          before: this.$route.fullPath,
        },
      })
    }
  }
}
</script>
