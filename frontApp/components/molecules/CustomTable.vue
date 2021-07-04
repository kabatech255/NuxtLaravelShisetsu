<template>
  <div class="Table" :class="[borderClass, latticeClass]">
    <table class="Table_ent" :style="tableStyle">
      <thead>
        <tr v-if="sort">
          <td
            v-for="(field, index) in fields"
            :key="index"
            class="-contractStatus"
            :class="sortCheck"
            :style="field.style"
            @click="onSort(field)"
          >
            <span class="Table_labelValue">{{ field.label }}</span>
            <Icon :name="sortIconName(field)" class="Table_sortIcon" />
          </td>
        </tr>
        <tr v-else>
          <td
            v-for="(field, index) in fields"
            :key="index"
            class="-contractStatus"
            :style="field.style"
          >
            <span class="Table_labelValue">{{ field.label }}</span>
          </td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items" :key="index">
          <td
            v-for="(field, idx) in fields"
            :key="idx"
            :style="colStyle"
            :class="isEllipsis(field)"
          >
            <RouterLink
              v-if="hasLink(field)"
              :to="linkTo(item, field)"
              tag="a"
              class="u-primaryLink"
              >{{ item[field.key] }}</RouterLink
            >
            <span v-else>{{ item[field.key] }}</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import Icon from '~/components/atoms/Icon.vue'
import { SortObj } from '~/types/Paginator'
@Component({
  components: { Icon },
})
export default class CustomTable extends Vue {
  @Prop({ type: Array, default: [{ key: 'name', label: '名前' }] })
  fields?: object[]

  @Prop({ type: Array, default: [] })
  items?: object[]

  @Prop({ type: Boolean, default: false })
  alignCenter?: boolean

  @Prop({ type: Boolean, default: false })
  ellipsis?: boolean

  @Prop({ type: Boolean, default: false })
  sort?: boolean

  @Prop({ type: Boolean, default: false })
  stripe?: boolean

  @Prop({ type: Boolean, default: false })
  border?: boolean

  @Prop({ type: Boolean, default: false })
  lattice?: boolean

  @Prop({ type: String, default: '100%' })
  width?: string

  @Prop({ type: Object, default: () => ({}) }) currentSorting?: SortObj

  get colStyle(): object {
    return this.alignCenter
      ? {
          textAlign: 'center',
        }
      : {}
  }

  get tableStyle(): object {
    return {
      maxWidth: this.width,
    }
  }

  isEllipsis(field: object): object {
    return {
      '--ellipsis': Object.prototype.hasOwnProperty.call(field, 'ellipsis'),
    }
  }

  get borderClass(): object {
    return {
      '--border': this.border,
      '--stripe': this.stripe,
    }
  }

  get latticeClass(): object {
    return {
      '--lattice': this.lattice,
    }
  }

  get sortCheck(): object {
    return {
      '--sort': this.sort,
    }
  }

  sortIconName(field: object): string {
    if (Object.keys(this.currentSorting).length > 0) {
      return this.fetchSortState(field)
    }

    if (!Object.prototype.hasOwnProperty.call(field, 'sort_status')) {
      return 'keyboard_arrow_down'
    }
    return field.sort_status === 'desc' ? 'arrow_drop_up' : 'arrow_drop_down'
  }

  fetchSortState(field: object): string {
    return field.key === this.currentSorting.sortBy &&
      this.currentSorting.orderBy === 'desc'
      ? 'arrow_drop_up'
      : 'arrow_drop_down'
  }

  hasLink(field: object): boolean {
    return Object.prototype.hasOwnProperty.call(field, 'link')
  }

  onSort(field: object): void {
    this.$emit('onSort', field)
  }

  /**
   * 'link' => [
   *   'name' => 'mypage-exam',  [リンク先]
   *   'query' => 'store_code',  [クエリ名]
   *   'params' => [
   *     'before' => [
   *       'name' => '集計'
   *     ]
   *   ]
   * ],
   * @param item
   * @param field
   */
  linkTo(item: any, field: any): object {
    const linkObj: any = {}
    Object.keys(field.link).forEach((key: string) => {
      if (key === 'name') {
        linkObj[key] = field.link[key]
      } else if (key === 'query') {
        linkObj.query = {}
        linkObj.query[field.link[key]] = item[field.link[key]]
      } else if (key === 'params') {
        Object.keys(field.link.params).forEach((paramsName: string) => {
          // paramsの設定
          if (paramsName === 'before') {
            linkObj.params = {}
            linkObj.params[paramsName] = {
              name: field.link.params.before.name,
              path: this.$route.fullPath,
            }
          }
        })
      }
    })
    return linkObj
  }
}
</script>
