<template>
  <ValidationObserver v-slot="{ handleSubmit}">
    <form @submit.prevent="handleSubmit(save)">
      <b-field grouped group-multiline>
        <ValidationProvider
          v-slot="{ errors }"
          name="número de serie"
          rules="required"
          class="column is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Número de Serie"
          >
            <b-input
              v-model="device.serialNumber"
              placeholder="Número de serie"
            >
            </b-input>
          </b-field>
        </ValidationProvider>
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
            <b-select
              v-model="device.modelId"
              placeholder="Seleccione un Modelo"
              expanded
            >
              <option
                v-for="deviceModel in deviceModels"
                :value="deviceModel.id"
                :key="deviceModel.id"
              >
                {{ deviceModel.name }}
              </option>
            </b-select>
          </b-field>
        </ValidationProvider>
      </b-field>
      <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="teléfono"
            class="column is-half is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Teléfono"
            >
              <phone-selector :simcard.sync="simcardSelected"/>
              <input type="hidden" :value="simcardSelected"/>
            </b-field>
          </ValidationProvider>
        </b-field>
      <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Contraseña"
            rules="required|confirmed:confirmPassword"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Contraseña"
            >
              <b-input
                type="password"
                v-model="device.password"
                placeholder="Contraseña"
                password-reveal
              >
              </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Confirmar contraseña"
            rules="required"
            class="column is-full-mobile"
            vid="confirmPassword"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Confirmar contraseña"
            >
              <b-input
                type="password"
                v-model="device.confirmPassword"
                placeholder="Confirmar contraseña"
                password-reveal
              >
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
      
      <b-field class="column is-full-mobile">
        <p class="control">
          <b-button type="is-light" @click="cancel"> Cancelar </b-button>
          <b-button type="is-link" native-type="submit">
            Guardar
          </b-button>
        </p>
      </b-field>
    </form>
  </ValidationObserver>
</template>

<script>
import { Formatters } from "@/mixins/formatters";
import PhoneSelector from "@/components/simCards/phone_selector";

export default {
  mixins: [Formatters()],
  components: {
    PhoneSelector
  },
  data() {
    return {
      device: {
        serialNumber: null,
        modelId: null,
        password: null,
        confirmPassword: null
      },
      openChangePassword: false,
      simcardSelected: null,
      deviceModels: [],
    };
  },
  async mounted() {
    await Promise.all([
      this.loadDeviceModels(),
    ]);
  },
  methods: {
    cancel(){
      this.redirect();
    },
    reset() {
      this.device = {
        serialNumber: null,
        modelId: null,
        password: null,
        confirmPassword: null
      };
    },
    async loadDeviceModels() {
      const deviceModelsData = await this.$store.dispatch(
        "deviceModels/getDeviceModels",
        {}
      );
      this.deviceModels = deviceModelsData.data;
    },
    async save() {
      let deviceAux = {
        device_model_id: this.device.modelId,
        serial_number: this.device.serialNumber,
        code: (this.deviceModels.find((x) => x.id === this.device.modelId)).name,
        password: this.device.password,
        simcard_id: this.simcardSelected?.id
      };
      
      try {
        const { data } = await this.$store.dispatch(
          "devices/createDevice",
          deviceAux
        );
        const deviceId = data.id
        if(deviceId) {
          this.notify({
            message: 'Creación exitosa',
            type: 'is-success'
          });
          this.redirect();
        } else {
          this.notify({
            message: 'Creación fallida',
            type: 'is-danger'
          });
        }
      } catch (e) {
        console.log(e)
        this.notify({
          message: 'Creación fallida',
          type: 'is-danger'
        });
      }
    },
    redirect() {
      this.$inertia.visit(route("devices.index"));
    },
  },
};
</script>

<style scoped>
  .form-section {
    background-color: #f8f8f8;
    border-radius: 10px;
  }
</style>
