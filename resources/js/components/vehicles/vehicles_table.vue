<template>
  <b-table
    :data="vehicles"
    striped
    hoverable
    paginated
    :loading="isLoading"
    :per-page="10"
  >
    <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
    <b-table-column field="plate_number" label="Patente" v-slot="props">
      {{ props.row.plate_number }}
    </b-table-column>
    <b-table-column field="model" label="Modelo" v-slot="props">
      {{ props.row.model }}
    </b-table-column>
    <b-table-column field="brand" label="Marca" v-slot="props">
      {{
        props.row.car_brand
          ? props.row.car_brand.name
          : '-'
      }}
    </b-table-column>
    <b-table-column field="owner_name" label="Dueño" v-slot="props">
      {{ props.row.owner_name }}
    </b-table-column>
    <b-table-column field="phone" label="Teléfono" v-slot="props">
      {{ props.row.phone }}
    </b-table-column>
    <b-table-column field="owner_adquisition_date" label="Fecha" v-slot="props">
      {{ moment(props.row.created_at).format('D [de] MMMM, YYYY') }}
    </b-table-column>
    <b-table-column v-slot="props">
      <b-button type="is-link" size="is-small" @click="toDetails(props.row.id)">
        Ver Detalles
      </b-button>
    </b-table-column>
  </b-table>
</template>

<script>
export default {
  props: {
    vehicleFilter: {
      type: String,
      default() {
        return ''
      },
    },
  },
  data() {
    return {
      vehicles: [],
      isLoading: false,
    }
  },
  watch: {
    vehicleFilter: {
      handler(val) {
        this.loadVehicles()
      },
    },
  },
  mounted() {
    this.loadVehicles();
  },
  methods: {
    async loadVehicles() {
      this.isLoading = true;
      const vehicleData = await this.$store.dispatch(
        'vehicles/getVehicles',
        this.vehicleFilter !== ''
          ? {
              query: {
                plate_number: this.vehicleFilter.toUpperCase(),
              },
            }
          : { query: {} }
      );
      this.vehicles = vehicleData.data;
      this.isLoading = false;
    },
    filterVehicles(vehicle) {
      return (
        vehicle.plate_number.includes(this.vehicleFilter) ||
        vehicle.owner_name.includes(this.vehicleFilter)
      );
    },
    toDetails(id) {
      this.$inertia.visit(route('vehicles.show', id));
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }
</style>
