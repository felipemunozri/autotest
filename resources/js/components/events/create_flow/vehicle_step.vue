<template>
  <div class="columns is-multiline">
    <div class="column is-full">
      <h1 class="title has-text-left">Selección de Vehículo</h1>
      <ValidationObserver v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(searchVehicles)" class="mb-5">
          <ValidationProvider
            v-slot="{ errors }"
            name="patente"
            rules="required|min:6|max:6"
            class="column is-one-third p-0"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Patente"
              grouped
            >
              <b-input v-model="plateNumber" placeholder="Buscar patente de vehículo" expanded></b-input>
              <p class="control">
                <b-button
                  type="is-link"
                  native-type="submit"
                  icon-left="magnify"
                  :disabled="invalid"
                  :loading="isLoading"
                >
                  Buscar
                </b-button>
              </p>
            </b-field>
          </ValidationProvider>
        </form>
      </ValidationObserver>
      <found-vehicles-table
        ref="found-vehicles-table"
        :query="query"
        @vehicle-selected="selectVehicle"
      />
      <b-button
        type="is-link"
        :disabled="!selectedVehicle"
        @click="confirmVehicleSelection"
      >
        Continuar
      </b-button>
    </div>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";
import FoundVehiclesTable from "@/components/events/found_vehicles_table";

export default {
  components: {
    FoundVehiclesTable,
  },
  mixins: [Formatters()],
  props: {
    dni: {
      type: String,
      default: null,
      required: true 
    },
  },
  data() {
    return {
      plateNumber: null,
      selectedVehicle: null,
      isLoading: false
    };
  },
  watch: {
    plateNumber: {
      handler(val) {
        this.plateNumber = val.toUpperCase();
      },
    },
  },
  computed: {
    query() {
      const queryAux = {};
      if (this.plateNumber !== '') queryAux.plate_number = this.plateNumber;
      if (this.dni !== '') queryAux.dni = this.dni.replaceAll('.', '');
      return queryAux;
    },
  },
  methods: {
    searchVehicles() {
      this.$refs['found-vehicles-table'].loadVehicles();
    },
    selectVehicle(vehicle) {
      this.selectedVehicle = vehicle;
    },
    confirmVehicleSelection(){
      this.$emit('onConfirmVehicleSelection', this.selectedVehicle)
    }
  },
  mounted () {
    this.searchVehicles();
  },
};
</script>

<style scoped>
</style>