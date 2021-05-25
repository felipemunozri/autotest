<template>
  <div class="columns is-multiline mt-3">
    <div class="column is-full">
      <h1 class="title has-text-left">Verificación de Identidad</h1>
      <b-table :data="beneficiaries" striped hoverable paginated :per-page="8">
        <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
        <b-table-column field="dni" label="RUN" v-slot="props">
          {{ runFormatting(props.row.dni) }}
        </b-table-column>
        <b-table-column field="name" label="Nombre" v-slot="props">
          <span>
            {{ props.row.name }} {{ props.row.lastname }}
            <b-icon 
              icon="check-decagram"
              type="is-info"
              size="is-small"
              v-if="props.row.pivot && props.row.pivot.owner"
            ></b-icon
          ></span>
        </b-table-column>
        <b-table-column field="name" label="Dirección" v-slot="props">
          {{ props.row.address }}
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
      <ValidationObserver v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(verifyCode)" class="mb-5">
          <ValidationProvider
            v-slot="{ errors }"
            name="código de seguridad"
            rules="required|min:5|max:5"
            class="column is-one-third p-0"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Código de seguridad"
              grouped
            >
              <b-input
                v-model="code"
                placeholder="Ingrese el código de seguridad"
                :disabled="!codeSent"
                expanded
              ></b-input>
              <p class="control">
                <b-button
                  type="is-success"
                  native-type="submit"
                  icon-left="progress-check"
                  :disabled="invalid || !codeSent"
                  :loading="isLoading"
                >
                  Validar Código
                </b-button>
              </p>
            </b-field>
          </ValidationProvider>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    vehicleId: {
      type: String,
      required: true,
    },
    eventId: {
      type: String,
      default: null,
    },
    beneficiaries: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      isLoading: false,
      disableButtons: false,
      verifierBeneficiaryId: null,
      codeSent: false,
      code: null,
    };
  },
  watch: {
    code: {
      handler(val) {
        this.code = val.toUpperCase();
      },
    },
  },
  methods: {
    async sendConfirmationCode(beneficiaryId) {
      this.disableButtons = true;
      const response = await this.$store.dispatch(
        "actions/sendConfirmationCode",
        {
          beneficiaryId,
          eventId: this.eventId,
        }
      );

      if (response.sent) {
        this.notify({
          message: '¡Código enviado!',
          type: 'is-success'
        });
        this.verifierBeneficiaryId = beneficiaryId;
        this.codeSent = true;
      } else {
        this.notify({
          message: 'No se pudo enviar el código. Intente de nuevo.',
          type: 'is-danger'
        });
        this.verifierBeneficiaryId = null;
        this.codeSent = false;
      }
      this.disableButtons = false;
    },
    async verifyCode() {
      this.isLoading = true;
      const response = await this.$store.dispatch("events/validateCode", {
        beneficiaryId: this.verifierBeneficiaryId,
        eventId: this.eventId,
        code: this.code,
      });

      if (response && response.is_valid) {
        this.notify({
          message: '¡Código Correcto!',
          type: 'is-success'
        });
        this.$emit('verifiedCode');
      } else {
        this.notify({
            message: 'Código inválido. Intente de nuevo.',
            type: 'is-danger'
          });
      }
      this.isLoading = false;
    },
  },
};
</script>