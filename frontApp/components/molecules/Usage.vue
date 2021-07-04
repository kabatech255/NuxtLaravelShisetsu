<template>
  <div class="Usage">
    <FadeInBox
      ref-key="usage"
      :main-visual-height="positions.mainVisualHeight"
      :current-y="positions.currentY"
    >
      <div slot="fadein-content" class="Container">
        <ul class="Usage_row">
          <li
            v-for="(item, index) in usage"
            :key="index"
            class="Usage_col u-md-col-12 u-col-4"
          >
            <div class="Usage_item">
              <img
                class="Usage_image"
                :src="imgSrc(item)"
                :alt="`how_to_use_${index}`"
              />
            </div>
          </li>
        </ul>
      </div>
    </FadeInBox>
  </div>
</template>
<script lang="ts">
import { Prop, Component, Vue } from 'nuxt-property-decorator'
import { assetPath } from '~/plugins/util'
import FadeInBox from '~/components/molecules/FadeInBox.vue'
import { Positions } from '~/types/Positions'
@Component({
  components: { FadeInBox },
})
export default class Usage extends Vue {
  @Prop({ type: Array, default: () => [] }) usage!: string[]

  @Prop({ type: Object, required: true }) positions!: Positions

  imgSrc(src: string): string {
    return `${assetPath}/assets/img/${src}`
  }
}
</script>
