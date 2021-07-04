<template>
  <nav class="ArticleListLayout">
    <ul class="ArticleListLayout_row">
      <li
        v-for="(article, index) in articles"
        :key="index"
        class="ArticleListLayout_col"
      >
        <FadeInBox
          :ref-key="`card_${index}`"
          :main-visual-height="positions.mainVisualHeight"
          :current-y="positions.currentY"
        >
          <Card
            slot="fadein-content"
            query-key="store_code"
            :article="article"
          />
        </FadeInBox>
      </li>
    </ul>
  </nav>
</template>
<script lang="ts">
import { Prop, Component, Vue } from 'nuxt-property-decorator'
import FadeInBox from '~/components/molecules/FadeInBox.vue'
import Card from '~/components/molecules/Card.vue'
import { CardObject } from '~/types/CardObject'
import { Positions } from '~/types/Positions'

@Component({
  components: { FadeInBox, Card },
})
export default class ArticleListLayout extends Vue {
  @Prop({ type: Array, required: true }) articles!: CardObject[]

  @Prop({ type: Object, required: true }) positions!: Positions
}
</script>
