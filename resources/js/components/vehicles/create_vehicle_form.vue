<template>
  <div>
    <ValidationObserver v-slot="{ handleSubmit, invalid }">
      <form @submit.prevent="handleSubmit(sendRequest)">
        <div class="form-section columns is-vcentered p-3 mt-3" columns>
          <div class="column is-11">
            <h6 class="title is-6">Información de Dueño</h6>
            <div class="columns is-vcentered">
              <ValidationProvider
                v-slot="{ errors }"
                name="run"
                rules="required|run"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="RUN"
                >
                  <b-input
                    v-model="beneficiary.dni"
                    @blur="verifyDni"
                    placeholder="RUN"
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="nombre"
                rules="required"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Nombre"
                >
                  <b-input
                    v-model="beneficiary.name"
                    :disabled="beneficiaryFound"
                    placeholder="Nombre"
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="apellidos"
                rules="required"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Apellidos"
                >
                  <b-input
                    v-model="beneficiary.lastname"
                    :disabled="beneficiaryFound"
                    placeholder="Apellidos"
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
            </div>
            <div class="columns is-vcentered">
              <ValidationProvider
                v-slot="{ errors }"
                name="correo"
                rules="required|email"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Correo"
                >
                  <b-input
                    v-model="beneficiary.email"
                    :disabled="beneficiaryFound"
                    placeholder="Correo"
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="teléfono"
                rules="required|min:6"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Teléfono"
                >
                  <p class="control">
                    <span class="button is-static">+56 9</span>
                  </p>
                  <b-input
                    v-model="beneficiary.phoneNumber"
                    :disabled="beneficiaryFound"
                    placeholder="Teléfono"
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
            </div>
            <div
              v-for="(contact, index) in contacts"
              class="form-section-contact p-5 mt-3"
              :key="index"
            >
              <div class="columns is-vcentered">
                <div class="column">
                  <h6 class="title is-6">{{ `Contacto #${index + 1}` }}</h6>
                </div>
                <div class="column is-2">
                  <b-button
                    size="is-small"
                    class="mt-3"
                    icon-left="minus-thick"
                    @click="removeContact(index)"
                  >
                    Quitar Contacto
                  </b-button>
                </div>
              </div>
              <div class="columns is-vcentered">
                <ValidationProvider
                  v-slot="{ errors }"
                  name="run"
                  rules="required|run"
                  class="column is-4"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]"
                    label="RUN"
                  >
                    <b-input
                      v-model="contact.dni"
                      placeholder="RUN"
                      @input="formatRut(index)"
                      @blur="verifyContactDni(index)"
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="nombre"
                  rules="required"
                  class="column is-4"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]"
                    label="Nombre"
                  >
                    <b-input
                      v-model="contact.name"
                      :disabled="contact.id"
                      placeholder="Nombre"
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="apellidos"
                  rules="required"
                  class="column is-4"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]"
                    label="Apellidos"
                  >
                    <b-input
                      v-model="contact.lastname"
                      :disabled="contact.id"
                      placeholder="Apellidos"
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
              </div>
              <div class="columns is-vcentered">
                <ValidationProvider
                  v-slot="{ errors }"
                  name="correo"
                  rules="required|email"
                  class="column is-4"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]"
                    label="Correo"
                  >
                    <b-input
                      v-model="contact.email"
                      :disabled="contact.id"
                      placeholder="Correo"
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
                <ValidationProvider
                  v-slot="{ errors }"
                  name="teléfono"
                  rules="required|min:6"
                  class="column is-4"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]"
                    label="Teléfono"
                  >
                    <p class="control">
                      <span class="button is-static">+56 9</span>
                    </p>
                    <b-input
                      v-model="contact.phoneNumber"
                      :disabled="contact.id"
                      placeholder="Teléfono"
                    >
                    </b-input>
                  </b-field>
                </ValidationProvider>
              </div>
            </div>
            <div class="columns is-vcentered">
              <div class="column">
                <b-button
                  type="is-link"
                  size="is-small"
                  class="mt-3"
                  icon-left="plus-thick"
                  @click="addContact"
                >
                  Añadir Contacto
                </b-button>
              </div>
            </div>
          </div>
          <div class="column is-1">
            <b-icon icon="account" size="is-large" />
          </div>
        </div>
        <div class="form-section columns is-vcentered p-3 mt-3" columns>
          <div class="column is-11">
            <h6 class="title is-6">Información de Vehículo</h6>
            <div class="columns is-vcentered">
              <ValidationProvider
                v-slot="{ errors }"
                name="patente"
                rules="required|min:6|max:6"
                class="column is-4"
              >
                <b-field
                  :type="(errors.length > 0 || plateNumberUnavailable) ? 'is-danger':''"
                  :message="errors[0]"
                  label="Patente"
                >
                  <b-input v-model="vehicle.plateNumber" placeholder="Patente">
                  </b-input>
                  <div v-if="plateNumberUnavailable" class="help is-danger">
                    Patente ya registrada. Ingrese otra.
                  </div>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="modelo"
                rules="required"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Modelo"
                >
                  <b-input v-model="vehicle.model" placeholder="Modelo">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="marca"
                rules="required"
                class="column is-4"
                :disabled="!carBrands.length"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Marca"
                >
                  <b-select
                    v-model="vehicle.brandId"
                    placeholder="Seleccione una Marca"
                    expanded
                  >
                    <option
                      v-for="brand in carBrands"
                      :value="brand.id"
                      :key="brand.id"
                    >
                      {{ brand.name }}
                    </option>
                  </b-select>
                </b-field>
              </ValidationProvider>
            </div>
            <div class="columns is-vcentered">
              <ValidationProvider
                v-slot="{ errors }"
                name="año"
                rules="required|numeric|min:4|max:4"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Año"
                >
                  <b-input v-model="vehicle.ownerAdquisitionDate" type="number" placeholder="Año">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="color"
                rules="required"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Color"
                >
                  <b-input v-model="vehicle.color" placeholder="Color">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="año"
                rules="required|numeric"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Cilindraje (cm3)"
                >
                  <b-input
                    v-model="vehicle.engineCapacity"
                    type="number"
                    placeholder="Cilindraje"
                  >
                  </b-input>
                </b-field>
              </ValidationProvider>
            </div>
            <div class="columns is-vcentered">
              <ValidationProvider
                v-slot="{ errors }"
                name="número de chasís"
                rules="required"
                class="column is-4"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Número de chasís"
                >
                  <b-input v-model="vehicle.chassisNumber" placeholder="Número de chasís">
                  </b-input>
                </b-field>
              </ValidationProvider>
              <ValidationProvider
                v-slot="{ errors }"
                name="color"
                rules="required"
                class="column is-4"
                :disabled="!fuelTypes.length"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Tipo de Combustible"
                >
                  <b-select
                    v-model="vehicle.fuelTypeId"
                    placeholder="Seleccione una Marca"
                    expanded
                  >
                    <option
                      v-for="fuelType in fuelTypes"
                      :value="fuelType.id"
                      :key="fuelType.id"
                    >
                      {{ fuelType.name }}
                    </option>
                  </b-select>
                </b-field>
              </ValidationProvider>
            </div>
          </div>
          <div class="column is-1">
            <b-icon icon="car" size="is-large" />
          </div>
        </div>
        <div class="form-section columns is-vcentered p-3 mt-3" columns>
          <div class="column is-11">
            <h6 class="title is-6">Información de Dispositivo</h6>
            <div class="columns is-vcentered">
              <b-field class="column is-3">
                <b-checkbox v-model="includeDevice"
                type="is-info">
                  ¿Asociar a dispositivo? - {{ includeDevice ? 'Si' : 'No' }}
                </b-checkbox>
              </b-field>
              <ValidationProvider
                v-if="includeDevice"
                v-slot="{ errors }"
                name="Número de Serie"
                rules="required"
                class="column"
              >
                <b-field
                  :type="errors.length > 0 ? 'is-danger':''"
                  :message="errors[0]"
                  label="Número de Serie"
                >
                  <device-selector :device.sync="deviceSelected" />
                  <input type="hidden" :value="deviceSelected" />
                </b-field>
              </ValidationProvider>
            </div>
          </div>
          <div class="column is-1">
            <b-icon icon="cellphone" size="is-large" />
          </div>
        </div>
        <div class="buttons">
          <b-button type="is-light" @click="cancel">
            Cancelar
          </b-button>
          <b-button
              type="is-link"
              @click.prevent="save"
              :disabled="invalid || plateNumberUnavailable || devicePhoneUnavailable"
            >
              Guardar
            </b-button>
        </div>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { Formatters } from '@/mixins/formatters';
import DeviceSelector from "@/components/vehicles/device_selector";

export default {
  mixins: [Formatters()],
  components: { DeviceSelector },
  data() {
    return {
      beneficiaryFound: false,
      beneficiaryId: '',
      plateNumberUnavailable: false,
      devicePhoneUnavailable: false,
      includeDevice: false,
      vehicle: {
        plateNumber: '',
        model: '',
        brandId: '',
        ownerAdquisitionDate: '',
        color: '',
        engineCapacity: '',
        chassisNumber: '',
        fuelTypeId: '',
      },
      beneficiary: {
        dni: '',
        name: '',
        lastname: '',
        email: '',
        phoneNumber: '',
      },
      device: {
        serialNumber: '',
        phoneNumber: '',
        brandId: '',
        carrierId: '',
        password: '',
        balance: '',
      },
      contacts: [],
      carriers: [],
      countries: [],
      carBrands: [],
      fuelTypes: [],
      deviceModels: [],
      deviceSelected: null,
    }
  },
  watch: {
    'vehicle.plateNumber': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.vehicle.plateNumber = val.toUpperCase();
          this.verifyPlateNumber();
        }
      },
    },
    'beneficiary.dni': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.beneficiary.dni = this.runFormatting(val);
          // this.verifyDni();
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
    'device.phoneNumber': {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.device.phoneNumber = this.phoneFormatting(val);
          this.verifyDevicePhone();
        }
      },
    },
  },
  async mounted() {
    await Promise.all([
      this.loadCountries(),
      this.loadCarBrands(),
      this.loadFuelTypes(),
      this.loadDeviceModels(),
      this.loadCarriers(),
    ]);
  },
  methods: {
    formatRut(index) {
      this.contacts[index].dni = this.runFormatting(this.contacts[index].dni);
    },
    formatPhone(index) {
      this.contacts[index].phoneNumber = this.phoneFormatting(this.contacts[index].phoneNumber);
    },
    async verifyContactDni(index) {
      if (this.contacts[index].dni) {
        const response = await this.$store.dispatch('beneficiaries/getBeneficiaries', {
          query: {
            dni: this.contacts[index].dni.replaceAll('.', ''),
          },
        });
        const beneficiaries = response.data;
        if (beneficiaries.length) {
          this.contacts[index].id = beneficiaries[0].id;
          this.contacts[index].dni = this.runFormatting(beneficiaries[0].dni);
          this.contacts[index].name = beneficiaries[0].name;
          this.contacts[index].lastname = beneficiaries[0].lastname;
          this.contacts[index].email = beneficiaries[0].email;
          this.contacts[index].phoneNumber = beneficiaries[0].phone_number.slice(4);
        } else {
          this.contacts[index].name = '';
          this.contacts[index].lastname = '';
          this.contacts[index].email = '';
          this.contacts[index].phoneNumber = '';
        }
      } else {
        this.contacts[index].name = '';
        this.contacts[index].lastname = '';
        this.contacts[index].email = '';
        this.contacts[index].phoneNumber = '';
      }
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch('countries/getCountries', {});
      this.countries = countriesData.data;
    },
    async loadCarBrands() {
      const carBrandsData = await this.$store.dispatch('carBrands/getCarBrands', {});
      this.carBrands = carBrandsData.data;
    },
    async loadFuelTypes() {
      const fuelTypesData = await this.$store.dispatch('fuelTypes/getFuelTypes', {});
      this.fuelTypes = fuelTypesData.data;
    },
    async loadDeviceModels() {
      const deviceModelsData = await this.$store.dispatch('deviceModels/getDeviceModels', {});
      this.deviceModels = deviceModelsData.data;
    },
    async loadCarriers() {
      const carriersData = await this.$store.dispatch('carriers/getCarriers', {});
      this.carriers = carriersData.data;
    },
    addContact() {
      this.contacts.push({
        dni: '',
        name: '',
        lastname: '',
        email: '',
        phoneNumber: '',
      });
    },
    removeContact(index) {
      this.contacts = this.contacts.filter((contact, i) => i !== index);
    },
    async verifyDni() {
      if (this.beneficiary.dni !== '' && !this.beneficiaryFound) {
        const response = await this.$store.dispatch('beneficiaries/getBeneficiaries', {
          query: {
            dni: this.beneficiary.dni.replaceAll('.', ''),
          },
        });
        const beneficiaries = response.data;
        if (beneficiaries.length) {
          this.beneficiaryFound = true;
          this.beneficiaryId = beneficiaries[0].id;
          this.beneficiary.dni = this.runFormatting(beneficiaries[0].dni);
          this.beneficiary.name = beneficiaries[0].name;
          this.beneficiary.lastname = beneficiaries[0].lastname;
          this.beneficiary.email = beneficiaries[0].email;
          this.beneficiary.phoneNumber = beneficiaries[0].phone_number.slice(4);
        } else {
          this.beneficiaryFound = false;
          this.beneficiary.name = '';
          this.beneficiary.lastname = '';
          this.beneficiary.email = '';
          this.beneficiary.phoneNumber = '';
        }
      } else {
        this.beneficiaryFound = false;
        this.beneficiary.name = '';
        this.beneficiary.lastname = '';
        this.beneficiary.email = '';
        this.beneficiary.phoneNumber = '';
      }
    },
    verifyPlateNumber: _.debounce(async function() {
      if (this.vehicle.plateNumber !== '') {
        const response = await this.$store.dispatch('vehicles/getVehicles', {
          query: {
            plate_number: this.vehicle.plateNumber,
          },
        });
        const vehicles = response.data;
        if (vehicles.length) {
          this.plateNumberUnavailable = true;
        } else {
          this.plateNumberUnavailable = false;
        }
      }
    }, 700),
    verifyDevicePhone: _.debounce(async function() {
      if (this.device.phoneNumber !== '') {
        const response = await this.$store.dispatch('simCards/getSimCards', {
          query: {
            phone_number: `+569${this.device.phoneNumber.replaceAll(' ', '')}`,
          },
        });
        const simCards = response.data;
        if (simCards.length) {
          this.devicePhoneUnavailable = true;
        } else {
          this.devicePhoneUnavailable = false;
        }
      }
    }, 700),
    async save() {
      /* Crear vehículo */
      const vehicleAux = {
        country_id: this.countries[0].id,
        card_brand_id: this.vehicle.brandId,
        plate_number: this.vehicle.plateNumber,
        model: this.vehicle.model,
        year: this.vehicle.ownerAdquisitionDate,
        owner_acquisition_date: new Date(),
        color: this.vehicle.color,
        engine_capacity: this.vehicle.engineCapacity,
        chassis_number: this.vehicle.chassisNumber,
        fuel_type_id: this.vehicle.fuelTypeId,
        owner_dni: this.beneficiary.dni,
        owner_name: `${this.beneficiary.name} ${this.beneficiary.lastname}`,
      }
      const createdVehicleData = await this.$store.dispatch(
        'vehicles/createVehicle',
        vehicleAux
      );

      /* Crear o actualizar beneficiario */
      let beneficiaryId;
      if (this.beneficiaryFound) {
        beneficiaryId = this.beneficiaryId;
      } else {
        const beneficiaryAux = {
          dni: this.beneficiary.dni.replaceAll('.', ''),
          name: this.beneficiary.name,
          lastname: this.beneficiary.lastname,
          phone_number: `+569${this.beneficiary.phoneNumber.replaceAll(' ', '')}`,
          email: this.beneficiary.email,
        }
        const createdBeneficiaryData = await this.$store.dispatch(
          'beneficiaries/createBeneficiary',
          beneficiaryAux
        );
        beneficiaryId = createdBeneficiaryData.data.id;
      }

      const beneficiaryVehicleAux = {
        beneficiary_id: beneficiaryId,
        vehicle_id: createdVehicleData.data.id,
        owner: true,
      }
      await this.$store.dispatch(
        'beneficiaryVehicles/createBeneficiaryVehicle',
        beneficiaryVehicleAux
      );

      if (this.contacts.length) {
        for (const contact of this.contacts) {
          let beneficiaryId;
          const beneficiaryAux = {
            dni: contact.dni.replaceAll('.', ''),
            name: contact.name,
            lastname: contact.lastname,
            phone_number: `+569${contact.phoneNumber.replaceAll(' ', '')}`,
            email: contact.email,
          }
          if (_.has(contact, 'id')) {
            beneficiaryId = contact.id;
          } else {
            const createdBeneficiaryData = await this.$store.dispatch(
              'beneficiaries/createBeneficiary',
              beneficiaryAux
            );
            beneficiaryId = createdBeneficiaryData.data.id;
          }
          const beneficiaryVehicleAux = {
            beneficiary_id: beneficiaryId,
            vehicle_id: createdVehicleData.data.id,
            owner: false,
          }
          await this.$store.dispatch(
            'beneficiaryVehicles/createBeneficiaryVehicle',
            beneficiaryVehicleAux
          );
        }
      }

      /* Asociar dispositivo */
      if (this.includeDevice && this.deviceSelected) {
        const response = await this.$store.dispatch(
          'deviceStatus/getAllStatus',
          {
            query: {
              name: 'activated'
            },
          }
        );
        console.log(response);
        if (response && response.data) {
          const deviceAux = {
            id: this.deviceSelected.id,
            vehicle_id: createdVehicleData.data.id,
            device_status_id: response.data[0].id,
          };
          const data = await this.$store.dispatch('devices/updateDevice', deviceAux);
          console.log(data);
        }
      }

      this.$buefy.dialog.alert({
        title: '¡Vehículo Creado!',
        icon: 'car',
        hasIcon: true,
        message: `Se ha creado el vehículo.`,
        confirmText: 'Aceptar',
        onConfirm: () => this.$inertia.visit('/'),
      });
    },
    cancel() {
      this.$inertia.visit('/');
    },
  }
}
</script>

<style scoped>
  .form-section {
    background-color: #f8f8f8;
    border-radius: 10px;
  }

  .form-section-contact {
    background-color: white;
    border-radius: 10px;
  }
</style>