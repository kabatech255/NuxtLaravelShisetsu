<template>
  <main class="p-profile" :style="minHeightStyle">
    <MypageMainLayout :breadcrumbs="breadcrumbs">
      <div slot="row-visual" class="MypageMainLayout_col --fixed">
        <RowVisual image-path="assets/img/analysis_flat.svg" />
      </div>
      <div slot="section-content">
        <ProfileLayout :author="author" :department-list="departmentList" />
      </div>
    </MypageMainLayout>
  </main>
</template>
<script lang="ts">
import { Vue, Prop, Component } from 'nuxt-property-decorator'
import MypageMainLayout from '~/components/atoms/MypageMainLayout.vue'
import RowVisual from '~/components/molecules/RowVisual.vue'
import { BreadCrumbObj } from '~/types/BreadCrumbs'
import { Author } from '~/types/Author'
import ProfileLayout from '~/components/organisms/ProfileLayout.vue'
import { Department } from '~/types/Department'
import PageMixin from '~/mixins/PageMixin'
@Component({
  layout: 'MyPageLayout',
  mixins: [PageMixin],
  components: { MypageMainLayout, RowVisual, ProfileLayout },
})
export default class settings extends Vue {
  async asyncData(app: any): Promise<any> {
    const departmentList: Department[] = await app.$axios
      .$get('/department')
      .catch((err: any): any => err)
    return { departmentList }
  }

  @Prop({ type: Object, required: true }) author!: Author

  get breadcrumbs(): BreadCrumbObj[] {
    return [
      {
        name: 'ページトップ',
        path: '/',
      },
      {
        name: 'マイページ',
        path: '/mypage',
      },
      {
        name: '設定',
        path: this.$route.fullPath,
      },
    ]
  }
}
</script>
