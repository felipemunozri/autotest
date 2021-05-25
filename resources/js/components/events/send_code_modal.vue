<template>
  <b-modal
    v-model="modalShowing"
    has-modal-card
    trap-focus
    :destroy-on-hide="false"
    aria-role="dialog"
    aria-label="Example Modal"
    aria-modal
    scroll="keep"
  >
    <div class="card p-3">
      <div class="card-content">
        <h3 class="title is-3">Enviar Código de Verificación</h3>
        <b-table
          :data="beneficiaries"
          striped
          hoverable
          paginated
          :loading="false"
          :per-page="8"
        >
          <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
          <b-table-column field="dni" label="RUN" v-slot="props">
            {{ props.row.dni }}
          </b-table-column>
          <b-table-column field="name" label="Nombre" v-slot="props">
            {{ props.row.name }} {{ props.row.lastname }}
          </b-table-column>
          <b-table-column field="emial" label="Email" v-slot="props">
            {{ props.row.email }}
          </b-table-column>
          <b-table-column field="phone_number" label="Teléfono" v-slot="props">
            {{ props.row.phone_number }}
          </b-table-column>
          <b-table-column v-slot="props">
            <b-button
              type="is-link"
              class="search-button"
              size="is-small"
              @click="sendConfirmationCode(props.row.id)"
              expanded
              :disabled="disableButtons"
            >
              Enviar código
            </b-button>
          </b-table-column>
        </b-table>
        <div class="columns">
          <div class="column">
            <b-button class="is-pulled-right" @click="hideModal">
              Cerrar
            </b-button>
          </div>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: {
    beneficiaries: {
      type: Array,
      default() {
        return [];
      },
    },
    vehicleId: {
      type: String,
      default() {
        return '';
      },
    },
    ownerId: {
      type: String,
      default() {
        return '';
      },
    },
    event: {
      type: Object,
      default() {
        return null;
      },
    },
  },
  data() {
    return {
      modalShowing: false,
      disableButtons: false,
    }
  },
  methods: {
    async sendConfirmationCode(beneficiaryId) {
      this.disableButtons = true;
      const response = await this.$store.dispatch('actions/sendConfirmationCode', {
        beneficiaryId,
        eventId: this.event.id,
      });

      if (response.sent) {
        this.$buefy.dialog.alert({
          icon: 'check-circle',
          hasIcon: true,
          message: `¡Código enviado!`,
          confirmText: 'Aceptar',
          type: 'is-success',
        });
        this.$emit('code-sent', beneficiaryId);
      } else {
        this.$buefy.dialog.alert({
          icon: 'close-thick',
          hasIcon: true,
          message: `No se pudo enviar el código. Intente de nuevo.`,
          confirmText: 'Aceptar',
          type: 'is-danger',
        });
      }
      this.disableButtons = false;
    },
    showModal() {
      this.modalShowing = true;
    },
    hideModal() {
      this.modalShowing = false;
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }
</style>
