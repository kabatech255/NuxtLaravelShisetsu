<template>
  <div class="u-pa20">
    <FormLayout
      :form-data="assetFormData"
      title="画像アップロード"
      button-label="アップロード"
      @click="upload"
    />
    <div class="u-alignCenter u-pt30">
      <CustomButton label="追加" @click="addList" />
    </div>
  </div>
</template>
<script lang="ts">
import Vue from 'vue'
import FormLayout from '~/components/organisms/FormLayout.vue'
import CustomButton from '~/components/atoms/CustomButton.vue'
export default Vue.extend({
  components: { FormLayout, CustomButton },
  data: () => ({
    assetFormData: {
      uploadData: {
        name: 'detailFileList',
        type: 'fileList',
        value: '',
        rules: [() => true],
        errorMessages: [],
        list: [
          {
            name: 'file1',
            type: 'file',
            label: 'asset1',
            value: '',
            preview: '',
            rules: [(val: any) => !!val || '画像1を挿入してください'],
            errorMessages: [],
            accept: 'image/*',
          },
          {
            name: 'file2',
            type: 'file',
            label: 'asset2',
            value: '',
            preview: '',
            rules: [() => true],
            errorMessages: [],
            accept: 'image/*',
          },
          {
            name: 'file3',
            type: 'file',
            label: 'asset3',
            value: '',
            preview: '',
            rules: [() => true],
            errorMessages: [],
            accept: 'image/*',
          },
        ],
      },
    } as any,
    test: null as any,
  }),
  methods: {
    async upload(): Promise<void> {
      const formData = new FormData()
      this.assetFormData.uploadData.list.forEach((item: any): void => {
        formData.append(item.name, item.value)
      })
      this.test = await this.$axios
        .post('/upload', formData)
        .then((response: any): any => response)
        .catch((error: any): any => error)
    },
    addList(): void {
      const obj: any = {
        name: 'file3',
        type: 'file',
        label: 'asset3',
        value: '',
        preview: '',
        rules: [() => true],
        errorMessages: [],
      }
      const arr: any = this.assetFormData.uploadData.list
      arr.add(obj)
    },
  },
})
</script>
