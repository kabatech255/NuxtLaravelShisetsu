<template>
  <div class="SummaryDescription">
    <h4 class="SummaryDescription_title">
      <AuthorIcon :author="writtenBy" />
      <span class="u-ml5 u-fontBold">{{ writtenBy.name }}さんより</span>
    </h4>
    <p class="SummaryDescription_value">{{ value }}</p>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import AuthorIcon from '~/components/molecules/AuthorIcon.vue'
@Component({
  components: { AuthorIcon },
})
export default class SummaryDescription extends Vue {
  @Prop({
    type: Object,
    default: (): object => ({
      name: '',
    }),
  })
  writtenBy?: object

  @Prop({ type: String, default: '' })
  value?: string
}
</script>
