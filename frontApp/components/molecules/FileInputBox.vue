<template>
  <div class="FileInputBox u-mt30">
    <ul class="FileInputBox_tabList">
      <li
        v-for="(tab, index) in tabList"
        :key="index"
        class="FileInputBox_tab"
        :class="hidden(index)"
        @click="changeFileItem(index)"
      >
        {{ tab }}
      </li>
    </ul>
    <div class="FileInputBox_container">
      <div v-if="!currentItem.preview" class="FileInputBox_bg">
        <Icon name="add_a_photo" class="FileInputBox_noImage" />
      </div>
      <div
        v-for="(item, index) in itemList.list"
        :key="index"
        class="FileInputBox_body"
        :class="hidden(index)"
      >
        <input
          :id="item.name"
          :ref="`fileBtn_${item.name}`"
          type="file"
          class="FileInputBox_file"
          :accept="item.accept"
          @change="onFileChange"
        />
      </div>
      <div class="FileInputBox_preview" :class="noPreview">
        <div v-if="currentItem.preview" class="FileInputBox_previewMask"></div>
        <Icon
          v-if="!!currentItem.preview"
          :tab-option="true"
          name="delete"
          class="FileInputBox_clear"
          @click="clear"
        />
        <output class="FileInputBox_previewImage">
          <img
            class="FileInputBox_previewSrc"
            :src="currentItem.preview"
            alt=""
          />
        </output>
        <button
          type="button"
          class="FileInputBox_seal"
          @click="onFileClick"
        ></button>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import Vue, { PropType } from 'vue'
import Icon from '~/components/atoms/Icon.vue'
import { FileFieldList, FileField } from '~/types/FormObj'

export default Vue.extend({
  components: { Icon },
  props: {
    itemList: {
      type: Object as PropType<FileFieldList>,
      default: (): FileFieldList => ({
        name: 'fileList',
        type: 'fileList',
        value: '',
        rules: [() => true],
        errorMessages: [],
        list: [
          {
            name: 'file_name',
            type: 'file',
            label: '画像',
            value: '',
            preview: '',
            accept: 'image/*',
            rules: [() => true],
            errorMessages: [],
          },
        ],
      }),
    },
    flag: {
      type: Boolean as PropType<boolean>,
      default: false,
    },
    accept: {
      type: String as PropType<string>,
      default: 'image/*',
    },
  },
  data: () => ({
    currentKey: 0,
  }),
  computed: {
    tabList(): string[] {
      return this.itemList.list.map((item: FileField): string => item.label)
    },
    currentItem(): FileField {
      return this.itemList.list[this.currentKey]
    },
    noPreview(): object {
      return {
        '--noPreview': !this.itemList.list[this.currentKey].preview,
      }
    },
  },
  watch: {
    flag() {
      this.currentKey = 0
    },
  },
  methods: {
    onFileClick() {
      const fileBtn: any = this.$refs[
        `fileBtn_${this.itemList.list[this.currentKey].name}`
      ]
      fileBtn[0].click()
    },
    onFileChange(event: any): boolean | void {
      const targetField: FileField = this.itemList.list[this.currentKey]
      if (event.target.files.length === 0) {
        return false
      }
      if (!event.target.files[0].type.match('image.*')) {
        return false
      }
      const reader = new FileReader()
      reader.onload = (e: any) => {
        targetField.preview = e.target.result
      }
      reader.readAsDataURL(event.target.files[0])
      targetField.value = event.target.files[0]
      this.$emit('change')
    },
    reset(): void {
      this.itemList.list[this.currentKey].preview = null
      this.itemList.list[this.currentKey].value = null
    },
    clear(): void {
      this.reset()
      this.$emit('change')
    },
    changeFileItem(index: number): void {
      this.currentKey = index
    },
    hidden(index: number): object {
      return {
        '--hidden': this.currentKey !== index,
      }
    },
  },
})
</script>
