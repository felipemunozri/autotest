<template>
  <b-table
    :data="data"
    striped
    hoverable
    paginated
    :loading="isLoading"
    :narrowed="isNarrowed"
    :per-page="10"
  >
    <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
    <b-table-column field="dni" label="RUN" v-slot="props">
      {{ runFormatting(props.row.dni) }}
    </b-table-column>
    <b-table-column field="name" label="Nombre" v-slot="props">
      {{ `${props.row.name} ${props.row.lastname}` }}
      <b-icon
          icon="check-decagram"
          v-if="props.row.vehicle.pivot.owner"
          type="is-success"
          size="is-small">
      </b-icon>
    </b-table-column>
    <b-table-column field="phone_number" label="Teléfono" v-slot="props">
      {{props.row.phone_number}}
    </b-table-column>
    <b-table-column v-slot="props">
      <div class="buttons">
          <b-button
            v-if="props.row.vehicle.pivot.owner"
            size="is-small"
            @click="confirmIsNotOwner(props.row)"
            type="is-warning">
            <b-icon size="is-small" icon="check-decagram"></b-icon>
          </b-button>
          <b-button
            v-else
            size="is-small"
            @click="confirmIsOwner(props.row)"
            type="is-success">
            <b-icon size="is-small" icon="check-decagram"></b-icon>
          </b-button>
          <b-button
            size="is-small"
            type="is-danger" @click="confirmRemove(props.row)">
            <b-icon size="is-small" icon="close"></b-icon>
          </b-button>
      </div>
    </b-table-column>
  </b-table>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    vehicleId: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      data: [],
      isLoading: false,
      isNarrowed: true,
      currentBeneficary: null
    }
  },
  watch: {
    vehicleId: {
      handler(val) {
        this.fetchData();
      },
    },
  },
  mounted() {
    this.$root.$on('savedContact', (data) => {
      this.fetchData();
    })
    this.fetchData();
  },
  methods: {
    confirmIsOwner(beneficiary) {
      this.currentBeneficary = beneficiary;
      this.$buefy.dialog.confirm({
          title: 'Marcar como dueño',
          message: '¿Seguro que desea convertir al beneficiario en dueño del vehículo?',
          confirmText: 'Aceptar',
          cancelText: 'Cancelar',
          type: 'is-info',
          hasIcon: true,
          onConfirm: this.toOwnVehicle
      })
    },
    confirmIsNotOwner(beneficiary) {
      this.currentBeneficary = beneficiary;
      this.$buefy.dialog.confirm({
          title: 'Quitar marca de dueño',
          message: '¿Seguro que desea quitar la marca de dueño?',
          confirmText: 'Aceptar',
          cancelText: 'Cancelar',
          type: 'is-warning',
          hasIcon: true,
          onConfirm: this.removeOwner
      })
    },
    confirmRemove(beneficiary){
      this.currentBeneficary = beneficiary;
      this.$buefy.dialog.confirm({
          title: 'Quitar Beneficiario',
          message: '¿Seguro que desea desvincular el beneficiario del vehículo?',
          confirmText: 'Quitar',
          cancelText: 'Cancelar',
          type: 'is-danger',
          hasIcon: true,
          onConfirm: this.unassignBeneficiary
      })
    },
    async toOwnVehicle(){
      try {
        const response = await this.$store.dispatch(
          'beneficiaryVehicles/updateBeneficiaryVehicle', {
            beneficiary_id: this.currentBeneficary.id,
            vehicle_id: this.currentBeneficary.vehicle.id,
            owner: true
          }
        );
        let data = response.data;
        this.notify({
          message: 'Actualizado Exitosamente',
          type: 'is-success'
        });
        this.fetchData();
      } catch (e) {
        console.log(e);
        this.notify({
          message: 'Actualización fallida',
          type: 'is-danger'
        });
      }
    },
    async removeOwner(){
      try {
        const response = await this.$store.dispatch(
          'beneficiaryVehicles/updateBeneficiaryVehicle', {
            beneficiary_id: this.currentBeneficary.id,
            vehicle_id: this.currentBeneficary.vehicle.id,
            owner: false
          }
        );
        let data = response.data;
        this.notify({
          message: 'Actualizado Exitosamente',
          type: 'is-success'
        });
        this.fetchData();
      } catch (e) {
        console.log(e);
        this.notify({
          message: 'Actualización fallida',
          type: 'is-danger'
        });
      }
    },
    async unassignBeneficiary(){
      try {
        const response = await this.$store.dispatch(
          'beneficiaryVehicles/deleteBeneficiaryVehicle', {
            beneficiary_id: this.currentBeneficary.id,
            vehicle_id: this.currentBeneficary.vehicle.id
          }
        );
        let data = response.data;
        this.notify({
          message: 'Beneficiario desvinculado',
          type: 'is-success'
        });
        this.fetchData();
      } catch (e) {
        console.log(e);
        this.notify({
          message: 'No se pudo quitar desvincular el beneficiario',
          type: 'is-danger'
        });
      }
    },
    async fetchData() {
      if(!this.vehicleId) return false;
      this.isLoading = true;
      const response = await this.$store.dispatch(
        'beneficiaries/getBeneficiaries', {
          query: {
            vehicle_id: this.vehicleId,
            include: [
              'vehicles'
            ]
          }
        }
      );
      let data = response.data;
      if(data) {
        data = data.map((beneficiary) => {
          let vehicle = beneficiary.vehicles.filter(v => v.id === this.vehicleId)[0]
          delete beneficiary.vehicles;
          return {
            ...beneficiary,
            vehicle
          }
        });
      }
      this.data = data;
      this.isLoading = false;
    },
    toDetails(id) {
      this.$inertia.visit(route('user.show', id));
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }
</style>
