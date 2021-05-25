<template>
  <b-table
    :data="beneficiaries"
    striped
    hoverable
    paginated
    :loading="isLoading"
    :per-page="8"
  >
    <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
    <b-table-column v-slot="props">
      <b-radio
        v-model="selectedBeneficiary"
        name="name"
        :native-value="props.row"
      ></b-radio>
    </b-table-column>
    <b-table-column field="dni" label="RUN" v-slot="props">
      {{ runFormatting(props.row.dni) }}
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
    <b-table-column field="address" label="Dirección" v-slot="props">
      {{ props.row.address }}
    </b-table-column>
  </b-table>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    dni: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      beneficiaries: [],
      isLoading: false,
      selectedBeneficiary: {},
    }
  },
  watch: {
    selectedBeneficiary: {
      handler(val) {
        if (val !== {}) {
          this.$emit('beneficiary-selected', this.selectedBeneficiary);
        }
      }
    }
  },
  methods: {
    async loadBeneficiaries() {
      this.isLoading = true;
      const beneficiaryData = await this.$store.dispatch(
        'beneficiaries/getBeneficiaries',
        {
          query: {
            dni: this.dni.replaceAll('.', ''),
          },
        }
      );
      this.beneficiaries = beneficiaryData.data;
      this.isLoading = false;
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }
</style>
