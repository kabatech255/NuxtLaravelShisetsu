<template>
  <div v-if="!isLogin" class="TestLoginButton" :class="[optionClass]">
    <CustomButton
      label="テストユーザーとして試す"
      type="button"
      :class="[defaultClass, optionClass]"
      @click="testLogin"
    />
  </div>
</template>
<script lang="ts">
import { Vue, Component, Prop } from 'nuxt-property-decorator'
import CustomButton from '~/components/atoms/CustomButton.vue'

@Component({
  components: { CustomButton },
})
export default class TestLoginButton extends Vue {
  @Prop({ type: Boolean, required: true, default: false }) isLogin!: boolean

  @Prop({ type: Boolean, default: false }) lg?: boolean
  @Prop({ type: Boolean, default: false }) mdCenter?: boolean
  @Prop({ type: Boolean, default: false }) colorPrimary?: boolean
  @Prop({ type: Boolean, default: false }) smB12?: boolean

  async testLogin(): Promise<void> {
    await this.$store.dispatch('auth/testLogin')
    this.$emit('click')
    if (Object.prototype.hasOwnProperty.call(this.$route.params, 'before')) {
      this.$router.push(this.$route.params.before)
    } else {
      this.$router.push('/mypage')
    }
  }

  get defaultClass(): string[] {
    return ['--rounded']
  }

  get optionClass(): object {
    return {
      '--lg': this.lg,
      '--mdCenter': this.mdCenter,
      '--sm-b12': this.smB12,
      '--transparent': !this.colorPrimary,
      '--outlined': this.colorPrimary,
    }
  }
}
</script>
