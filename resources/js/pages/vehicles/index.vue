<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">VEHÍCULOS</h1>
        </div>
        <div class="column is-2">
          <b-button type="is-link" @click="toCreate" icon-left="plus">
            AÑADIR VEHÍCULO
          </b-button>
        </div>
      </div>
      <card title="Listado de vehículos" :header-search-input="false">
        <div slot="header-right" class="card-header-icon">
          <b-input v-model="searchText" class=""></b-input>
          <b-button icon-left=magnify type="is-primary is-light" @click="search"></b-button>
        </div>
        <div slot="content">
          <vehicles-table :vehicleFilter="tableFilter" />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import VehiclesTable from '@/components/vehicles/vehicles_table'

export default {
  components: { Card, VehiclesTable },
  props: ['data'],
  data() {
    return {
      searchText: '',
      tableFilter: '',
    }
  },
  mounted() {
  },
  methods: {
    search() {
     this.tableFilter = this.searchText
    },
    toCreate() {
      this.$inertia.visit(route('vehicles.create'))
    },
  }
}
</script>