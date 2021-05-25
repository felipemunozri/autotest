<template>
  <aside
      v-show="isAsideVisible"
      class="aside is-placed-left is-expanded">
    <aside-tools :is-main-menu="true">
      <div slot="label" class="aside-header-container">
        <!-- <div class="aside-logo">
          <img src="../../../../assets/logo_white.png" alt="logo"/>
        </div> -->
        <div class="aside-logo">
          <img src="../../../../assets/icon-white.png" alt="logo" />
        </div> 
        <div class="aside-title">
          AUTOSEGURO365
        </div>
      </div>
    </aside-tools>
    <div class="menu is-menu-main">
      <template v-for="(menuGroup, index) in menu" >
        <div :key="`item-${index}`" v-if="menuGroup.items.filter(x => x.visible === true).length > 0">
          <p class="menu-label">{{ menuGroup.title }}</p>
          <aside-menu-list
            :menu="menuGroup.items"/>
        </div>
      </template>
    </div>
    <div class="menu-footer">Versión {{ appVersion }} <br> &copy; AUTOSEGURO365 - {{ year }}</div>
  </aside>
</template>

<script>
import { mapState } from 'vuex';
import AsideTools from './asideTools';
import AsideMenuList from './asideMenuList';

export default {
  name: 'AsideMenu',
  components: { 
    AsideTools,
    AsideMenuList
  },
  props: {
    menu: {
      type: Array,
      default: () => [],
    },
  },
  data () {
    return {
      appVersion: process.env.MIX_APP_VERSION,
    }
  },
  computed: {
    ...mapState([
      'isAsideVisible'
    ]),
    year () {
      return this.$moment().year()
    }
  },
  methods: {
  }
}
</script>
