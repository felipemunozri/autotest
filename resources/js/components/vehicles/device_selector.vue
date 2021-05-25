<template>
  <section>
    <b-tabs v-model="activeTab">
      <b-tab-item label="Buscar por número de serie">
        <b-field expanded class="mr-0">
          <b-autocomplete
            placeholder="Seleccione número de serie"
            autocomplete="off"
            field="serial_number"
            :open-on-focus="true"
            :clearable="false"
            :data="devices"
            v-model="serialNumber"
            @typing="searchSerialNumber"
            @select="(option) => (deviceSelected = option)"
            :disabled="disabled"
            expanded
          >
            <template slot-scope="props">
              <span>{{ props.option.serial_number }}</span>
              <span class="is-pulled-right" v-if="props.option.carrier">{{
                props.option.carrier.name
              }}</span>
            </template>
          </b-autocomplete>
        </b-field>
      </b-tab-item>
      <b-tab-item label="Buscar por número de teléfono">
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
            :data="devices"
            :custom-formatter="formatterPhone"
            v-model="phoneNumber"
            @typing="searchPhones"
            @select="(option) => (deviceSelected = option)"
            :disabled="disabled"
            expanded
          >
            <template v-if="props.option.sim_card" slot-scope="props">
              <span>{{ props.option.sim_card.phone_number }}</span>
              <span class="is-pulled-right" v-if="props.option.sim_card.carrier">{{
                props.option.sim_card.carrier.name
              }}</span>
            </template>
          </b-autocomplete>
        </b-field>
      </b-tab-item>
    </b-tabs>
  </section>
</template>

<script>

export default {
  props: {
    device: {
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
      activeTab: 0,
      countries: [],
      countrySelected: null,
      devices: [],
      deviceSelected: null,
      serialNumber: null,
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
        this.unselectDevice();
      }
    },
    deviceSelected(newValue, oldValue) {
      if (
        newValue?.id !== oldValue?.id ||
        (newValue?.id && !oldValue) ||
        (!newValue && oldValue)
      ) {
        this.$emit("update:device", this.deviceSelected);
      }
    },
    device(newValue, oldValue) {
      if (newValue) {
        this.setDevice(newValue);
      } else {
        this.countryName = null;
      }
    },
  },
  methods: {
    unselectDevice() {
      this.deviceSelected = null;
      this.serialNumber = null;
    },
    formatterPhone(selection) {
      return selection?.sim_card?.phone_number?.replace(`${this.areaCode}`, "");
    },
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
        this.unselectDevice();
      }
    }, 400),
    searchPhones: _.debounce(async function (searchText) {
      let formattedText = null;
      if (searchText && searchText !== "") {
        formattedText = `%${this.areaCode}${searchText.replaceAll(" ", "")}%`;
      }
      try {
        const { data } = await this.$store.dispatch("devices/getDevices", {
          query: {
            phone_number: formattedText,
            status_code: "enabled",
            include: ["simCard.carrier"],
          },
        });
        this.devices = data;
      } catch (error) {
        console.log(error);
      }
    }, 400),
    searchSerialNumber: _.debounce(async function (searchText) {
      let formattedText = null;
      if (searchText && searchText !== "") {
        formattedText = `%${searchText.replaceAll(" ", "")}%`;
      }
      try {
        const { data } = await this.$store.dispatch("devices/getDevices", {
          query: {
            serial_number: formattedText,
            status_code: "enabled",
            include: ["simCard"],
          },
        });
        this.devices = data;
      } catch (error) {
        console.log(error);
      }
    }, 400),
    async getDevice(id) {
      try {
        const { data } = await this.$store.dispatch("devices/getDevice", { id });
        return data;
      } catch (error) {
        console.log(error);
      }
      return null;
    },
    async setDevice(data) {
      if (data) {
        const device = await this.getDevice(data.id);
        this.deviceSelected = device;
        this.serialNumber = device?.serial_number;
      }
    },
  },
  mounted() {
    this.setDevice(this.device);
  },
};
</script>