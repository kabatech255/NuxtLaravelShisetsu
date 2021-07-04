<template>
  <div :style="withFlex" style="height: 100%">
    <ul v-if="isSeparate" class="InfoPanelList --left">
      <li class="InfoPanelList_col">
        <ColorPanel
          color="#666666"
          :class="['--isClick']"
          onclick="history.back()"
        >
          <button slot="ColorPanel_content" class="ColorPanel_inner">
            <Icon name="arrow_back" class="ColorPanel_icon" />
            <span class="ColorPanel_label">戻る</span>
          </button>
        </ColorPanel>
      </li>
    </ul>
    <ul class="InfoPanelList">
      <li v-if="!isSeparate" class="InfoPanelList_col">
        <ColorPanel
          color="#666666"
          :class="['--isClick']"
          onclick="history.back()"
        >
          <button slot="ColorPanel_content" class="ColorPanel_inner">
            <Icon name="arrow_back" class="ColorPanel_icon" />
            <span class="ColorPanel_label">戻る</span>
          </button>
        </ColorPanel>
      </li>
      <li class="InfoPanelList_col">
        <ColorPanel :color="infoPanelItem.exam.color">
          <div slot="ColorPanel_content" class="ColorPanel_inner">
            <IconMono :name="infoPanelItem.exam.iconName" :width="30" />
            <p>
              {{ infoPanelItem.exam.name }}
            </p>
          </div>
        </ColorPanel>
      </li>
      <li class="InfoPanelList_col">
        <ColorPanel color="#ffc321">
          <div slot="ColorPanel_content" class="ColorPanel_inner">
            <IconMono name="assets/img/calendar_white.svg" :width="30" />
            <p>
              {{ infoPanelItem.exam.year }}.{{ infoPanelItem.exam.month }}.{{
                infoPanelItem.exam.date
              }}
            </p>
          </div>
        </ColorPanel>
      </li>
      <li class="InfoPanelList_col">
        <ColorPanel color="#38c172">
          <div slot="ColorPanel_content" class="ColorPanel_inner">
            <div
              class="ColorPanel_authIcon"
              :data-author="infoPanelItem.examinedBy.name"
            >
              <AuthorIcon
                :author="infoPanelItem.examinedBy"
                :color="infoPanelItem.exam.color"
              />
            </div>
            <p>検査者</p>
          </div>
        </ColorPanel>
      </li>
    </ul>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import { Author } from '~/types/Author'
import ColorPanel from '~/components/molecules/ColorPanel.vue'
import IconMono from '~/components/atoms/IconMono.vue'
import Icon from '~/components/atoms/Icon.vue'
import AuthorIcon from '~/components/molecules/AuthorIcon.vue'
export type InfoPanelObj = {
  exam: {
    color: string
    name: string
    iconName: string
    year: string
    month: string
    date: string
  }
  examinedBy: Author
}

@Component({
  components: { IconMono, Icon, ColorPanel, AuthorIcon },
})
export default class InfoPanelList extends Vue {
  @Prop({ type: Object, default: () => ({}) })
  infoPanelItem?: InfoPanelObj

  @Prop({ type: Boolean, default: false })
  isSeparate?: boolean

  get withFlex(): object {
    return this.isSeparate
      ? {
          display: 'flex',
        }
      : {}
  }
}
</script>
