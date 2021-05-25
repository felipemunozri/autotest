<template>
  <div>
    <ValidationObserver v-slot="{ handleSubmit}">
      <form @submit.prevent="handleSubmit(save)">
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="teléfono"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="
                errors.length > 0 ? 'is-danger' : ''
              "
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
                @select="option => (countrySelected = option)"
                :clearable="true"
              >
              </b-autocomplete>
              </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="carrier"
            rules="required"
            class="column is-full-mobile"
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
            class="column is-full-mobile"
          >
            <b-field
              :type="
                errors.length > 0 || devicePhoneUnavailable ? 'is-danger' : ''
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
          <ValidationProvider
            v-slot="{ errors }"
            name="status"
            rules="required"
            class="column is-full-mobile"
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
          </ValidationProvider>
        </b-field>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="saldo"
            rules="numeric"
            class="column is-half is-full-mobile"
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
        <b-field class="column is-full-mobile">
          <p class="control">
            <b-button type="is-light" @click="cancel"> Cancelar </b-button>
            <b-button type="is-link" native-type="submit" :disabled="devicePhoneUnavailable">
              Guardar
            </b-button>
          </p>
        </b-field>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    simcardId: {
      type: String,
      require: true,
    },
  },
  data() {
    return {
      simCard: {
        id: null,
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
      countrySelected: null
    };
  },
  computed: {
    devicePhoneUnavailable() {
      return this.phoneAlreadyExists && this.simCard.phone_number != this.phoneFormatting(this.originalSimCard.phone_number);
    },
    selectedCarrier(){
      return this.carriers && this.carriers.length ? this.carriers.find(x => x.id === this.simCard.carrier_id) : null;
    },
    areaCode (){
      return this.countrySelected  ? this.countrySelected.code_number : null ;
    }
  },
  watch: {
    'simCard.phone_number': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.simCard.phone_number = this.phoneFormatting(val);
          this.verifyDevicePhone();
        }
      },
    },
    countrySelected(value) {
      if ( value ) {
        this.simCard.country_id = value.id;
        this.loadCarriers()
      }
    },
    countryName(value){
      if(!value){
        this.countrySelected = null;
        this.simCard.carrier_id = null;
        this.simCard.country_id = null;
      }
    }
  },
  async mounted() {
    await Promise.all([
      this.fetchData(),
      this.loadCountries(),
      this.loadSimCardsStatus()
    ])
  },
  methods: {
    cancel(){
      this.redirect();
    },
    searchCountry: _.debounce(function () {
      this.loadCountries()
    }, 400),
    async fetchData () {
      if (this.simcardId) {
        const { data } = await this.$store.dispatch("simCards/getSimCard", 
          this.simcardId
        );

        const phoneNumber = data.phone_number ? data.phone_number.slice(3) : data.phone_number;
        
        this.simCard = JSON.parse(JSON.stringify(data));
        this.simCard.phone_number = phoneNumber;
        this.originalSimCard = JSON.parse(JSON.stringify(data));
        this.originalSimCard.phone_number = phoneNumber;
        const { country } = this.simCard;
        this.countrySelected = country;
        this.countryName = country?.name;
      }
    },
    reset() {
      this.simCard = {
        id: null,
        country_id: null,
        carrier_id: null,
        phone_number: null,
        sim_card_status_id: null,
        device_id: null,
        tenant_id: null,
        balance: null,
        sms: null,
      };
    },
    async loadCarriers() {
      const carriersData = await this.$store.dispatch(
        "carriers/getCarriers",
        {
          country_id: this.countrySelected.id
        }
      );
      this.carriers = carriersData.data;
    },
    async loadSimCardsStatus() {
      const response = await this.$store.dispatch(
        "simCardStatus/getAllStatus",
        {
        }
      );
      this.simCardStatus = response.data;
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch(
        "countries/getCountries",
        {
          name: !this.countryName || this.countryName === '' ?  null : `%${this.countryName}%`,
        }
      );
      this.countries = countriesData.data;
    },
    verifyDevicePhone: _.debounce(async function() {
      if (this.simCard.phone_number !== '') {
        const response = await this.$store.dispatch('simCards/getSimCards', {
          query: {
            phone_number: `${this.areaCode}${this.simCard.phone_number.replaceAll(' ', '')}`,
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
        phone_number: `${this.areaCode}${this.simCard.phone_number.replaceAll(' ', '')}`,
        balance: parseFloat(this.simCard.balance),
      };
      try {
        const { data } = await this.$store.dispatch(
          "simCards/updateSimCard",
          simCardAux
        );
        if(data?.id) {
          this.notify({
            message: 'Actualización exitosa',
            type: 'is-success'
          });
          this.redirect();
        } else {
          this.notify({
            message: 'Actualización fallida',
            type: 'is-danger'
          });
        }
      } catch (e) {
        console.log(e)
        this.notify({
          message: 'Actualización fallida',
          type: 'is-danger'
        });
      }
    },
    redirect() {
      this.$inertia.visit(route("simcard.index"));
    },
  },
};
</script>

<style scoped>
.form-section {
  background-color: #f8f8f8;
  border-radius: 10px;
}
</style>