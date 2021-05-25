<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">INFORMACIÓN DE VEHÍCULO</h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-full is-full-mobile">
          <card  title="Información de Vehículo">
            <div slot="header-right" class="p-2" v-if="vehicleId && !canEditVehicle">
              <b-button type="is-info" size="is-small" icon-left="pencil" @click="canEditVehicle = true"> Editar </b-button>
            </div>
            <div slot="content">
              <edit-vehicle-form :can-edit.sync="canEditVehicle"/>
            </div>
          </card>
        </div>
        <div class="column is-6 is-full-mobile">
          <card title="Información de Beneficiarios">
            <div slot="header-right" class="p-2">
              <beneficiary-modal :vehicle-id="vehicleId" v-show="vehicleId"/>
            </div>
            <div slot="content">
              <beneficiaries-table :vehicle-id="vehicleId"/>
            </div>
          </card>
        </div>
        <div class="column is-6 is-full-mobile">
          <card title="Información de Dispositivo">
            <div slot="header-right" class="p-2" v-if="vehicleId && !canEditDevice && hasDevice">
              <b-button type="is-info" size="is-small" icon-left="pencil" @click="canEditDevice = true"> Editar </b-button>
            </div>
            <div slot="content">
              <b-notification
                type="is-warning"
                aria-close-label="Close notification"
                role="alert"
                v-if="vehicleId && !hasDevice && loadedDevice"
                >
                <b>Vehículo sin dispositivo</b>
              </b-notification>
              <device-form :vehicle-id="vehicleId" :can-edit.sync="canEditDevice" @loadedData="loadedDeviceOfVehicle"/>
            </div>
          </card>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import EditVehicleForm from '@/components/vehicles/edit_form'
import DeviceForm from '@/components/vehicles/device_form'
import BeneficiariesTable from '@/components/vehicles/beneficiaries_table'
import BeneficiaryModal from '@/components/vehicles/beneficiary_modal'

export default {
  components: { 
    Card,
    EditVehicleForm,
    DeviceForm,
    BeneficiariesTable,
    BeneficiaryModal
  },
  data() {
    return {
      vehicle: null,
      canEditVehicle: false,
      canEditDevice: false,
      hasDevice: false,
      loadedDevice: false
    }
  },
  computed: {
    vehicleId() {
      return this.vehicle ? this.vehicle.id : null
    }
  },
  methods: {
    toIndex() {
      this.$inertia.visit('/');
    },
    loadedDeviceOfVehicle(data) {
      if (data && data.id){
        this.hasDevice = true;
      } else {
        this.hasDevice = false;
      }
      this.loadedDevice = true;
    }
  },
  mounted(){
    this.$root.$on('loadVehicle', (data) => {
      this.vehicle = data;
    })
  }
}
</script>
