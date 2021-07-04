<template>
  <header ref="header" class="Header" :class="isTransparent">
    <div class="Header_row">
      <div class="Header_logo">
        <Logo />
      </div>
      <div v-if="isLogin" class="Header_authItem --md u-mr60">
        <AuthNav :author="author" @onClickLogout="logout" />
      </div>
      <div class="Header_menuIcon" @click="toggle()">
        <Icon name="menu" />
      </div>
      <div
        class="Header_overlay"
        :class="{ '--hidden': !isShow }"
        @click="toggle()"
      ></div>
      <nav class="Header_nav" :class="{ '--hidden': !isShow }">
        <h2 class="Header_navTitle">Menu</h2>
        <ul class="Header_menu">
          <HeaderLink
            v-for="(item, index) in headerMenu"
            :key="index"
            :item="item"
            tag="li"
            class="Header_menuItem"
            @click="moveTo"
          />
          <HeaderLink
            v-if="!isLogin"
            :item="{
              label: 'Log in',
              iconSrc: 'login',
              to: '/login',
            }"
            tag="li"
            class="Header_menuItem"
          />

          <li v-if="isLogin" class="Header_authItem --lg">
            <AuthNav :author="author" @onClickLogout="logout" />
          </li>
        </ul>
      </nav>
    </div>
  </header>
</template>
<script lang="ts">
import Vue from 'vue'
import Header from '~/mixins/Header'
import Logo from '~/components/atoms/Logo.vue'
export default Vue.extend({
  components: { Logo },
  mixins: [Header],
})
</script>
