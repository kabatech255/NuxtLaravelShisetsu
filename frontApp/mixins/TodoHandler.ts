import { Component, Prop, Vue } from 'nuxt-property-decorator'
import { TextField } from '~/types/FormObj'
import { Todo, TodoApi } from '~/types/Dashboard'
import { Author } from '~/types/Author'

@Component
export default class TodoHandler extends Vue {
  @Prop({ type: Object, required: true }) author!: Author

  todoForm: TextField = {
    name: 'body',
    label: '',
    type: 'text',
    value: '',
    placeholder: '30文字以内',
    prependIcon: '',
    rules: [(val: string) => !!val || '入力必須です'],
    errorMessages: [],
    class: ['--outlined'],
    options: ['required', 'counter', 'autofocus'],
  }

  todo: TodoApi = {
    list: [
      {
        id: 0,
        employee_id: 0,
        body: '',
        is_done: 0,
        validity: false,
      },
    ],
    keys: [],
  }

  async storeTodo(): Promise<void> {
    const submitData = new FormData()
    submitData.append('body', this.todoForm.value)
    await this.$axios
      .post(`/examinator/${this.author.employee_id}/todo/store`, submitData)
      .then((response: any): void => {
        this.todoForm.value = ''
        const newT = response.data
        newT.iconName = newT.icon_name
        newT.isShow = true
        newT.is_done = 0
        newT.style = 'text-align: left; flex-grow: 1;'
        newT.appendIcon = 'remove_circle_outline'
        this.todo.list.push(newT)
        this.customSort()
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async toggleDone(todo: Todo): Promise<void> {
    this.customSort()

    if (todo.is_done) {
      await this.done(todo)
    } else {
      await this.didnt(todo)
    }
  }

  async done(todo: Todo) {
    await this.$axios
      .put(`/examinator/${this.author.employee_id}/todo/${todo.id}/done`)
      .then(() => {})
      .catch((err: any) => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async didnt(todo: Todo) {
    await this.$axios
      .put(`/examinator/${this.author.employee_id}/todo/${todo.id}/didnt`)
      .then(() => {})
      .catch((err: any) => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  async deleteTodo(todoId: number): Promise<void> {
    await this.$axios
      .delete(`/examinator/${this.author.employee_id}/todo/${todoId}/delete`)
      .then((): void => {
        this.todo.list.forEach((t: Todo, index: number): void => {
          if (t.id === todoId) {
            this.todo.list.splice(index, 1)
          }
        })
      })
      .catch((err: any): void => {
        this.$store.dispatch('status/errorHandler', err)
      })
  }

  customSort(): void {
    this.todo.list
      .sort((prev: Todo, next: Todo) => prev.id - next.id)
      .sort((prev: Todo, next: Todo) => prev.is_done - next.is_done)
  }
}
