<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">DETALLE DE VEHÍCULO</h1>
        </div>
        <div class="column is-2">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <div class="columns">
        <div class="column is-8">
          <card title="Información de Vehículo" :header-search-input="false">
            <div slot="content">
              <div class="columns is-vcentered p-3" columns>
                <div class="column is-11">
                  <div class="columns is-vcentered">
                    <div class="column is-6">
                      <b-field label="Patente">
                        <b-input v-model="vehicle.plate_number" placeholder="Patente" disabled>
                        </b-input>
                      </b-field>
                    </div>
                    <div class="column is-6">
                      <b-field label="Modelo">
                        <b-input v-model="vehicle.model" placeholder="Model" disabled>
                        </b-input>
                      </b-field>
                    </div>
                  </div>
                  <div class="columns is-vcentered" v-if="vehicle.car_brand">
                    <div class="column is-6">
                      <b-field label="Marca">
                        <b-input v-model="vehicle.car_brand.name" placeholder="Marca" disabled>
                        </b-input>
                      </b-field>
                    </div>
                    <div class="column is-6">
                      <b-field label="Año">
                        <b-input v-model="vehicleDate" placeholder="Año" disabled>
                        </b-input>
                      </b-field>
                    </div>
                  </div>
                  <div class="columns is-vcentered">
                    <div class="column is-6">
                      <b-field label="Color">
                        <b-input v-model="vehicle.color" placeholder="Color" disabled>
                        </b-input>
                      </b-field>
                    </div>
                    <div class="column is-6">
                      <b-field label="Cilindraje">
                        <b-input v-model="vehicle.engine_capacity" placeholder="Cilindraje" disabled>
                        </b-input>
                      </b-field>
                    </div>
                  </div>
                  <div class="columns is-vcentered">
                    <div class="column is-6">
                      <b-field label="Número de Chasís">
                        <b-input v-model="vehicle.chassis_number" placeholder="Número de Chasís" disabled>
                        </b-input>
                      </b-field>
                    </div>
                    <div class="column is-6">
                      <b-field label="Tipo de Combustible">
                        <b-input v-model="vehicle.fuel_type.name" placeholder="Tipo de Combustible" disabled>
                        </b-input>
                      </b-field>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </card>
        </div>
        <div class="column has-text-centered is-4">
          <card title="Acciones" :header-search-input="false">
            <div slot="content">
              <b-button
                v-for="action in actions"
                @click="confirmAction(action)"
                :icon-left="action.icon"
                :type="action.type"
                class="mt-2"
                expanded
                :key="`button-${action.code}`"
              >
                {{ action.name }}
              </b-button>
            </div>
          </card>
        </div>
      </div>
      <div class="columns">
        <div class="column is-7">
          <card title="Información de Dueño" :header-search-input="false">
            <div slot="content" v-if="vehicle.beneficiaries">
              <div class="columns is-vcentered">
                <div class="column is-12">
                  <b-field  label="RUN">
                    <b-input v-model="ownerRun" placeholder="RUN" disabled>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div class="columns is-vcentered">
                <div class="column is-6">
                  <b-field label="Nombre">
                    <b-input v-model="vehicle.beneficiaries[0].name" placeholder="Nombre" disabled>
                    </b-input>
                  </b-field>
                </div>
                <div class="column is-6">
                  <b-field label="Apellidos">
                    <b-input v-model="vehicle.beneficiaries[0].lastname" placeholder="Apellidos" disabled>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div class="columns is-vcentered">
                <div class="column is-6">
                  <b-field label="Correo">
                    <b-input v-model="vehicle.beneficiaries[0].email" placeholder="Correo" disabled>
                    </b-input>
                  </b-field>
                </div>
                <div class="column is-6">
                  <b-field label="Teléfono">
                    <b-input v-model="vehicle.beneficiaries[0].phone_number" placeholder="Patente" disabled>
                    </b-input>
                  </b-field>
                </div>
              </div>
            </div>
          </card>
        </div>
        <div class="column is-5">
          <card title="Información de dispositivo" :header-search-input="false">
            <div slot="content" v-if="vehicle.device">
              <div class="columns is-vcentered">
                <div class="column is-10">
                  <b-field label="Número de Serie">
                    <b-input v-model="vehicle.device.serial_number" placeholder="Número de serie" disabled>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div class="columns is-vcentered" v-if="vehicle.device.sim_card.carrier">
                <div class="column is-10">
                  <b-field label="Carrier">
                    <b-input v-model="vehicle.device.sim_card.carrier.name" placeholder="Carrier" disabled>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div class="columns is-vcentered" v-if="vehicle.device.model">
                <div class="column is-6">
                  <b-field label="Marca">
                    <b-input v-model="vehicle.device.model.name" placeholder="Marca" disabled>
                    </b-input>
                  </b-field>
                </div>
              </div>
            </div>
          </card>
        </div>
      </div>
    </div>
    <b-modal
      v-model="modalShowing"
      has-modal-card
      trap-focus
      :destroy-on-hide="false"
      aria-role="dialog"
      aria-label="Example Modal"
      aria-modal
      scroll="keep"
    >
      <div class="card p-3">
        <section class="section">
          <div class="container has-text-centered">
            <b-icon icon="check-circle" size="is-large" /><br />
            <h4 class="title is-4 mt-5">Se ha realizado la acción exitosamente.</h4>
            <h4 class="title is-4 mb-5">Cuando la respuesta esté lista, se activará el botón.</h4>
            <answer-dialog v-if="task.answers && task.answers.length" :answer="task.answers[0]" />
            <b-button v-else type="is-link" size="is-small" disabled>
              Ver Respuesta
            </b-button>
          </div>
        </section>
      </div>
    </b-modal>
  </section>
</template>

<script>
import { Formatters } from '@/mixins/formatters';
import Card from '@/components/common/card';
import AnswerDialog from '@/components/tasks/answer_dialog';

export default {
  components: { Card, AnswerDialog },
  mixins: [Formatters()],
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      modalShowing: false,
      actions: [
        {
          code: 'locate',
          name: 'Localizar',
          icon: 'earth',
          type: 'is-success'
        },
        {
          code: 'turnOff',
          name: 'Apagar',
          icon: 'engine-off',
          type: 'is-danger'
        },
        {
          code: 'turnOn',
          name: 'Encender',
          icon: 'engine',
          type: 'is-info'
        },
      ],
      vehicle: {
        beneficiaries: [{}],
        device: {
          model: {},
          sim_card: { carrier: {} },
        },
        fuel_type: {},
        car_brand: {},
      },
      task: {},
    }
  },
  computed: {
    vehicleDate() {
      return this.moment(this.vehicle.owner_adquisition_date).format('D [de] MMMM, YYYY')
    },
    ownerRun() {
      return this.runFormatting(this.vehicle.owner_dni)
    },
  },
  async mounted() {
    await this.loadVehicle(this.id);
  },
  methods: {
    async loadVehicle(id) {
      const vehicleData = await this.$store.dispatch('vehicles/getVehicle', id);
      this.vehicle = vehicleData.data;
    },
    toIndex() {
      this.$inertia.visit('/');
    },
    confirmAction(action) {
      this.$buefy.dialog.confirm({
          title: 'Confirmar Acción',
          icon: action.icon,
          hasIcon: true,
          message: `Está seguro que desea realizar la acción de <b>${action.name}</b> vehículo.`,
          cancelText: 'Cancelar',
          confirmText: 'Aceptar',
          type: action.type,
          onConfirm: () => this.doAction(action.code)
      })
    },
    async wait(ms) {
      return new Promise(resolve => {
        setTimeout(resolve, ms);
      });
    },
    async getTask(id) {
      const response = await this.$store.dispatch('tasks/getTask', id);
      await this.wait(3000);
      return response.data;
    },
    async doAction(code) {
      this.task = {};
      try {
        const taskData = await this.$store.dispatch(`actions/${code}`, this.vehicle.id);
        this.modalShowing = true;
        let response;
        let counter = 0;
        while(counter < 7) {
          response = await this.getTask(taskData.data.id);
          if (response.answers && response.answers.length) {
            this.task = response;
            break;
          }
          counter++;
        }
        if (counter === 7) {
          this.$buefy.dialog.confirm({
            icon: 'close-thick',
            hasIcon: true,
            message: `No se pudo recibir respuesta del dispositivo.`,
            confirmText: 'Aceptar',
            type: 'is-danger',
          })
        }
        // this.task = await this.getTask(taskData.data.id);
        // this.task = taskData.data;
        /* this.$buefy.dialog.confirm({
          icon: 'check-circle',
          hasIcon: true,
          message: `Se ha realizado la acción exitosamente. Cuando la respuesta esté lista, se visualizará un botón.`,
          confirmText: 'Aceptar',
          type: 'is-success',
        }); */
      } catch (error) {
        console.log(error); 
        this.$buefy.dialog.confirm({
          icon: 'close-thick',
          hasIcon: true,
          message: `No se pudo realizar la acción.`,
          confirmText: 'Aceptar',
          type: 'is-danger',
        })
      }
    },
  },
}
</script>
