<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">NUEVO VEHÍCULO</h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <card title="Formulario de ingreso" :header-search-input="false">
        <div slot="content">
          <create-vehicle-form />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import CreateVehicleForm from '@/components/vehicles/create_vehicle_form'

export default {
  components: { Card, CreateVehicleForm },
  methods: {
    toIndex() {
      this.$inertia.visit('/');
    },
  },
}
</script>
