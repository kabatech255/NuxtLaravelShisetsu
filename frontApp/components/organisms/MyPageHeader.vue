<template>
  <header ref="header" class="MyPageHeader" :class="isTransparent">
    <div class="MyPageHeader_row">
      <div v-if="isLogin" class="MyPageHeader_authItem u-mr60">
        <div class="Sidebar">
          <RouterLink to="/" tag="a" class="Sidebar_top --mdonly">
            <Icon name="home" class="Sidebar_topIcon" />
            <span class="Sidebar_topValue">TOP</span>
          </RouterLink>
        </div>
        <AuthNav :author="author" @onClickLogout="logout" />
      </div>
      <div class="MyPageHeader_menuIcon" @click="toggle()">
        <Icon name="menu" />
      </div>
      <div
        class="MyPageHeader_overlay"
        :class="{ '--hidden': !isShow }"
        @click="toggle()"
      ></div>
      <nav class="MyPageHeader_nav" :class="{ '--hidden': !isShow }">
        <h2 class="MyPageHeader_navTitle">Menu</h2>
        <ul class="MyPageHeader_menu">
          <HeaderLink
            v-for="(item, index) in headerMenu"
            :key="index"
            :item="item"
            tag="li"
            class="MyPageHeader_menuItem --mypage"
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
            class="MyPageHeader_menuItem"
          />
        </ul>
      </nav>
    </div>
  </header>
</template>
<script lang="ts">
import Vue from 'vue'
import Header from '~/mixins/Header'
export default Vue.extend({
  mixins: [Header],
})
</script>
