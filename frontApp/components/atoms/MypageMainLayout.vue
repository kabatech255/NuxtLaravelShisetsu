<template>
  <div class="MypageMainLayout">
    <div class="MypageMainLayout_row --fullColumn">
      <slot name="row-visual"></slot>
      <div class="MypageMainLayout_col --fill">
        <client-only>
          <nav class="MypageMainLayout_nav">
            <ul class="MypageMainLayout_menu">
              <li class="MypageMainLayout_menuItem --flex">
                <BreadCrumb
                  :path-list="breadcrumbs"
                  :class="['--borderless', '--paddless']"
                />
              </li>
            </ul>
          </nav>
          <section class="MypageMainLayout_main">
            <slot name="section-content"></slot>
          </section>
        </client-only>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import BreadCrumb from '~/components/molecules/BreadCrumb.vue'
import { BreadCrumbObj } from '~/types/BreadCrumbs'

@Component({
  components: { BreadCrumb },
})
export default class MypageMainLayout extends Vue {
  @Prop({ type: Array, default: () => [] }) breadcrumbs?: BreadCrumbObj[]

  deviceHeight: string = '100vh'

  mounted(): void {
    this.deviceHeight = window.innerHeight + 'px'
  }
}
</script>
