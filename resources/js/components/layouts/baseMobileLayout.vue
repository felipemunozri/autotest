<template>
  <div>
    <nav-bar :show-toggle-menu-button="showAsideMenu"/>
    <aside-menu :menu="menu" v-if="showAsideMenu"/>
    <div  id="app-content" class="is-full" style="padding-top: 4rem; min-height: 100vh;">
      <slot></slot>
    </div>
    
    <!-- <footer-bar/> -->
  </div>
</template>

<script>

import NavBar from './navBar/navBar'
import AsideMenu from './asideMenu/asideMenu'
import { 
  BeneficiaryPerimeter,
  DevicePerimeter,
  EventPerimeter,
  UserPerimeter,
  VehiclePerimeter
} from '@/perimeters'
// import FooterBar from '@/components/FooterBar'
//import CredentialsService from '@/services/CredentialsService.js'
import { Formatters } from '@/mixins/formatters';

export default {
  mixins: [Formatters()],
  perimeters: [
    BeneficiaryPerimeter,
    DevicePerimeter,
    EventPerimeter,
    UserPerimeter,
    VehiclePerimeter
  ],
  components: {
    // FooterBar,
    AsideMenu,
    NavBar 
  },
  data() {
    return {
      showAsideMenu: false
    }
  },
  computed: {
    menu () {
      return [
      ]
    }
  },
  methods: {
  },
  created () {
    let user = USER;
    this.$store.commit('asideMobileStateToggle', false)
    this.$store.commit('user', {
      name: `${user.name} ${user.lastname !== null ? user.lastname : ''}`,
      email: `${user.email}`,
      avatar: user.profile_photo ? user.profile_photo : null /*'/data-sources/avatars/annie-spratt-121576-unsplash.jpg'*/,
      fullUser: user,
    });
    const currentTenant = this.$store.dispatch('users/getCurrentTenant');
    this.$store.commit('setCurrentTenant', this.renameKeysOfObject(currentTenant, {
      'id': 'id',
      'address': 'address',
      'code': 'code',
      'contact': 'contact',
      'country_id': 'countryId',
      'dni': 'dni',
      'logo_url': 'logoUrl',
      'name': 'name',
      'tenant_status_id': 'tenantStatusId',
      'created_at': 'createdAt',
      'updated_at': 'updatedAt',
      'deleted_at': 'deletedAt',
    }));
  }
}
</script>
