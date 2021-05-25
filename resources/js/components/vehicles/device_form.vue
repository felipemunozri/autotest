<template>
  <ValidationObserver v-slot="{ handleSubmit, invalid }" ref="formObserver">
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
              :disabled="!canEdit"
            >
            </b-input>
          </b-field>
        </ValidationProvider>
        <ValidationProvider
          v-slot="{ errors }"
          name="modelo"
          rules="required"
          class="column is-full-mobile"
          :disabled="!deviceModels.length"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Modelo"
          >
            <b-select
              v-model="device.modelId"
              placeholder="Seleccione un Modelo"
              :disabled="!canEdit"
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
            class="column is-full"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Teléfono"
            >
              <phone-selector :simcard.sync="simcardSelected" :disabled="!canEdit"/>
              <input type="hidden" :value="simcardSelected" />
            </b-field>
          </ValidationProvider>
        </b-field>
      <b-field grouped group-multiline>
        <ValidationProvider
          v-slot="{ errors }"
          name="status"
          rules="required"
          class="column is-half is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger' : ''"
            :message="errors[0]"
            label="Estado"
          >
            <b-select
              v-model="device.statusId"
              placeholder="Seleccione un Estado"
              :disabled="!canEdit"
              expanded
            >
              <option
                v-for="status in deviceStatus"
                :value="status.id"
                :key="status.id"
              >
                {{ status.description }}
              </option>
            </b-select>
          </b-field>
        </ValidationProvider>
      </b-field>
      <b-collapse
        :open.sync="openChangePassword"
        position="is-bottom"
        aria-id="changePasswordForm"
        v-if="canEdit"
      >
        <template #trigger="props">
          <a aria-controls="changePasswordForm">
            <b-icon :icon="!props.open ? 'menu-down' : 'menu-up'"></b-icon>
            {{
              !props.open
                ? "Cambiar contraseña"
                : "Cancelar cambio de contraseña"
            }}
          </a>
        </template>
        <b-field grouped group-multiline v-if="openChangePassword">
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
      </b-collapse>

      <b-field class="column is-full-mobile" v-if="canEdit">
        <p class="control">
          <b-button type="is-light" @click="cancel"> Cancelar </b-button>
          <b-button type="is-link" native-type="submit" :disabled="invalid">
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
    PhoneSelector,
  },
  props: {
    vehicleId: {
      type: String,
      default: null,
    },
    canEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      device: {
        id: null,
        serialNumber: null,
        statusId: null,
        modelId: null,
        password: null,
        confirmPassword: null,
      },
      openChangePassword: false,
      originalDevice: null,
      originalSimcard: null,
      deviceModels: [],
      deviceStatus: [],
      simcardSelected: null,
    };
  },
  computed:{
    hasDevice() {
      return this.device && this.device.id
    }
  },
  watch: {
    openChangePassword(val) {
      if (!val) {
        this.device.password = null;
        this.device.confirmPassword = null;
      }
    },
    vehicleId: {
      handler(val) {
        this.fetchData();
      },
    },
  },
  async mounted() {
    await Promise.all([this.loadDeviceModels(), this.loadDeviceStatus()]);
  },
  methods: {
    cancel() {
      this.device = JSON.parse(JSON.stringify(this.originalDevice));
      this.simcardSelected = JSON.parse(JSON.stringify(this.originalSimcard));
      this.$emit("update:canEdit", false);
    },
    async fetchData() {
      if (!this.vehicleId) return false;
      this.isLoading = true;
      this.$emit("update:canEdit", false);
      const { data } = await this.$store.dispatch("devices/getDevices", {
        query: {
          vehicle_id: this.vehicleId,
          include: ["simCard"],
        },
      });
      if (data && data.length) {
        const [device] = data;
        this.simcardSelected = device.sim_card;
        this.device = {
          id: device.id,
          serialNumber: device.serial_number,
          modelId: device.device_model_id,
          statusId: device.device_status_id,
          password: null,
          confirmPassword: null,
        };
        this.originalDevice = JSON.parse(JSON.stringify(this.device));
        this.originalSimcard = JSON.parse(JSON.stringify(this.simcardSelected));
      } else {
        this.reset()
      }
      this.$emit("loadedData", this.device);
     
      this.isLoading = false;
    },
    reset() {
      this.device = {
        id: null,
        serialNumber: null,
        modelId: null,
        balance: null,
        password: null,
        confirmPassword: null,
      };
      this.originalDevice = JSON.parse(JSON.stringify(this.device));
      this.originalSimcard = null;
      this.simcardSelected = JSON.parse(JSON.stringify(this.originalSimcard));
      this.$refs.formObserver.reset();
    },
    async loadDeviceModels() {
      const deviceModelsData = await this.$store.dispatch(
        "deviceModels/getDeviceModels",
        {}
      );
      this.deviceModels = deviceModelsData.data;
    },
    async loadDeviceStatus() {
      const response = await this.$store.dispatch(
        "deviceStatus/getAllStatus",
        {}
      );
      this.deviceStatus = response.data;
    },
    async save() {
      let deviceAux = {
        id: this.device.id,
        device_model_id: this.device.modelId,
        serial_number: this.device.serialNumber,
        code: this.deviceModels.find((x) => x.id === this.device.modelId).name,
        password: this.device.password,
        simcard_id: this.simcardSelected?.id,
      };
      if (!this.device.password || !this.device.confirmPassword) {
        delete deviceAux.password;
      }
      try {
        const { data } = await this.$store.dispatch(
          "devices/updateDevice",
          deviceAux
        );
        const deviceId = data.id;
        if (deviceId) {
          this.originalDevice = JSON.parse(JSON.stringify(this.device));
          this.originalSimcard = JSON.parse(JSON.stringify(this.simcardSelected));
          this.notify({
            message: "Actualización exitosa",
            type: "is-success",
          });
          this.$emit("update:canEdit", false);
        } else {
          this.notify({
            message: "Actualización fallida",
            type: "is-danger",
          });
        }
      } catch (e) {
        console.log(e);
        this.notify({
          message: "Actualización fallida",
          type: "is-danger",
        });
      }
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
