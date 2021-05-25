<template>
  <div>
    <nav v-show="isNavBarVisible" id="navbar-main" class="navbar is-fixed-top">
      <div class="navbar-brand">
        <a class="navbar-item" @click.prevent="menuToggleMobile" v-if="showToggleMenuButton">
          <b-icon :icon="menuToggleMobileIcon"/>
        </a>
        <a class="navbar-item is-size-5 has-text-weight-bold" v-else>
          AutoSeguro365
        </a>
      </div>
      <div class="navbar-brand is-right">
        <a class="navbar-item navbar-item-menu-toggle is-hidden-desktop" @click.prevent="menuNavBarToggle">
          <b-icon :icon="menuNavBarToggleIcon"/>
        </a>
      </div>
      <div class="navbar-menu fadeIn animated faster" :class="{'is-active':isMenuNavBarActive}">
        <div class="navbar-end">
          <!-- <nav-bar-menu class="has-divider">
            <b-icon icon="menu" custom-size="default"/>
            <span>Sample Menu</span>
            <div slot="dropdown" class="navbar-dropdown">
              <router-link to="/profile" class="navbar-item" exact-active-class="is-active">
                <b-icon icon="account" custom-size="default"/>
                <span>My Profile</span>
              </router-link>
              <a class="navbar-item">
                <b-icon icon="settings" custom-size="default"/>
                <span>Settings</span>
              </a>
              <a class="navbar-item">
                <b-icon icon="email" custom-size="default"/>
                <span>Messages</span>
              </a>
              <hr class="navbar-divider">
              <a class="navbar-item">
                <b-icon icon="logout" custom-size="default"/>
                <span>Log Out</span>
              </a>
            </div>
          </nav-bar-menu> -->
          <notifications />
          <nav-bar-menu class="has-divider has-user-avatar">
            <user-avatar class="is-inline-block"/>
            <div class="is-inline-block">
              <div class="is-block has-text-weight-light">{{ userName ? userName : 'Nombre Apellido' }}</div>
              <div class="is-block has-text-weight-bold is-capitalized">{{ userProfile ? userProfile : '' }}</div>
            </div>
            <div slot="dropdown" class="navbar-dropdown">
              <a v-if="$users.isAllowed('show')" class="navbar-item" title="Perfil" @click="toProfile">
                <b-icon icon="account" custom-size="default"></b-icon>
                <span>Perfil</span>
              </a> 
              <hr class="navbar-divider">
              <a class="navbar-item" title="Log out" @click="logout">
                <b-icon icon="logout" custom-size="default"/>
                <span>Cerrar Sesión</span>
              </a>
            </div>
          </nav-bar-menu>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import NavBarMenu from '@/components/layouts/navBar/navBarMenu';
import UserAvatar from './userAvatar';
import Notifications from './notifications';
import { UserPerimeter } from '@/perimeters';

export default {
  perimeters: [ UserPerimeter ],
  props: {
    showToggleMenuButton: {
      type: Boolean,
      default: true
    }
  },
  components: {
    UserAvatar,
    NavBarMenu,
    Notifications,
  },
  data () {
    return {
      isMenuNavBarActive: false
    }
  },
  computed: {
    menuNavBarToggleIcon () {
      return (this.isMenuNavBarActive) ? 'close' : 'dots-vertical'
    },
    menuToggleMobileIcon () {
      return this.isAsideMobileExpanded ? 'backburger' : 'forwardburger'
    },
    ...mapState([
      'isNavBarVisible',
      'isAsideMobileExpanded',
      'userName',
      'userProfile'
    ])
  },
  methods: {
    menuToggleMobile () {
      this.$store.commit('asideMobileStateToggle');
    },
    menuNavBarToggle () {
      this.isMenuNavBarActive = (!this.isMenuNavBarActive);
    },
    toProfile() {
      this.$inertia.visit(route('profile'));
    },
    async logout () {
      this.$inertia.post(route('logout'));
    },
  },
}
</script>
