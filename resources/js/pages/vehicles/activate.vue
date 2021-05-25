<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">ACTIVAR VEHÍCULO</h1>
        </div>
      </div>
      <transition-group name="fade">
        <card v-if="!response" title="Formulario de ingreso" class="mb-3" key="card-1">
          <div slot="content">
            <form @submit.prevent="handleSubmit(save)" >
              <ValidationProvider
                v-slot="{ errors }"
                name="Patente de vehículo"
                rules="required|min:6|max:6"
                class="column is-6 is-full-mobile"
              >
                <b-field
                  :type="{'is-danger': errors.length}"
                  :message="errors[0]"
                  label="Patente"
                >
                  <b-input v-model="plateNumber" placeholder="patente" expanded> </b-input>
                </b-field>
              </ValidationProvider>
            </form>
          </div>
        </card>
        <card v-if="!response" title="Código QR" key="card-2">
          <div slot="content">
            <form @submit.prevent="handleSubmit(save)">
              <form v-if="textInput" @submit.prevent="handleSubmit(save)" >
                <ValidationProvider
                  v-slot="{ errors }"
                  name="número de serie"
                  rules="required"
                  class="column is-6 is-full-mobile"
                >
                  <b-field
                    :type="{'is-danger': errors.length}"
                    :message="errors[0]"
                    label="Número de Serie"
                  >
                    <b-input v-model="serialNumber" placeholder="Número de serie" expanded> </b-input>
                  </b-field>
                </ValidationProvider>
              </form>
              <div v-if="!textInput">
                <div v-if="!cameraActive && !deviceId" class="camera-button is-centered p-3" @click="showCamera">
                  <b-icon icon="qrcode-scan" size="is-large" />
                  <h5 class="title is-5 mt-5">Abrir cámara</h5>
                </div>
                <qrcode-stream v-else-if="cameraActive && !deviceId" class="cam" @decode="onDecode"></qrcode-stream>
                <div v-else class="center-container">
                  <h5 class="title is-5">Código:</h5>
                  <h5 class="is-5">{{ deviceId }}</h5>
                </div>
              </div>
              <b-button expanded class="mt-5" @click="changeInput">
                {{ textInput ? 'Usar Cámara' : 'Ingreso Manual' }}
              </b-button>
            </form>
          </div>
        </card>
        <card v-if="response" class="mb-3" key="card-3">
          <div v-if="activated" slot="content" class="center-container p-5">
            <h3 class="subtitle">Código de operación: {{ operationCode.toUpperCase() }}</h3>
            <h1 class="subtitle mt-5">ACTIVACIÓN DE VEHÍCULO</h1>
            <h1 class="title mt-2" title-weight="weight-normal">{{ plateNumber }}</h1>
            <h1 class="subtitle mt-2">REALIZADA EXITOSAMENTE</h1>
            <b-icon icon="check-circle" type="is-success" class="mt-5" size="is-large" />
          </div>
          <div v-else slot="content" class="center-container p-5">
            <h3 class="subtitle">Código de operación: {{ operationCode.toUpperCase() }}</h3>
            <h1 class="title mt-5">{{ responseMessage }}</h1>
            <b-icon icon="close-circle" type="is-danger" class="mt-5" size="is-large" />
          </div>
        </card>
      </transition-group>
      <b-button
        v-if="!response"
        expanded
        type="is-success"
        @click="activateDevice"
        :disabled="!plateNumber || !(serialNumber || deviceId)"
      >
        Validar Activación
      </b-button>
      <b-button
        v-else
        expanded
        type="is-link"
        @click="finish"
        :disabled="!response"
      >
        Finalizar
      </b-button>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card';
import baseMobileLayout from '@/components/layouts/baseMobileLayout';
import { QrcodeStream } from 'vue-qrcode-reader';

export default {
  layout: baseMobileLayout,
  components: { Card, QrcodeStream },
  data() {
    return {
      plateNumber: null,
      deviceId: null,
      serialNumber: null,
      cameraActive: false,
      textInput: false,
      activated: false,
      response: false,
      responseMessage: null,
      operationCode: null,
    }
  },
  watch: {
    plateNumber: {
      handler(val) {
        this.plateNumber = val.toUpperCase();
      },
    },
  },
  methods: {
    toIndex() {
      this.$inertia.visit('/');
    },
    redirect() {
      this.$inertia.visit(route("users.index"));
    },
    showCamera() {
      this.cameraActive = true;
    },
    changeInput() {
      this.textInput = !this.textInput;
    },
    onDecode(decodedString) {
      this.deviceId = decodedString;
      this.cameraActive = false;
    },
    async activateDevice() {
      const response = await this.$store.dispatch('devices/activateDevice', {
        plateNumber: this.plateNumber,
        serialNumber: this.serialNumber ? this.serialNumber : '',
        deviceId: this.deviceId ? this.deviceId : '',
      });
      if (response.data && response.data.status) {
        this.response = true;
        this.operationCode = response.data.code;
        if (response.data.status.code === 'activated') {
          this.activated = true;
          this.responseMessage = 'Activación de vehículo realzada exitosamente';
        } else if (response.data.details) {
          if (response.data.details.vehicle_status === 'ALREADY-ACTIVATED') {
            this.responseMessage = 'El vehículo fue activado previamente.';
          } else if (response.data.details.vehicle_status === 'NOT-FOUND') {
            this.responseMessage = 'No se encontró el vehículo.';
          } else if (response.data.details.device_status === 'INVALID-DEVICE') {
            this.responseMessage = 'El dispositivo no se pudo activar.';
          } else if (response.data.details.device_status === 'NOT-FOUND') {
            this.responseMessage = 'No se encontró el dispositivo.';
          }
        }
      }
    },
    finish() {
      this.$inertia.visit('/');
    },
  },
}
</script>

<style scoped>
  .camera-button {
    width: 100%;
    border-width: 1px;
    border-color: #dbdbdb;
    background-color: white;
    text-align: center;
  }

  .center-container {
    text-align: center;
  }

  .fade-enter-active, .fade-leave-active {
    transition: opacity .2s;
  }

  .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }
</style>
