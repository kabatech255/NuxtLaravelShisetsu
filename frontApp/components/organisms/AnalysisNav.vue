<template>
  <nav class="AnalysisNav">
    <div class="AnalysisNav_bg">
      <RowVisual image-path="assets/img/analysis_flat.svg" />
    </div>
    <ul class="AnalysisNav_menu">
      <li class="AnalysisNav_item --flex">
        <BreadCrumb
          :path-list="breadcrumbs"
          :class="['--borderless', '--paddless']"
        />
      </li>
      <li class="AnalysisNav_item">
        <SelectBox
          :item="examItem"
          :class="['--head']"
          @onChange="changeChart"
        />
      </li>
    </ul>
  </nav>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import SelectBox from '~/components/molecules/SelectBox.vue'
import RowVisual from '~/components/molecules/RowVisual.vue'
import BreadCrumb from '~/components/molecules/BreadCrumb.vue'
import { BreadCrumbObj } from '~/types/BreadCrumbs'
import { SelectField } from '~/types/FormObj'

@Component({
  components: { SelectBox, RowVisual, BreadCrumb },
})
export default class AnalysisNav extends Vue {
  @Prop({
    type: Object,
    default: () => ({
      name: '',
      label: '',
      type: 'select',
      labelKey: 'name',
      value: '',
      list: [],
      rules: [() => true],
      errorMessages: [],
      options: [],
    }),
  })
  examItem?: SelectField

  changeChart(exam: any): void {
    this.$emit('onChange', exam)
  }

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
        name: '集計',
        path: this.$route.fullPath,
      },
    ]
  }
}
</script>
