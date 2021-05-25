<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">DETALLE DE EVENTO</h1>
        </div>
        <div class="column is-2">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <div class="columns">
        <div class="column is-12">
          <card title="Información del Evento" :header-search-input="false">
            <div slot="header-right" class="card-header-icon">
              <span
                v-if="event && event.status"
                class="tag is-large"
                :class="statusStyle[event.status.code]"
                expanded
              >
                {{ event.status.name }}
              </span>
            </div>
            <div slot="content">
              <div v-if="event && event.vehicle" class="columns is-vcentered">
                <div class="column is-6">
                  <b-field label="Patente Vehículo">
                    <b-input v-model="event.vehicle.plateNumber" placeholder="Model" readonly>
                    </b-input>
                  </b-field>
                </div>
                <div class="column is-6">
                  <b-field label="Modelo">
                    <b-input v-model="event.vehicle.model" placeholder="Model" readonly>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div v-if="event && event.beneficiary" class="columns is-vcentered">
                <div class="column is-6">
                  <b-field label="Nombre Beneficiario">
                    <b-input v-model="ownerName" placeholder="Model" readonly>
                    </b-input>
                  </b-field>
                </div>
                <div class="column is-6">
                  <b-field label="RUN">
                    <b-input v-model="ownerDni" placeholder="Model" readonly>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div v-if="event && event.beneficiary" class="columns is-vcentered">
                <div class="column is-6">
                  <b-field label="Fecha de Operación">
                    <b-input v-model="eventDate" placeholder="Model" readonly>
                    </b-input>
                  </b-field>
                </div>
                <div class="column is-6">
                  <b-field label="Nombre Operador">
                    <b-input v-model="receiverName" placeholder="Model" readonly>
                    </b-input>
                  </b-field>
                </div>
              </div>
              <div v-if="event && event.answers" class="mt-5">
                <h5 class="title is-5">Preguntas</h5>
                <div v-if="event.origin" class="mb-3">
                  <GmapMap
                    :center="event.origin.location ? event.origin.location : { lat: -38.7410298, lng: -72.6269321 }"
                    :zoom="15"
                    map-type-id="roadmap"
                    style="width: 100%; height: 250px"
                  >
                    <GmapMarker
                      v-if="event.origin"
                      key="marker"
                      :draggable="false"
                      :position="event.origin.location"
                    />
                  </GmapMap>
                </div>
                <div v-for="(answer, index) in event.answers" :key="index" class="columns is-vcentered">
                  <div class="column is-5">
                    <h6 class="title is-6">{{ `${answer.question.order}. ¿${answer.question.name}?` }}</h6>
                  </div>
                  <div class="column is-7">
                    <b-field>
                      <b-input
                        v-model="answer.alternative.body"
                        readonly
                      >
                      </b-input>
                    </b-field>
                  </div>
                </div>
              </div>
              <div class="mt-5">
                <h5 class="title is-5">Acciones</h5>
                <div class="columns">
                  <div class="column is-12">
                    <tasks-timeline ref="tasks-timeline" />
                  </div>
                </div>
              </div>
            </div>
          </card>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { Formatters } from '@/mixins/formatters';
import Card from '@/components/common/card';
import TasksTimeline from '@/components/events/tasks_timeline';

export default {
  mixins: [Formatters()],
  components: { Card, TasksTimeline },
  props: {
    id: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      event: null,
      tasks: [],
      answers: [],
      points: [],
      statusStyle: {
        'finished': 'is-info',
        'in-progress': 'is-warning',
        'created': 'is-validated',
        'validated': 'is-success',
      },
    }
  },
  computed: {
    ownerDni() {
      if (this.event && this.event.beneficiary) {
        return this.runFormatting(this.event.beneficiary.dni);
      }
      return '';
    },
    ownerName() {
      if (this.event && this.event.beneficiary) {
        return `${this.event.beneficiary.name} ${this.event.beneficiary.lastname}`;
      }
      return '';
    },
    receiverName() {
      if (this.event && this.event.receiver) {
        return `${this.event.receiver.name} ${this.event.receiver.lastname}`;
      }
      return '';
    },
    eventDate() {
      return this.moment(this.event.eventDate).format('D [de] MMMM, YYYY') +
        (this.event.eventTime ? ' - ' + this.event.eventTime.slice(0, 5) : '');
    },
  },
  async mounted() {
    await this.loadEvent(this.id);
  },
  methods: {
    async loadEvent(id) {
      const response = await this.$store.dispatch('events/getEvent', id);
      this.event = {
        'id': response.data.id,
        'tenantId': response.data.tenant_id,
        'beneficiaryId': response.data.beneficiary_id,
        'vehicleId': response.data.vehicle_id,
        'receivedBy': response.data.received_by,
        'detail': response.data.detail,
        'eventDate': response.data.event_date,
        'eventTime': response.data.event_time,
        'observation': response.data.observation,
        'eventStatusId': response.data.event_status_id,
        'origin': response.data.origin,
        'tasks': response.data.tasks,
        'answers': this.renameKeysOfList(response.data.answers, {
          'answer': 'answer',
          'created_at': 'createdAt',
          'event_id': 'eventId',
          'event_question_id': 'eventQuestionId',
          'id': 'id',
          'updated_at': 'updatedAt',
          'question': 'question',
          'alternative': 'alternative',
        }),
        'beneficiary': this.renameKeysOfObject(response.data.beneficiary, {
          'address': 'address',
          'created_at': 'createdAt',
          'dni': 'dni',
          'dni_serial_number': 'dniSerialNumber',
          'email': 'email',
          'id': 'id',
          'lastname': 'lastname',
          'name': 'name',
          'phone_number': 'phoneNumber',
          'updated_at': 'updatedAt',
        }),
        'vehicle': this.renameKeysOfObject(response.data.vehicle, {
          'card_brand_id': 'carBrandId',
          'chassis_number': 'chassisNumber',
          'color': 'color',
          'color_code': 'colorCode',
          'country_id': 'countryId',
          'created_at': 'createdAt',
          'drive': 'drive',
          'engine_capacity': 'engineCapacity',
          'fuel_type_id': 'fuelTypeId',
          'id': 'id',
          'key': 'key',
          'model': 'model',
          'model_id': 'modelId',
          'owner_acquisition_date': 'ownerAcquisitionDate',
          'owner_dni': 'ownerDni',
          'owner_name': 'ownerName',
          'plate_number': 'plateNumber',
          'tenant_id': 'tenantId',
          'updated_at': 'updatedAt',
          'vehicle_type_id': 'vehicleTypeId',
          'year': 'year',
        }),
        'status': response.data.status,
        'receiver': this.renameKeysOfObject(response.data.receiver, {
          'api_token': 'apiToken',
          'created_at': 'createdAt',
          'dni': 'dni',
          'email': 'email',
          'email_verified_at': 'emailVerifiedAt',
          'id': 'id',
          'lastname': 'lastname',
          'name': 'name',
          'phone': 'phone',
          'provider': 'provider',
          'provider_id': 'providerId',
          'second_lastname': 'secondLastname',
          'second_name': 'secondName',
          'updated_at': 'updatedAt',
          'username': 'username',
        }),
      };
      if (this.event.tasks) {
        this.event.tasks.forEach((task) => {
          this.tasks.push(task);
          this.$refs['tasks-timeline'].pushTaskInTimeline(task);
          if (task.answers) {
            task.answers.forEach((answer) => {
              this.receiveAnswer(answer);
            });
          }
        });
      }
    },
    receiveAnswer(answer) {
      this.answers.push(answer);
      this.$refs['tasks-timeline'].pushAnswerInTimeline(answer);
      if (answer.detail.type === 'locate') {
        this.points.push({
          lat: parseFloat(answer.detail.schema.lat),
          lng: parseFloat(answer.detail.schema.long),
          infoWindowOpen: false,
          date: new Date(answer.created_at),
        });
      }
    },
    toIndex() {
      this.$inertia.visit(route('events.index'));
    },
  },
}
</script>