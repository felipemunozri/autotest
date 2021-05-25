<template>
  <b-table
    :data="vehicles"
    striped
    hoverable
    paginated
    :selected="selectedVehicle"
    :loading="isLoading"
    :per-page="8"
  >
    <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
    <b-table-column v-slot="props">
      <b-radio
        v-model="selectedVehicle"
        name="name"
        type="is-light"
        :native-value="props.row"
        :disabled="!props.row.valid"
      ></b-radio>
    </b-table-column>
    <b-table-column field="plate_number" label="Patente" v-slot="props">
      <span :class="{ 'unselectable': !props.row.valid }" >
        {{ props.row.plate_number }}
      </span>
      <b-tooltip v-if="!props.row.valid" type="is-dark" multilined size="is-large">
        <b-icon
          icon="alert-decagram"
          :type="!props.row.device ? 'is-danger' : 'is-warning'"
          size="is-small">
        </b-icon>
        <template v-if="!props.row.device" v-slot:content>
          Este vehículo <b>no cuenta con un dispositivo asociado</b>,
          por lo que no se le podrán realizar acciones.
        </template>
        <template v-else-if="!props.row.device.status || props.row.device.status.name !== 'activated'" v-slot:content>
          El dispositivo de este vehículo <b>no se encuentra activado</b>,
          por lo que no se podrán realizar acciones.
        </template>
        <template v-else-if="!props.row.device.sim_card" v-slot:content>
          El dispositivo de este vehículo <b>no cuenta con tarjeta SIM</b>,
          por lo que no se podrán realizar acciones.
        </template>
        <template v-else-if="!props.row.device.sim_card.status || props.row.device.sim_card.status.name !== 'enabled'" v-slot:content>
          La tarjeta SIM del dispositivo de este vehículo <b>no se encuentra disponible</b>,
          por lo que no se podrán realizar acciones.
        </template>
      </b-tooltip>
    </b-table-column>
    <b-table-column field="model" label="Modelo" v-slot="props">
      <span :class="{ 'unselectable': !props.row.valid }">{{ props.row.model }}</span>
    </b-table-column>
    <b-table-column field="brand" label="Marca" v-slot="props">
      <span :class="{ 'unselectable': !props.row.valid }">{{
        props.row.car_brand
          ? props.row.car_brand.name
          : '-'
      }}</span>
    </b-table-column>
    <b-table-column field="owner_name" label="Dueño" v-slot="props">
      <span :class="{ 'unselectable': !props.row.valid }">{{ props.row.owner_name }}</span>
    </b-table-column>
    <b-table-column field="phone" label="Teléfono" v-slot="props">
      <span :class="{ 'unselectable': !props.row.valid }">{{
        props.row.beneficiaries.length
          ? props.row.beneficiaries[0].phone_number
          : '-'
      }}</span>
    </b-table-column>
  </b-table>
</template>

<script>
import notification_item from '../layouts/navBar/notification_item.vue'
export default {
  components: { notification_item },
  props: {
    query: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      vehicles: [],
      isLoading: false,
      selectedVehicle: null,
      deviceUnavailableText: 'Este vehículo no cuenta con dispositivo asociado,\n por lo que no se le podrán realizar acciones.',
    }
  },
  watch: {
    vehicleFilter: {
      handler(value) {
        this.loadVehicles()
      },
    },
    selectedVehicle: {
      handler(value) {
        this.$emit('vehicle-selected', value);
      }
    }
  },
  methods: {
    async loadVehicles() {
      this.isLoading = true;
      this.selectedVehicle = null;
      const vehicleData = await this.$store.dispatch('vehicles/getVehicles', {
        query: this.query
      });
      this.vehicles = vehicleData.data.map(vehicle => {
        vehicle.valid =
          vehicle.device && vehicle.device.status && vehicle.device.status.name === 'activated'
          && vehicle.device.sim_card && vehicle.device.sim_card.status && vehicle.device.sim_card.status.name === 'enabled'
          ? true
          : false;
        return vehicle;
      });
      this.isLoading = false;
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }

  .unselectable {
    color: darkgray;
  }
</style>
