<template>
  <div class="TodoPanel">
    <BasePanel
      :title="title"
      event-content="TODOを登録する"
      @onAppend="toggleEdit"
    >
      <div slot="content" class="BasePanel_row">
        <VerticalLayout
          v-if="!!todo.list.length"
          :list="todo.list"
          :keys="todo.keys"
          :has-icon="true"
          :class-options="['--light']"
          @itemClick="toggleDone"
          @onAppend="prepareDelete"
        />
        <p v-else class="Text --emptyMsg u-alignCenter u-fontBold">
          TODOの登録はまだありません
        </p>
      </div>
      <div
        slot="head-attach"
        class="BasePanel_headAttach"
        :class="hiddenEditor"
      >
        <div class="BasePanel_attachBg"></div>
        <TextBox :item="todoForm" :class="['--only']" />
        <p class="BasePanel_headRow">
          <CustomButton label="Add" :class="['--sm']" @click="storeTodo" />
        </p>
      </div>
    </BasePanel>
    <Modal :is-show="isShow" @onCancel="clear">
      <CancelAlertBox
        slot="modalContent"
        :button-options="['--error']"
        cancel-button-label="戻る"
        @onCancel="clear"
        @onExec="deleteTodo"
      >
        <p slot="CancelAlertBox_message" class="CancelAlertBox_message">
          一度削除すると元に戻せません。<br />削除してよろしいですか？
        </p>
      </CancelAlertBox>
    </Modal>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from 'nuxt-property-decorator'
import BasePanel from '~/components/molecules/BasePanel.vue'
import VerticalLayout from '~/components/molecules/VerticalLayout.vue'
import CustomButton from '~/components/atoms/CustomButton.vue'
import TextBox from '~/components/molecules/TextBox.vue'
import Modal from '~/components/organisms/Modal.vue'
import CancelAlertBox from '~/components/molecules/CancelAlertBox.vue'
import { BasePanelTitle, Todo } from '~/types/Dashboard'

@Component({
  components: {
    BasePanel,
    VerticalLayout,
    CustomButton,
    TextBox,
    Modal,
    CancelAlertBox,
  },
})
export default class TodoPanel extends Vue {
  @Prop({ type: Object, default: () => ({}) })
  todo?: Todo

  @Prop({ type: Object, default: () => ({}) })
  todoForm?: object

  currentTodoId: number | null = null
  isShow: boolean = false

  title: BasePanelTitle = {
    id: 'todos',
    value: 'Todo',
    prependIcon: '',
    appendIcon: 'add_circle_outline',
    color: 'purple',
    isEdit: false,
  }

  toggleEdit(): void {
    this.title.isEdit = !this.title.isEdit
    this.title.appendIcon = this.title.isEdit
      ? 'remove_circle_outline'
      : 'add_circle_outline'
  }

  toggleModal(): void {
    this.isShow = !this.isShow
  }

  prepareDelete(todo: Todo): void {
    this.toggleModal()
    this.currentTodoId = todo.id
  }

  clear(): void {
    this.toggleModal()
    this.currentTodoId = null
  }

  async toggleDone(todo: Todo): void {
    this.todo.list.forEach((t: Todo): void => {
      if (todo.id === t.id) {
        t.is_done = Math.abs(t.is_done - 1)
        t.validity = !t.validity
        t.iconName = this.iconName(t.validity)
      }
    })
    await this.$emit('onTodoClick', todo)
  }

  async storeTodo(): void {
    await this.$emit('onAdd')
  }

  async deleteTodo(): void {
    this.toggleModal()
    await this.$emit('onDelete', this.currentTodoId)
  }

  iconName(validity: boolean): string {
    return validity ? 'check_box_outline_blank' : 'check_box'
  }

  get hiddenEditor(): object {
    return {
      '--hidden': !this.title.isEdit,
    }
  }
}
</script>
