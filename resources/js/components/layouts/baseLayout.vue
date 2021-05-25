<template>
  <div>
    <nav-bar/>
    <aside-menu :menu="menu"/>
    <div id="app-content" class="is-full-mobile" style="padding-top: 4rem; min-height: 100vh;">
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
  VehiclePerimeter,
  TenantPerimeter
} from '@/perimeters'
import { Formatters } from "@/mixins/formatters";
// import FooterBar from '@/components/FooterBar'
//import CredentialsService from '@/services/CredentialsService.js'

export default {
  mixins: [Formatters()],
  perimeters: [
    BeneficiaryPerimeter,
    DevicePerimeter,
    EventPerimeter,
    UserPerimeter,
    VehiclePerimeter,
    TenantPerimeter
  ],
  components: {
    // FooterBar,
    AsideMenu,
    NavBar 
  },
  computed: {
    menu () {
      return [
        {
          title: 'Menú',
          items: [
            {
              to: 'dashboard.index',
              icon: 'view-dashboard',
              iconOutline: 'view-dashboard-outline',
              label: 'Inicio',
              visible: true
            },
            {
              to: 'analytics.index',
              icon: 'chart-box',
              iconOutline: 'chart-box-outline',
              label: 'Estadisticas',
              visible: true
            },
            {
              to: 'events.create',
              icon: 'magnify',
              label: 'Buscar Vehículo',
              strictRoute: true,
              visible: this.$events.isAllowed('create')
            },
            {
              to: 'vehicles.create',
              icon: 'car',
              label: 'Nuevo Vehículo',
              strictRoute: true,
              visible: this.$vehicles.isAllowed('create')
            },
            {
              to: 'events.index',
              icon: 'calendar',
              label: 'Historial de Eventos',
              strictRoute: true,
              visible: this.$events.isAllowed('list'),
            },
            {
              to: 'vehicles.summary',
              icon: 'car-info',
              label: 'Información de Vehículo',
              strictRoute: true,
              visible: this.$vehicles.isAllowed('show')
            },
            {
              to: 'beneficiaries.edit',
              icon: 'account-edit',
              label: 'Editar Beneficiario',
              visible: this.$beneficiaries.isAllowed('edit')
            },/* 
            {
              to: 'users.index',
              icon: 'account',
              label: 'Propietarios'
            }, 
            {
              to: 'tasks.index',
              icon: 'calendar-check',
              label: 'Tareas',
            },*/
          ]
        },
        {
          title: 'Administración',
          items: [
            {
              to: 'users.index',
              icon: 'account',
              label: 'Usuarios',
              visible: this.$users.isAllowed('list')
            },
            {
              to: 'devices.index',
              icon: 'crosshairs-gps',
              label: 'Dispositivos',
              visible: this.$users.isAllowed('list')
            },
            {
              to: 'simcard.index',
              icon: 'sim',
              label: 'Tarjetas Sim',
              visible: this.$users.isAllowed('list')
            },
            {
              to: 'tenant.index',
              icon: 'domain',
              label: 'Organización',
              visible: this.$tenants.isAllowed('edit')
            }
          ]
        }
      ]
    }
  },
  methods: {
  },
  async created () {
    const url = APP_URL;
    this.$store.commit('setAppUrl', url);
    let user = USER;
    this.$store.commit('user', {
      name: `${user.name} ${user.lastname ? user.lastname : ''}`,
      role: `${user.roles && user.roles[0] ? user.roles[0].description : ''}`,
      email: `${user.email}`,
      avatar: user.profile_photo ? user.profile_photo : null /*'/data-sources/avatars/annie-spratt-121576-unsplash.jpg'*/,
      fullUser: user,
    });
    const currentTenant = TENANT
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
      'location': 'location'
    }));
  }
}
</script>
