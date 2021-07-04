<template>
  <div :style="authorImage" class="AuthorIcon">
    <span class="AuthorIcon_text" :style="transparent">{{ firstStr }}</span>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import { Author } from '~/types/Author'
import { primaryColor, assetPath } from '~/plugins/util'

@Component
export default class AuthorIcon extends Vue {
  @Prop({ type: Object, required: true }) author!: Author

  @Prop({ type: String, default: '#aaaaaa' }) color?: string

  get authorImage(): boolean | object {
    return this.author.file_name === null
      ? {
          backgroundColor: this.color,
        }
      : {
          backgroundImage: `url(${assetPath}/${this.author.file_name})`,
          backgroundColor: this.color,
        }
  }

  get firstStr(): string {
    return this.author.name[0]
  }

  get transparent(): object {
    if (this.author.file_name !== null) {
      return {
        color: 'transparent',
      }
    } else {
      return {}
    }
  }
}
</script>
