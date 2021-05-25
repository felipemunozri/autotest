<template>
  <b-modal
    v-model="isComponentModalActive"
    has-modal-card
    trap-focus
    :destroy-on-hide="false"
    aria-role="dialog"
    aria-label="Crear tarjeta SIM "
    aria-modal
  >
    <template>
      <ValidationObserver v-slot="{ handleSubmit }" ref="formObserver" v-if="isComponentModalActive">
        <form @submit.prevent="handleSubmit(save)">
          <div class="modal-card">
            <header class="modal-card-head">
              <p class="modal-card-title">Crear nueva tarjeta SIM</p>
              <button type="button" class="delete" @click="closeSimcardModal" />
            </header>
            <section class="modal-card-body">
              <b-field grouped group-multiline>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="pais"
                  rules="required"
                  class="column is-full"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger' : ''"
                    :message="errors[0]"
                    label="País"
                  >
                    <b-autocomplete
                      v-model="countryName"
                      placeholder="Seleccione un país"
                      :open-on-focus="true"
                      :data="countries"
                      @input="searchCountry"
                      field="name"
                      @select="(option) => (countrySelected = option)"
                      :clearable="true"
                    >
                    </b-autocomplete>
                  </b-field>
                </ValidationProvider>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="carrier"
                  rules="required"
                  class="column is-full"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger' : ''"
                    :message="errors[0]"
                    label="Carrier"
                  >
                    <b-select
                      v-model="simCard.carrier_id"
                      placeholder="Seleccione un Carrier"
                      expanded
                    >
                      <option
                        v-for="carrier in carriers"
                        :value="carrier.id"
                        :key="carrier.id"
                      >
                        {{ carrier.name }}
                      </option>
                    </b-select>
                  </b-field>
                </ValidationProvider>
              </b-field>
              <b-field grouped group-multiline>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="teléfono"
                  rules="required|min:6"
                  class="column is-full"
                >
                  <b-field
                    :type="
                      errors.length > 0 || devicePhoneUnavailable
                        ? 'is-danger'
                        : ''
                    "
                    :message="errors[0]"
                    label="Teléfono"
                  >
                    <p class="control">
                      <span class="button is-static">{{ areaCode }}</span>
                    </p>
                    <b-input
                      v-model="simCard.phone_number"
                      placeholder="Teléfono"
                      expanded
                    >
                    </b-input>
                  </b-field>
                  <div v-if="devicePhoneUnavailable" class="help is-danger">
                    Teléfono ya registrado. Ingrese otro.
                  </div>
                </ValidationProvider>
                <!-- <ValidationProvider
                  v-slot="{ errors }"
                  name="status"
                  rules="required"
                  class="column is-full"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger' : ''"
                    :message="errors[0]"
                    label="Estado"
                  >
                    <b-select
                      v-model="simCard.sim_card_status_id"
                      placeholder="Seleccione un Estado"
                      expanded
                    >
                      <option
                        v-for="status in simCardStatus"
                        :value="status.id"
                        :key="status.id"
                      >
                        {{ status.description }}
                      </option>
                    </b-select>
                  </b-field>
                </ValidationProvider> -->
              </b-field>
              <b-field grouped group-multiline>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="saldo"
                  rules="numeric"
                  class="column is-full"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger' : ''"
                    :message="errors[0]"
                    label="Saldo Inicial"
                  >
                    <b-input
                      v-model="simCard.balance"
                      type="number"
                      placeholder="Saldo"
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
              </b-field>
            </section>
            <footer class="modal-card-foot">
              <b-button type="is-light" @click="closeSimcardModal">
                    Cancelar
                  </b-button>
              <b-button
                type="is-link"
                native-type="submit"
                :disabled="devicePhoneUnavailable"
              >
                Guardar
              </b-button>
            </footer>
          </div>
        </form>
      </ValidationObserver>
    </template>
  </b-modal>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  data() {
    return {
      simCard: {
        country_id: null,
        carrier_id: null,
        phone_number: null,
        sim_card_status_id: null,
        device_id: null,
        tenant_id: null,
        balance: null,
        sms: null,
      },
      phoneAlreadyExists: false,
      originalSimCard: null,
      countries: [],
      carriers: [],
      simCardStatus: [],
      countryName: null,
      countrySelected: null,
      isComponentModalActive: false,
    };
  },
  computed: {
    devicePhoneUnavailable() {
      return this.phoneAlreadyExists;
    },
    selectedCarrier() {
      return this.carriers && this.carriers.length
        ? this.carriers.find((x) => x.id === this.simCard.carrier_id)
        : null;
    },
    areaCode() {
      return this.countrySelected ? this.countrySelected.code_number : "+000";
    },
  },
  watch: {
    "simCard.phone_number": {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.simCard.phone_number = this.phoneFormatting(val);
          this.verifyDevicePhone();
        }
      },
    },
    countrySelected(value) {
      if (value) {
        this.simCard.country_id = value.id;
        this.loadCarriers();
      }
    },
    countryName(value) {
      if (!value) {
        this.countrySelected = null;
        this.simCard.carrier_id = null;
        this.simCard.country_id = null;
      }
    },
  },
  async mounted() {
    await Promise.all([this.loadCountries(), this.loadSimCardsStatus()]);
  },
  methods: {
    searchCountry: _.debounce(function () {
      this.loadCountries();
    }, 400),
    reset() {
      this.simCard = {
        country_id: null,
        carrier_id: null,
        phone_number: null,
        sim_card_status_id: null,
        device_id: null,
        tenant_id: null,
        balance: null,
        sms: null,
      };

      this.phoneAlreadyExists = false;
      this.originalSimCard = null;
      this.countryName = null;
      this.countrySelected = null;
      this.$refs.formObserver.reset();
    },
    async loadCarriers() {
      const carriersData = await this.$store.dispatch("carriers/getCarriers", {
        country_id: this.countrySelected.id,
      });
      this.carriers = carriersData.data;
    },
    async loadSimCardsStatus() {
      const response = await this.$store.dispatch(
        "simCardStatus/getAllStatus",
        {}
      );
      this.simCardStatus = response.data;
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch(
        "countries/getCountries",
        {
          name:
            !this.countryName || this.countryName === ""
              ? null
              : `%${this.countryName}%`,
        }
      );
      this.countries = countriesData.data;
    },
    verifyDevicePhone: _.debounce(async function () {
      if (this.simCard.phone_number && this.simCard.phone_number !== '') {
        const response = await this.$store.dispatch("simCards/getSimCards", {
          query: {
            phone_number: `${
              this.areaCode
            }${this.simCard.phone_number.replaceAll(" ", "")}`,
          },
        });
        const simCards = response.data;
        if (simCards.length) {
          this.phoneAlreadyExists = true;
        } else {
          this.phoneAlreadyExists = false;
        }
      }
    }, 700),
    async save() {
      const simCardAux = {
        ...this.simCard,
        phone_number: `${this.areaCode}${this.simCard.phone_number.replaceAll(
          " ",
          ""
        )}`,
        balance: parseFloat(this.simCard.balance),
      };
      try {
        const { data } = await this.$store.dispatch(
          "simCards/createSimCard",
          simCardAux
        );
        if (data?.id) {
          this.notify({
            message: "Creación exitosa",
            type: "is-success",
          });
          
          this.$emit('created', {
            ...data,
            country: this.countrySelected
          });
          this.closeSimcardModal();
        } else {
          this.notify({
            message: "creación fallida",
            type: "is-danger",
          });
        }
      } catch (e) {
        console.log(e);
        this.notify({
          message: "creación fallida",
          type: "is-danger",
        });
      }
    },
    openSimcardModal(){
      this.isComponentModalActive = true;
    },
    closeSimcardModal(){
      this.reset();
      this.$nextTick(()=>{
        this.isComponentModalActive = false;
      })
      
    }
  },
};
</script>

<style scoped>
.form-section {
  background-color: #f8f8f8;
  border-radius: 10px;
}
</style>