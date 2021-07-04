<template>
  <div class="AdminPageContent">
    <h1>管理者画面ダッシュボード</h1>
    <div v-cloak class="u-mt50">
      <div style="max-width: 300px">
        <SelectBox :item="masterCategoryItems" @onChange="changeCategory" />
      </div>
      <div v-if="items.length > 0">
        <p class="u-mb20">
          {{ paginator.total }}件中、{{ paginator.from }}〜{{
            paginator.to
          }}件を表示中
        </p>
        <nav class="Pager u-mb10">
          <ul class="Pager_list">
            <li v-for="n in paginator.last_page" :key="n">
              <a
                class="Pager_item"
                :class="current(n)"
                @click.prevent="movePage(n)"
                >{{ n }}</a
              >
            </li>
          </ul>
        </nav>
        <EmptyCard>
          <CustomTable
            slot="card-content"
            :fields="fields"
            :items="items"
            :current-sorting="currentSorting"
            align-center
            lattice
            sort
            stripe
            @onSort="sort"
          />
        </EmptyCard>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import PageMixin from '~/mixins/PageMixin'
import { Paginator, SortObj } from '~/types/Paginator'
import CustomTable from '~/components/molecules/CustomTable.vue'
import EmptyCard from '~/components/atoms/EmptyCard.vue'
import SelectBox from '~/components/molecules/SelectBox.vue'
import { SelectField } from '~/types/FormObj'
@Component({
  layout: 'AdminLayout',
  mixins: [PageMixin],
  components: { CustomTable, EmptyCard, SelectBox },
})
export default class AdminIndex extends Vue {
  @Prop({ type: Object, default: null }) admin?: any

  la: string = 'user.email'
  currentModel: string = ''
  fields: object[] = []
  items: object[] = []
  paginator: Paginator = {
    current_page: 1,
    last_page: 0,
    from: 0,
    to: 0,
    total: 0,
  }

  currentSorting: SortObj = {
    sortBy: null,
    orderBy: null,
  }

  masterCategoryItems: SelectField = {
    name: '',
    label: 'マスター種別',
    type: 'select',
    labelKey: 'name',
    value: '',
    list: [
      {
        name: '社員',
        modelName: 'Examinator',
        relations: ['User'],
      },
      {
        name: '店舗',
        modelName: 'Shop',
        relations: ['Department'],
      },
    ],
    rules: [() => true],
    errorMessages: [],
    options: [],
  }

  res: any = null

  async mounted(): Promise<void> {
    this.masterCategoryItems.value = {
      name: '社員',
      modelName: 'Examinator',
    }
    this.currentModel = this.masterCategoryItems.value.modelName
    await this.fetchTableData({
      modelName: this.currentModel,
    })
  }

  async fetchTableData(query: object): Promise<void> {
    const queryStr: string = Object.keys(query)
      .map((key: string) => `${key}=${query[key]}`)
      .join('&')
    const path: string = `/admin/master/index?${queryStr}`
    await this.$axios
      .$get(path)
      .then((response): void => {
        this.fields = response.fields
        this.items = response.items.data
        delete response.items.data
        this.paginator = response.items
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async movePage(pageNumber: number): Promise<void> {
    await this.fetchTableData({
      modelName: this.currentModel,
      page: pageNumber,
      sortBy: this.currentSorting.sortBy,
      orderBy: this.currentSorting.orderBy,
    })
  }

  async sort(field: object) {
    this.fetchCurrentSorting(field)
    await this.fetchTableData({
      modelName: this.currentModel,
      page: 1,
      sortBy: this.currentSorting.sortBy,
      orderBy: this.currentSorting.orderBy,
    })
  }

  async changeCategory(): Promise<void> {
    this.fetchCurrentSorting({
      key: null,
    })
    this.currentModel = this.masterCategoryItems.value.modelName
    await this.fetchTableData({
      modelName: this.currentModel,
    })
  }

  current(pageNumber: number): object {
    return {
      '--current': this.paginator.current_page === pageNumber,
    }
  }

  isEmpty(obj: object): boolean {
    return !Object.keys(obj).length
  }

  fetchCurrentSorting(field: any): void {
    const orderBy =
      this.currentSorting.sortBy === field.key ? this.reversed() : 'desc'
    this.currentSorting.sortBy = field.key
    this.currentSorting.orderBy = orderBy
  }

  reversed(): string {
    return this.currentSorting.orderBy === 'desc' ? 'asc' : 'desc'
  }
}
</script>

<style>
[v-cloak] {
  display: none;
}
</style>
