<template>
  <div class="EmptyCard">
    <slot name="card-content"></slot>
  </div>
</template>
<script lang="ts">
import { Vue, Component } from 'nuxt-property-decorator'

@Component
export default class EmptyCard extends Vue {}
</script>
