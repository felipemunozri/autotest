<template>
  <li :class="{'is-active':isDropdownActive}" v-if="item.visible" @mouseover="hover = true" @mouseleave="hover = false">
    <component :is="componentIs" :href="route(this.item.to)" @click.prevent="menuClick"  :class="{'has-icon':!!item.icon, 'has-dropdown-icon': hasDropdown, 'is-active': isActive}">
      <b-icon v-if="item.icon" :icon="hover ? item.icon : item.icon" :class="[{ 'has-update-mark' : item.updateMark }, 'icon-size']"/>
      <span v-if="item.label" :class="{'menu-item-label':!!item.icon}">{{ item.label }}</span>
      <div v-if="hasDropdown" class="dropdown-icon">
        <b-icon :icon="dropdownIcon" />
      </div>
    </component>
    <aside-menu-list
        v-if="hasDropdown"
        :menu="item.menu"
        :isSubmenuList="true"/>
  </li>
</template>

<script>
import asideMenu from './asideTools'

export default {
  name: 'AsideMenuItem',
  components: {
    asideMenu
  },
  data () {
    let currentRouteMame = route().current()
    return {
      isDropdownActive: false,
      currentRouteMame,
      hover: false
    }
  },
  props: {
    item: {
      type: Object,
      default: null
    }
  },
  methods: {
    menuClick () {

      if (this.hasDropdown) {
        this.isDropdownActive = (!this.isDropdownActive)
      } else {
        this.$inertia.visit(route(this.item.to))
      }
    }
  },
  computed: {
    isActive() {
      if (this.itemStrictRoute) {
        return this.itemTo === this.currentRouteMame && !this.hasDropdown;
      } else  {
        let [itempParentPath] = this.itemTo ? this.itemTo.split('.') : []
        let [currentParentPath] = this.currentRouteMame.split('.')
        return itempParentPath === currentParentPath && !this.hasDropdown;
      }
    },
    componentIs () {
      return this.item.to ? 'inertia-link' : 'a'
    },
    hasDropdown () {
      return !!this.item.menu
    },
    dropdownIcon () {
      return (this.isDropdownActive) ? 'chevron-up' : 'chevron-down'
    },
    itemTo () {
      return this.item.to ? this.item.to : null
    },
    itemStrictRoute () {
      return this.item.strictRoute ? this.item.strictRoute : false
    },
    itemHref () {
      return this.item.href ? this.item.href : null
    }
  },
  beforeMount() {
    this.$once(
      'hook:destroyed',
      this.$inertia.on('navigate', (event) => {
        this.currentRouteMame = route().current();
      })
    )
  }
 
}
</script>
