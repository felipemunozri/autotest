<template>
  <div>
    <ValidationObserver v-slot="{ handleSubmit, invalid }">
      <form @submit.prevent="handleSubmit(save)" class="form-section p-3 mt-3">
        <ValidationProvider
          v-slot="{ errors }"
          name="run"
          rules="required|run"
          class="column is-6 is-full-mobile"
        >
          <b-field
            :type="errors.length > 0 ? 'is-danger':''"
            :message="errors[0]"
            label="RUN"
          >
            <b-input v-model="searchDni" placeholder="RUN" expanded>
            </b-input>
            <p class="control">
              <b-button type="is-primary" icon-left="magnify" label="Buscar" @click="searchBeneficiary"/>
            </p>
          </b-field>
        </ValidationProvider>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Nombre"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger':''"
              :message="errors[0]"
              label="Nombre"
            >
              <b-input v-model="beneficiary.name" placeholder="Nombre" :disabled="formDisabled">
              </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Apellidos"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger':''"
              :message="errors[0]"
              label="Apellidos"
            >
              <b-input v-model="beneficiary.lastname" placeholder="Apellidos" :disabled="formDisabled">
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Correo"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger':''"
              :message="errors[0]"
              label="Correo"
            >
              <b-input v-model="beneficiary.email" placeholder="Correo" :disabled="formDisabled">
              </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Teléfono"
            rules="required|min:6"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger':''"
              :message="errors[0]"
              label="Teléfono"
            >
              <p class="control">
                <span class="button is-static">+56 9</span>
              </p>
              <b-input v-model="beneficiary.phoneNumber" placeholder="Teléfono" :disabled="formDisabled" expanded>
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-field class="column is-full-mobile"><!-- Label left empty for spacing -->
            <p class="control">
              <b-button type="is-light" @click="redirect">
                Cancelar
              </b-button>
              <b-button
                type="is-link"
                native-type="submit"
                :disabled="invalid"
              >
                Guardar
              </b-button>
            </p>
        </b-field>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { Formatters } from '@/mixins/formatters'

export default {
  mixins: [Formatters()],
  data() {
    return {
      searchDni: null,
      beneficiary: {
        dni: null,
        name: null,
        lastname: null,
        email: null,
        phoneNumber: null,
      },
      countries: [],
    }
  },
  computed: {
    beneficiaryId(){
      return this.beneficiary.id;
    },
    formDisabled() {
      return !this.beneficiary.dni;
    }
  },
  watch: {
    'searchDni': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.searchDni = this.runFormatting(val);
          this.reset();
        }
      },
    },
    'beneficiary.phoneNumber': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.beneficiary.phoneNumber = this.phoneFormatting(val);
        }
      },
    },
  },
  async mounted() {
    await this.loadCountries();
  },
  methods: {
    reset() {
      this.beneficiary = {
        dni: null,
        name: null,
        lastname: null,
        email: null,
        phoneNumber: null,
      };
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch('countries/getCountries', {});
      this.countries = countriesData.data;
    },
    searchBeneficiary: _.debounce(async function() {
      const dni = this.searchDni;
      if (dni) {
        const response = await this.$store.dispatch('beneficiaries/getBeneficiaries', {
          query: {
            dni: dni.replaceAll('.', ''),
          },
        });
        const beneficiaries = response.data;
        if (beneficiaries.length) {
          this.beneficiary = this.renameKeysOfObject(beneficiaries[0], {
            'id': 'id',
            'dni': 'dni',
            'name': 'name',
            'lastname': 'lastname',
            'email': 'email',
            'phone_number': {
              name: 'phoneNumber',
              format: (phone) => {
                return phone.slice(4)
              }
            }
          })
        }
      }
    }, 200),
    async save() {
      await this.$store.dispatch('beneficiaries/updateBeneficiary',
        {
          ...this.beneficiary,
          dni: this.beneficiary.dni.replaceAll('.', ''),
          phone_number: `+569${this.beneficiary.phoneNumber.replaceAll(' ', '')}`,
        }
      );
      this.$buefy.dialog.alert({
        title: '¡Beneficiario Editado!',
        icon: 'account',
        hasIcon: true,
        message: `Se ha editado el beneficiario.`,
        confirmText: 'Aceptar',
        onConfirm: this.redirect,
      });
    },
    redirect() {
      this.$inertia.visit(route('dashboard.index'));
    },
  }
}
</script>

<style scoped>
.form-section {
  background-color: #f8f8f8;
  border-radius: 10px;
}
</style>