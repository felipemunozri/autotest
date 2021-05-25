<template>
  <div>
    <b-button type="is-info" size="is-small" icon-left="plus" @click="open">
      Añadir</b-button
    >
    <b-modal v-model="openModal" has-modal-card>
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(save)">
          <div class="modal-card" style="width: 500px">
            <header class="modal-card-head">
              <p class="modal-card-title">Añadir Beneficiario</p>
              <button type="button" class="delete" @click.stop="close" />
            </header>
            <section class="modal-card-body">
              <ValidationProvider
                v-slot="{ errors }"
                name="run"
                rules="required|run"
              >
                <b-field
                  :type="errors.length > 0  || !isAssignable ? 'is-danger' : ''"
                  :message="[
                    errors[0],
                    !errors.length && !isAssignable ? 'El usuario ya se encuentra asignado' : ''
                  ]"
                  label="RUN"
                >
                  <b-input v-model="beneficiary.dni" placeholder="RUN"> </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="nombre"
                rules="required"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger' : ''"
                  :message="errors[0]"
                  label="Nombre"
                >
                  <b-input v-model="beneficiary.name" placeholder="Nombre" :disabled="beneficiary.id">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="apellidos"
                rules="required"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger' : ''"
                  :message="errors[0]"
                  label="Apellidos"
                >
                  <b-input v-model="beneficiary.lastname" placeholder="Apellidos" :disabled="beneficiary.id">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="correo"
                rules="required|email"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger' : ''"
                  :message="errors[0]"
                  label="Correo"
                >
                  <b-input v-model="beneficiary.email" placeholder="Correo" :disabled="beneficiary.id">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="teléfono"
                rules="required|min:6"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger' : ''"
                  :message="errors[0]"
                  label="Teléfono"
                >
                  <p class="control">
                    <span class="button is-static">+56 9</span>
                  </p>
                  <b-input
                    v-model="beneficiary.phoneNumber"
                    placeholder="Teléfono"
                    :disabled="beneficiary.id"
                    expanded
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
            </section>
            <footer class="modal-card-foot">
              <b-button label="Cancelar" @click.stop="close" />
              <b-button type="is-link" native-type="submit" :disabled="!isAssignable">
                Añadir
              </b-button>
            </footer>
          </div>
        </form>
      </ValidationObserver>
    </b-modal>
  </div>
</template>

<script>
import { Formatters } from '@/mixins/formatters';

export default {
  mixins: [Formatters()],
  props: {
    vehicleId: {
      type: String,
      default: null
    },
  },
  data() {
    return {
      openModal: false,
      isLoading: false,
      beneficiary: {
        id: null,
        dni: null,
        name: null,
        lastname: null,
        email: null,
        phoneNumber: null,
      },
      vehicles: []
    };
  },
  computed: {
    isAssignable() {
      let index = this.vehicles.findIndex((v) => v.id === this.vehicleId);
      if (index >= 0) {
        return false;
      } else {
        return true;
      }
      
    }
  },
  watch: {
    'beneficiary.dni': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.beneficiary.dni = this.runFormatting(val);
          this.verifyDni();
        }
      },
    },
  },
  mounted() {
  },
  methods: {
    verifyDni: _.debounce(async function() {
      let dni = this.beneficiary.dni;
      if (dni) {
        const response = await this.$store.dispatch('beneficiaries/getBeneficiaries', {
          query: {
            dni: dni.replaceAll('.', '').toUpperCase(),
            include: [
              'vehicles'
            ]
          },
        });
        const beneficiaries = response.data;
        if (beneficiaries.length) {
          this.beneficiary.id = beneficiaries[0].id;
          this.beneficiary.dni = this.runFormatting(beneficiaries[0].dni);
          this.beneficiary.name = beneficiaries[0].name;
          this.beneficiary.lastname = beneficiaries[0].lastname;
          this.beneficiary.email = beneficiaries[0].email;
          this.beneficiary.phoneNumber = beneficiaries[0].phone_number.slice(4);
          this.vehicles = beneficiaries[0].vehicles
        } else {
          this.beneficiary = {
            ...this.beneficiary,
            id: null,
            name: null,
            lastname: null,
            email: null,
            phoneNumber: null,
          }
          this.vehicles = []
        }
      }
    }, 300),
    async save() {
      let response;
      let beneficiaryId;
      let beneficiaryAux = {
        id: this.beneficiary.id,
        dni: this.beneficiary.dni.replaceAll('.', ''),
        name: this.beneficiary.name,
        lastname: this.beneficiary.lastname,
        phone_number: `+569${this.beneficiary.phoneNumber.replaceAll(' ', '')}`,
        email: this.beneficiary.email,
      }
      try {
        if (this.beneficiary.id) {
          response = await this.$store.dispatch('beneficiaries/updateBeneficiary', beneficiaryAux);
          beneficiaryId = response.data.id;
        } else {
          delete beneficiaryAux.id
          response = await this.$store.dispatch(
            'beneficiaries/createBeneficiary',
            beneficiaryAux
          );
          beneficiaryId = response.data.id;
        }
        const beneficiaryVehicleAux = {
          beneficiary_id: beneficiaryId,
          vehicle_id: this.vehicleId,
          owner: false,
        }
        await this.$store.dispatch(
          'beneficiaryVehicles/createBeneficiaryVehicle',
          beneficiaryVehicleAux
        );
        this.notify({
          message: 'Asignación exitosa',
          type: 'is-success'
        });
      } catch (error) {
        console.log(error);
        this.notify({
          message: 'Asignación fallida',
          type: 'is-danger'
        });
      }
      this.close();
      this.$root.$emit('savedContact');
    },
    close() {
      this.openModal = false;
    },
    open() {
      this.reset();
      this.openModal = true;
    },
    reset(){
      this.beneficiary = {
        id: null,
        dni: null,
        name: null,
        lastname: null,
        email: null,
        phoneNumber: null,
      };
      this.vehicles = [];
    }
  },
};
</script>