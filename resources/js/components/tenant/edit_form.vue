<template>
  <div class="columns">
    <div class="column">
      <ValidationObserver v-slot="{ handleSubmit, invalid }">
        <form @submit.prevent="handleSubmit(save)">
          <ValidationProvider
            v-slot="{ errors }"
            name="nombre"
            rules="required"
            class="column is-full"
          >
            <b-field
              :type="{ 'is-danger': errors.length }"
              :message="[errors.length ? errors[0] : '']"
              label="Nombre"
            >
              <b-input
                v-model="tenant.name"
                placeholder="Nombre de la organización"
                :disabled="!canEdit"
                expanded
              >
              </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="código"
            rules="required"
            class="column is-full"
          >
            <b-field
              :type="{ 'is-danger': errors.length }"
              :message="[
                errors[0],
                !errors.length && !isValidCode && !isValidatingCode ? 'El código ya se encuentra registrado' : ''
              ]"
              label="Código"
            >
              <div :class="['control', {'is-loading': isValidatingCode}]">
                <input
                  :class="['input is-expanded', {'is-danger': (errors.length || !isValidCode) && !isValidatingCode }]"
                  v-model="tenant.code"
                  placeholder="Código que identifique a la organización"
                  :disabled="!canEdit"
                />
              </div>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="país"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="País"
            >
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
                :disabled="!canEdit"
                expanded
              >
              </b-autocomplete>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="dirección"
            rules=""
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Dirección"
            >
              <input type="hidden" v-model="tenant.address" />
              <gmap-autocomplete
                ref="autocomplete"
                @place_changed="changePlace"
                :value="tenant.address"
                :class="['input', { 'is-danger': errors[0] }]"
                :disabled="!canEdit"
              ></gmap-autocomplete>
            </b-field>
          </ValidationProvider>
          <b-field class="column is-full-mobile">
            <GmapMap
              :center="
                tenant && tenant.location
                  ? tenant.location
                  : { lat: -38.7410298, lng: -72.6269321 }
              "
              :zoom="15"
              map-type-id="roadmap"
              style="width: 100%; height: 250px"
            >
              <GmapMarker
                v-if="tenant && tenant.location"
                key="marker"
                :draggable="canEdit"
                @dragend="manualPositionChange"
                :position="tenant.location"
              />
            </GmapMap>
          </b-field>
          <b-field class="column is-full-mobile" v-if="canEdit">
            <p class="control">
              <b-button type="is-light" @click="cancel"> Cancelar </b-button>
              <b-button type="is-link" native-type="submit" :disabled="invalid">
                Guardar
              </b-button>
            </p>
          </b-field>
        </form>
      </ValidationObserver>
    </div>
    <div class="column">
      <div class="columns is-vcentered">
        <div class="column" style="text-align: right">
          <h3 class="title is-3 has-text-weight-light">
            {{ tenant.name }}
          </h3>
          <h4 class="title is-4 is-capitalized" title-weight="weight-normal">
            Organización
          </h4>
        </div>
        <div class="column is-narrow px-5">
          <div class="is-user-avatar">
            <img
              v-if="tenant.logo_url"
              :src="`${tenant.logo_url}?rand=${rand}`"
              :alt="$store.state.userName"
            />
            <img
              v-else
              src="../../../assets/placeholder-building.png"
              :alt="$store.state.userName"
            />
            <b-button class="photo-button" @click="showPhotoModal"
              ><b-icon icon="camera-plus"
            /></b-button>
          </div>
        </div>
      </div>
      <b-modal v-model="isModalActive" has-modal-card>
        <div class="modal-card">
          <div class="modal-card-body">
            <div class="content" style="text-align: center">
              <div>
                <b-upload
                  ref="file"
                  v-model="newPhoto"
                  accept="image/png, image/jpeg"
                  drag-drop
                >
                  <section class="section">
                    <div class="content has-text-centered">
                      <p>
                        <b-icon icon="upload" size="is-large"> </b-icon>
                      </p>
                      <p>Suelta tus archivos aquí o has click para subirlos.</p>
                    </div>
                  </section>
                </b-upload>
                <p class="mt-4">
                  <small
                    >Tamaño máximo: 1024kb. Dimensiones máximas:
                    1080*1080</small
                  >
                </p>
                <b-loading
                  v-if="isPhotoUploading"
                  :is-full-page="true"
                  v-model="isPhotoUploading"
                  :can-cancel="true"
                ></b-loading>
              </div>
            </div>
          </div>
        </div>
      </b-modal>
    </div>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    tenantId: {
      type: String,
      require: true,
    },
    canEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      searchDni: null,
      tenant: {
        name: null,
        code: null,
        dni: null,
        address: null,
        contact: null,
        country_id: null,
        location: null,
        logo_url: null,
      },
      originalTenant: null,
      countries: [],
      isModalActive: false,
      newPhoto: null,
      isPhotoUploading: false,
      rand: null,
      countryName: null,
      countrySelected: null,
      isValidatingCode: false,
      isValidCode: true
    };
  },
  watch: {
    countryName(newValue, oldValue) {
      if (!newValue) {
        this.unselectedCountry();
      }
    },
    countrySelected(newValue, oldValue) {
      if (
        newValue?.id !== oldValue?.id ||
        (newValue?.id && !oldValue) ||
        (!newValue && oldValue)
      ) {
        this.tenant.country = this.countrySelected;
        this.tenant.country_id = this.countrySelected?.id || null;
      }
    },
    newPhoto: {
      handler(val) {
        if (val) {
          this.uploadPhoto();
        }
      },
    },
    'tenant.code': {
      handler(value) {
        if(value) {
          this.tenant.code = JSON.parse(JSON.stringify(this.formatTenantCode(value)));
          this.validateCode();
        }
      }
    }
  },
  async mounted() {
    this.loadTeant();
    await Promise.all([this.loadCountries()]);
  },
  methods: {
    formatTenantCode(value) {
      if(!value || value == '') return value;
      return value.toLowerCase()
                      .replace(/\s+/, '-')
                      .trim()
                      .replace(/[^a-z-0-9]/g, '');
    },
    validateCode: _.debounce(async function () {
      const { code } = this.tenant;
      if ( code ) {
        this.isValidatingCode = true;
        const { data } = await this.$store.dispatch(
          "tenants/getTenants",
          {
            code: code, 
          }
        );

        const [ firstResult ] = data;

        if(firstResult && firstResult.id === this.tenant.id || !firstResult){
          this.isValidCode = true;
        } else {
          this.isValidCode = false;
        }
        
       
        this.isValidatingCode = false;
      }
    }, 700),
    async uploadPhoto() {
      this.isPhotoUploading = true;
      try {
        const data = new FormData();
        data.append("photo", this.newPhoto);
        const response = await this.$store.dispatch("tenants/uploadPhoto", {
          id: this.tenant.id,
          data,
        });
        if (response.errors) {
          this.isPhotoUploading = false;
          this.$buefy.dialog.alert({
            icon: "close-circle",
            hasIcon: true,
            message:
              response.errors.photo[0] === "validation.dimensions"
                ? "Las dimensiones de la imagen exceden los límites de 1080x1080 pixeles."
                : "El archivo debe pesar menos de 1024kb.",
            confirmText: "Aceptar",
            type: "is-danger",
          });
        } else {
          await this.loadTeant();
          this.updateStore();
          this.rand = Date.now();
          this.isPhotoUploading = false;
          this.isModalActive = false;
        }
      } catch (error) {
        console.log(error);
      }
    },
    manualPositionChange(data) {
      let lat = data.latLng.lat();
      let lng = data.latLng.lng();
      this.changePosition({ lat, lng });
    },
    async changePosition(location) {
      console.log(this.canEdit);
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location }, (results, status) => {
        if (results.length) {
          let data = results[0];
          this.tenant = {
            ...this.tenant,
            address: data.formatted_address,
            location: location,
          };
        }
      });
    },
    changePlace(data) {
      let lat = data.geometry.location.lat();
      let lng = data.geometry.location.lng();
      this.tenant = {
        ...this.tenant,
        address: data.formatted_address,
        location: {
          lat,
          lng,
        },
      };
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
        this.unselectedCountry();
      }
    }, 400),
    unselectedCountry() {
      this.countrySelected = null;
      this.tenant.country_id = null;
      this.tenant.country = null;
    },
    cancel() {
      this.tenant = { ...this.originalTenant };
      const { country } = this.tenant;
      this.countrySelected = country;
      this.countryName = country.name;
      this.$emit("update:can-edit", false);
    },
    async save() {
      try {
        const response = await this.$store.dispatch("tenants/updateTenant", {
          ...this.tenant,
          location: this.tenant.location
            ? JSON.stringify(this.tenant.location)
            : null,
        });
        this.updateStore(response.data);
        this.notify({
          message: "Actualización Exitosa",
          type: "is-success",
        });

        await this.loadTeant();
        this.updateStore();
        this.$emit("update:can-edit", false);
      } catch (e) {
        console.log(e);
        this.notify({
          message: "Actualización Fallida",
          type: "is-danger",
        });
      }
    },
    showPhotoModal() {
      this.isModalActive = true;
    },
    updateStore() {
      this.$store.commit("setCurrentTenant", this.tenant);
    },
    async loadTeant() {
      const { data } = await this.$store.dispatch(
        "tenants/getTenant",
        this.tenantId
      );
      this.tenant = data;
      this.originalTenant = { ...data };
      if (this.tenant?.country) {
        const { country } = this.tenant;
        this.countrySelected = country;
        this.countryName = country.name;
      } else {
        this.countrySelected = null;
      }
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch(
        "countries/getCountries",
        {}
      );
      this.countries = countriesData.data;
    },
  },
};
</script>

<style scoped>
.is-user-avatar,
img {
  height: 175px;
  width: 175px;
  position: relative;
  margin: auto;
}

.photo-button {
  position: absolute;
  bottom: 2px;
  right: 5px;
  border-radius: 50%;
  height: 30%;
  width: 30%;
  background-color: #0085cf;
  color: white;
  padding: 2px;
  text-align: center;
  justify-content: center;
  border: 7px solid white;
}

.photo-button > span {
  margin-top: 25%;
}
</style>
