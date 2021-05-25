<template>
  <b-field expanded class="mr-0">
    <b-autocomplete
      placeholder="País"
      autocomplete="off"
      field="name"
      :open-on-focus="true"
      :clearable="false"
      :data="countries"
      v-model="countryName"
      @typing="searchCountry"
      @select="(option) => (countrySelected = option)"
      :disabled="disabled"
      expanded
    >
    </b-autocomplete>
    <p class="control">
      <span class="button is-static">{{ areaCode }}</span>
    </p>
    <b-autocomplete
      placeholder="Seleccione teléfono"
      autocomplete="off"
      field="phone_number"
      :open-on-focus="true"
      :clearable="false"
      :data="simCards"
      :custom-formatter="formatterPhone"
      v-model="phoneNumber"
      @typing="searchPhones"
      @select="(option) => (simcardSelected = option)"
      :disabled="disabled"
      expanded
    >
      <template #header>
        <a @click="openSimcardModal">
          <span> Agregar uno nuevo... </span>
        </a>
      </template>
      <template slot-scope="props">
        <span>{{ props.option.phone_number }}</span>
        <span class="is-pulled-right" v-if="props.option.carrier">{{
          props.option.carrier.name
        }}</span>
      </template>
    </b-autocomplete>
    <create-simcard-modal ref="modal-simcard" @created="onCreatedSimCard"/>
  </b-field>
</template>

<script>
import CreateSimcardForm from "./create_form.vue";
import CreateSimcardModal from './create_modal.vue';
export default {
  components: { CreateSimcardForm, CreateSimcardModal },
  props: {
    simcard: {
      type: Object,
      default: null,
    },
    disabled: {
      type: Boolean,
      default: false
    },
  },
  data() {
    return {
      isComponentModalActive: false,
      countries: [],
      countrySelected: null,
      simCards: [],
      simcardSelected: null,
      phoneNumber: null,
      countryName: null,
    };
  },
  computed: {
    areaCode() {
      return this.countrySelected ? this.countrySelected.code_number : "+000";
    },
  },
  watch: {
    countryName(newValue, oldValue) {
      if (!newValue) {
        this.unselectSimCard();
      }
    },
    simcardSelected(newValue, oldValue) {
      if (
        newValue?.id !== oldValue?.id ||
        (newValue?.id && !oldValue) ||
        (!newValue && oldValue)
      ) {
        this.$emit("update:simcard", this.simcardSelected);
      }
    },
    simcard(newValue, oldValue) {
      if (newValue) {
        this.setSimCard(newValue);
      } else {
        this.countryName = null;
      }
    },
  },
  methods: {
    unselectSimCard() {
      this.countrySelected = null;
      this.simcardSelected = null;
      this.phoneNumber = null;
    },
    formatterPhone(selection) {
      return selection.phone_number.replace(`${this.areaCode}`, "");
    },
    searchPhones: _.debounce(async function (searchText) {
      let formattedText = null;
      if (searchText && searchText !== "") {
        formattedText = `%${this.areaCode}${searchText.replaceAll(" ", "")}%`;
      }
      try {
        const { data } = await this.$store.dispatch("simCards/getSimCards", {
          query: {
            phone_number: formattedText,
            status_code: "enabled",
            include: ["carrier"],
          },
        });
        this.simCards = data;
      } catch (error) {
        console.log(error);
      }
    }, 400),
    searchCountry: _.debounce(async function (searchText) {
      if (searchText || searchText !== "") {
        const formattedText = `%${searchText}%`;
        try {
          const { data } = await this.$store.dispatch(
            "countries/getCountries",
            {
              name: formattedText,
            }
          );
          this.countries = data;
        } catch (error) {
          console.log(error);
        }
      } else {
        this.unselectSimCard();
      }
    }, 400),
    async getSimCard(id) {
      try {
        const { data } = await this.$store.dispatch("simCards/getSimCard", id);
        return data;
      } catch (error) {
        console.log(error);
      }
      return null;
    },
    async setSimCard(data) {
      if (data) {
        const simcard = await this.getSimCard(data.id);
        this.simcardSelected = simcard;
        this.countrySelected = simcard?.country;
        this.countryName = simcard?.country?.name;
        this.phoneNumber = simcard?.phone_number?.replace(`${this.areaCode}`, "");
      }
    },
    onCreatedSimCard(data){
      this.setSimCard(data);
    },
    openSimcardModal(){
      this.$refs['modal-simcard'].openSimcardModal();
    },
    closeSimcardModal(){
      this.isComponentModalActive = false;
    }
  },
  mounted() {
    this.setSimCard(this.simcard);
  },
};
</script>