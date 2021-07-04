<template>
  <div class="p-error u-pa20">
    <p>お探しのページは見つかりませんでした。</p>
    <CustomButton label="戻る" :class="['--text']" onclick="history.back()" />
  </div>
</template>
<script lang="ts">
import { Component, Vue, Watch } from 'nuxt-property-decorator'
import PageMixin from '~/mixins/PageMixin'
import CustomButton from '~/components/atoms/CustomButton.vue'

@Component({
  mixins: [PageMixin],
  components: { CustomButton },
})
export default class NotFound extends Vue {}
</script>
