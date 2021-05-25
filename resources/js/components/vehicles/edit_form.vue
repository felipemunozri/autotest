<template>
  <ValidationObserver v-slot="{ handleSubmit }">
    <form @submit.prevent="handleSubmit(save)">
      <ValidationProvider
        v-slot="{ errors }"
        name="patente"
        rules="required"
        class="column is-6 is-full-mobile"
      >
        <b-field
          :type="{ 'is-danger': errors.length }"
          :message="[errors.length ? errors[0] : '']"
          label="Patente"
        >
          <b-input v-model="searchPlateNumber" placeholder="Patente" expanded>
          </b-input>
          <p class="control">
            <b-button type="is-primary" icon-left="magnify" label="Buscar" @click="searchVehicle"/>
          </p>
        </b-field>
      </ValidationProvider>
      <b-field grouped group-multiline>
        <ValidationProvider
          v-slot="{ errors }"
          name="modelo"
          rules="required"
          class="column is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Modelo"
          >
            <b-input v-model="vehicle.model" placeholder="Modelo" :disabled="!canEdit"> </b-input>
          </b-field>
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          name="marca"
          rules="required"
          class="column is-full-mobile"
          :disabled="!carBrands.length"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Marca"
          >
            <b-select
              v-model="vehicle.brandId"
              placeholder="Seleccione una Marca"
              :disabled="!canEdit"
              expanded
            >
              <option
                v-for="brand in carBrands"
                :value="brand.id"
                :key="brand.id"
              >
                {{ brand.name }}
              </option>
            </b-select>
          </b-field>
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          name="año"
          rules="required|numeric|min:4|max:4"
          class="column is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Año"
          >
            <b-input v-model="vehicle.year" placeholder="Año" :disabled="!canEdit">
            </b-input>
          </b-field>
        </ValidationProvider>
      </b-field>
      <b-field grouped group-multiline>
        <ValidationProvider
          v-slot="{ errors }"
          name="color"
          rules="required"
          class="column is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Color"
          >
            <b-input v-model="vehicle.color" placeholder="Color" :disabled="!canEdit"> </b-input>
          </b-field>
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          name="año"
          rules="required|numeric"
          class="column is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Cilindraje (cm3)"
          >
            <b-input
              v-model="vehicle.engineCapacity"
              placeholder="Cilindraje"
              @focus="$event.target.select()"
              :disabled="!canEdit"
            >
            </b-input>
          </b-field>
        </ValidationProvider>
      </b-field>
      <b-field grouped group-multiline>
        <ValidationProvider
          v-slot="{ errors }"
          name="número de chasís"
          rules="required"
          class="column is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Número de chasís"
          >
            <b-input
              v-model="vehicle.chassisNumber"
              placeholder="Número de chasís"
              :disabled="!canEdit"
            >
            </b-input>
          </b-field>
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          name="color"
          rules="required"
          class="column is-full-mobile"
          :disabled="!fuelTypes.length"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Tipo de Combustible"
          >
            <b-select
              v-model="vehicle.fuelTypeId"
              placeholder="Seleccione una Marca"
              :disabled="!canEdit"
              expanded
            >
              <option
                v-for="fuelType in fuelTypes"
                :value="fuelType.id"
                :key="fuelType.id"
              >
                {{ fuelType.name }}
              </option>
            </b-select>
          </b-field>
        </ValidationProvider>
      </b-field>
      <b-field class="column is-full-mobile" v-if="canEdit">
        <p class="control">
          <b-button type="is-light" @click="cancel"> Cancelar </b-button>
          <b-button
            type="is-link"
            native-type="submit"
          >
            Guardar
          </b-button>
        </p>
      </b-field>
    </form>
  </ValidationObserver>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    canEdit: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      vehicle: {
        plateNumber: null,
        model: null,
        brandId: null,
        year: null,
        color: null,
        engineCapacity: null,
        chassisNumber: null,
        fuelTypeId: null,
      },
      originalVehicle: null,
      searchPlateNumber: null,
      countries: [],
      carBrands: [],
      fuelTypes: [],
      deviceModels: [],
    };
  },
  watch: {
    "searchPlateNumber": {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.searchPlateNumber = val.toUpperCase();
        }
      },
    },
  },
  async mounted() {
    await this.loadCountries();
    await this.loadCarBrands();
    await this.loadFuelTypes();
  },
  methods: {
    cancel(){
      this.vehicle = JSON.parse(JSON.stringify(this.originalVehicle));
      this.$emit('update:canEdit', false);
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch(
        "countries/getCountries",
        {}
      );
      this.countries = countriesData.data;
    },
    async loadCarBrands() {
      const carBrandsData = await this.$store.dispatch(
        "carBrands/getCarBrands",
        {}
      );
      this.carBrands = carBrandsData.data;
    },
    async loadFuelTypes() {
      const fuelTypesData = await this.$store.dispatch(
        "fuelTypes/getFuelTypes",
        {}
      );
      this.fuelTypes = fuelTypesData.data;
    },
    async loadDeviceModels() {
      const deviceModelsData = await this.$store.dispatch(
        "deviceModels/getDeviceModels",
        {}
      );
      this.deviceModels = deviceModelsData.data;
    },
    searchVehicle: _.debounce(async function() {
      const plateNumber = this.searchPlateNumber;
      if (plateNumber) {
        const response = await this.$store.dispatch('vehicles/getVehicles', {
          query: {
            plate_number: plateNumber.toUpperCase(),
          },
        });
        const vehicles = response.data;
        if (vehicles.length) {
          this.vehicle = this.renameKeysOfObject(vehicles[0], {
            'id': 'id',
            'plate_number': 'plateNumber',
            'model': 'model',
            'card_brand_id': 'brandId',
            'year': 'year',
            'color': 'color',
            'engine_capacity': 'engineCapacity',
            'chassis_number': 'chassisNumber',
            'fuel_type_id': 'fuelTypeId'
          });
          this.originalVehicle = JSON.parse(JSON.stringify(this.vehicle));
          this.$root.$emit('loadVehicle', this.vehicle);
        }
      }
    }, 200),
    async save() {
      const vehicleAux = this.renameKeysOfObject(this.vehicle, {
        'id': 'id',
        'plateNumber': 'plate_number',
        'model': 'model',
        'brandId': 'card_brand_id',
        'year': 'year',
        'color': 'color',
        'engineCapacity': 'engine_capacity',
        'chassisNumber': 'chassis_number',
        'fuelTypeId': 'fuel_type_id'
      });
      try {
        const response = await this.$store.dispatch(
          "vehicles/updateVehicle",
          vehicleAux
        );
        const id = response.data.id
        if(id) {
          this.originalVehicle = JSON.parse(JSON.stringify(this.vehicle));
          this.notify({
            message: 'Actualización Exitosa',
            type: 'is-success'
          });
          this.$emit('update:canEdit', false);
        } else {
          this.notify({
            message: 'Actualización fallida',
            type: 'is-danger'
          });
        }
      } catch (error) {
        this.notify({
            message: 'Actualización fallida',
            type: 'is-danger'
          });
      }
    }
  },
};
</script>

<style scoped>
.form-section {
  background-color: #f8f8f8;
  border-radius: 10px;
}

.form-section-contact {
  background-color: white;
  border-radius: 10px;
}
</style>