<template>
  <div class="p-about">
    <div class="Container u-pt30">
      <ul class="">
        <li class="">
          <CustomButton label="ログイン" />
        </li>
        <li class="">
          <CustomButton label="ログイン" :class-options="['--pink']" />
        </li>
        <li class="">
          <CustomButton label="ログイン" :class-options="['--outlined']" />
        </li>
        <li class="">
          <CustomButton
            label="ログイン"
            :class-options="['--outlined', '--pink']"
          />
        </li>
      </ul>
      <ul class="">
        <li class="">
          <CustomButton label="ログイン" :class-options="['--rounded']" />
        </li>
        <li class="">
          <CustomButton
            label="ログイン"
            :class-options="['--rounded', '--pink']"
          />
        </li>
        <li class="">
          <CustomButton
            label="ログイン"
            :class-options="['--rounded', '--outlined']"
          />
        </li>
        <li class="">
          <CustomButton
            label="ログイン"
            :class-options="['--rounded', '--outlined', '--pink']"
          />
        </li>
      </ul>
      <h2 class="u-pa20">パネル</h2>
      <ul class="">
        <li class="">
          <Panel />
        </li>
        <li class="">
          <Panel />
        </li>
        <li class="">
          <Panel />
        </li>
      </ul>
      <ul>
        <li>
          <div class="ListCard">
            <h3 class="ListCard_head">
              <Icon name="" />
              <span class="ListCard_title">メインタグ</span>
            </h3>
            <div class="ListCard_body">
              <ul class="ListCard_menu">
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
                <li class="ListCard_menuItem">行動宣言</li>
              </ul>
            </div>
          </div>
        </li>
        <li>あ</li>
      </ul>
    </div>
  </div>
</template>
<script lang="ts">
import Vue from 'vue'
import CustomButton from '~/components/atoms/CustomButton.vue'
export default Vue.extend({
  components: { CustomButton },
})
</script>
<style lang="scss">
.p-about {
  ul {
    padding: 10px 20px;
    display: flex;
    align-items: center;
  }
  li {
    padding: 5px;
  }
}
</style>
