<template>
  <div>
    <MessageDialog />
    <Header
      id="scroll_to_header"
      :positions="positions"
      :header-menu="headerMenu"
    />
    <div class="PageContent">
      <RouterView :positions="positions" @fetchHeaderList="fetchHeader" />
    </div>
    <Footer />
    <ScrollButton to="#scroll_to_header">
      <CircledButton
        slot="button-content"
        :tab-index="100"
        icon-name="keyboard_arrow_up"
        :class="['--lg']"
      />
    </ScrollButton>
    <PageFilledLoader />
    <WaitingLoader />
  </div>
</template>
<script lang="ts">
import { Component, Vue } from 'nuxt-property-decorator'
import ScrollButton from '~/components/molecules/ScrollButton.vue'
import CircledButton from '~/components/molecules/CircledButton.vue'
import PageFilledLoader from '~/components/atoms/PageFilledLoader.vue'
import WaitingLoader from '~/components/molecules/WaitingLoader.vue'
import Header from '~/components/organisms/Header.vue'
import Footer from '~/components/organisms/Footer.vue'
import MessageDialog from '~/components/atoms/MessageDialog.vue'
import LayoutMixin from '~/mixins/LayoutMixin'
@Component({
  name: 'default',
  components: {
    ScrollButton,
    CircledButton,
    Header,
    Footer,
    PageFilledLoader,
    WaitingLoader,
    MessageDialog,
  },
  mixins: [LayoutMixin],
})
export default class Default extends Vue {}
</script>
