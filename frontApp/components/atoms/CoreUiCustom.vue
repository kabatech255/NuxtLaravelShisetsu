<template>
  <div class="CoreUiCustom">
    <slot name="coreui-custom-content"></slot>
  </div>
</template>
<script>
import { Component, Vue } from 'nuxt-property-decorator'

@Component
export default class CoreUiCustom extends Vue {}
</script>
