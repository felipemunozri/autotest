<template>
  <div class="columns is-multiline mt-3">
    <div class="column is-full">
      <h1 class="title has-text-left">Busqueda de Beneficiario</h1>
      <ValidationObserver v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(searchBeneficiaries)" class="mb-5">
          <ValidationProvider
            v-slot="{ errors }"
            name="run"
            rules="required|run"
            class="column is-half p-0"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="RUN"
              grouped
            >
              <b-input
                v-model="dni"
                placeholder="Ingrese RUN de beneficiario"
                :disabled="isSelected"
                expanded
              ></b-input>
              <p class="control">
                <b-button
                  type="is-link"
                  native-type="submit"
                  icon-left="magnify"
                  :disabled="invalid || isSelected"
                  :loading="isLoading"
                >
                  Buscar
                </b-button>
              </p>
            </b-field>
          </ValidationProvider>
        </form>
      </ValidationObserver>
      <fade-in-trasition>
        <div v-if="beneficiary" key="beneficiary-info" ref="benficiary-section">
          <div class="mb-4">
            <beneficiary-info v-bind="beneficiary"></beneficiary-info>
          </div>
          <b-button
            type="is-link"
            @click="selectBeneficiary"
            v-show="!isSelected"
          >
            Continuar
          </b-button>
        </div>
        <div v-else-if="!beneficiary && searched" key="no-info" class="columns">
          <div class="column is-narrow has-text-centered is-vcentered">
            <b-icon icon="account-cancel-outline" type="is-danger"> </b-icon>
            <span class="has-text-weight-bold has-text-danger">
              Beneficiario no encontrado
            </span>
          </div>
        </div>
      </fade-in-trasition>
    </div>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";
import FoundBeneficiariesTable from "@/components/events/found_beneficiaries_table";
import BeneficiaryInfo from './beneficiary_info.vue';

export default {
  components: {
    FoundBeneficiariesTable,
    BeneficiaryInfo
  },
  mixins: [Formatters()],
  data() {
    return {
      isLoading: false,
      dni: null,
      beneficiary: null,
      isSelected: false,
      searched: false,
    };
  },
  watch: {
    dni(value) {
      this.dni = this.runFormatting(value);
    },
  },
  methods: {
    selectBeneficiary() {
      this.isSelected = true;
      this.$emit("onConfirmBeneficiarySelection", this.beneficiary);
    },
    async searchBeneficiaries() {
      this.isLoading = true;
      const response = await this.$store.dispatch(
        "beneficiaries/getBeneficiaries",
        {
          query: {
            dni: this.dni.replaceAll(".", ""),
          },
        }
      );
      if (response && response.data) {
        this.beneficiary = response.data[0];
        this.scrollToSection('benficiary-section')
      } else {
        this.beneficiary = null;
      }
      if (!this.searched) this.searched = true;
      this.isLoading = false;
    },
    scrollToSection(section){
      this.$nextTick(() => {
        if (this.$refs[section]) this.$refs[section].scrollIntoView();
      });
    }
  },
};
</script>

<style scoped>
</style>