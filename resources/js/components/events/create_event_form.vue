<template>
  <div>
    <idle-modal :active="verified" @timer-end="leaveEvent" />
    <div class="form-section columns is-vcentered p-3">
      <ValidationObserver v-slot="{ handleSubmit, invalid }" class="column is-12">
        <form @submit.prevent="handleSubmit(sendRequest)">
          <div class="form-section columns is-vcentered" columns>
            <div class="column is-1">
              <b-icon icon="numeric-1-circle" size="is-large" />
            </div>
            <div class="column is-11">
              <h5 class="title is-5">Información de Beneficiario</h5>
              <div class="columns is-centered">
                <ValidationProvider
                  v-slot="{ errors }"
                  name="run"
                  rules="required|run"
                  class="column is-8"
                >
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]"
                    label="RUN"
                  >
                    <b-input v-model="dni" placeholder="RUN">
                    </b-input>
                  </b-field>
                </ValidationProvider>
                <div class="column is-4 item">
                  <b-button
                    type="is-link"
                    class="search-button"
                    @click.prevent="searchBeneficiaries"
                    :disabled="invalid || callerBeneficiary"
                    expanded
                  >
                    Buscar
                  </b-button>
                </div>
              </div>
              <div class="columns is-centered mt-5">
                <found-beneficiaries-table
                  ref="found-beneficiaries-table"
                  :dni="dni"
                  @beneficiary-selected="selectBeneficiary"
                />
              </div>
            </div>
          </div>
        </form>
      </ValidationObserver>
    </div>
    <transition name="slide-fade">
      <div
        v-if="callerBeneficiary"
        class="form-section columns is-vcentered p-3"
        ref="section-2"
      >
        <ValidationObserver v-slot="{ handleSubmit, invalid }" class="column is-12">
          <hr class="divider mb-5">
          <form @submit.prevent="handleSubmit(sendRequest)">
            <div class="form-section columns is-vcentered mt-5" columns>
              <div class="column is-1">
                <b-icon icon="numeric-2-circle" size="is-large" />
              </div>
              <div class="column is-11">
                <h5 class="title is-5">Información de Vehículo</h5>
                <div class="columns is-centered">
                  <ValidationProvider
                    v-slot="{ errors }"
                    name="patente"
                    rules="min:6|max:6"
                    class="column is-8"
                  >
                    <b-field
                      :type="errors.length > 0 ? 'is-danger':''"
                      :message="errors[0]"
                      label="Patente"
                    >
                      <b-input v-model="plateNumber" placeholder="Nombre">
                      </b-input>
                    </b-field>
                  </ValidationProvider>
                  <div class="column is-4 item">
                    <b-button
                      type="is-link"
                      class="search-button"
                      @click.prevent="searchVehicles"
                      :disabled="invalid || (dni === '' && plateNumber === '')"
                      expanded
                    >
                      Buscar
                    </b-button>
                  </div>
                </div>
                <div class="columns is-centered mt-5">
                  <found-vehicles-table
                    ref="found-vehicles-table"
                    :query="query"
                    @vehicle-selected="selectVehicle"
                  />
                </div>
              </div>
            </div>
          </form>
        </ValidationObserver>
      </div>
    </transition>
    <transition name="slide-fade">
      <div
        v-if="event"
        class="form-section columns is-vcentered p-3"
        ref="section-3"
      >
        <ValidationObserver v-slot="{ handleSubmit, invalid }" class="column is-12">
          <hr class="divider mb-5">
          <form @submit.prevent="handleSubmit(sendRequest)">
            <div class="form-section columns is-vcentered mt-5" columns>
              <div class="column is-1">
                <b-icon icon="numeric-3-circle" size="is-large" />
              </div>
              <div class="column is-11">
                <h5 class="title is-5">Verificación</h5>
                <div class="columns is-centered">
                  <div class="column is-4 item">
                    <b-button
                      class="search-button"
                      type="is-link"
                      @click="showSendCodeModal"
                    >
                      Enviar Código
                    </b-button>
                  </div>
                  <div class="column is-8">
                    <div class="columns is-centered ml-5">
                      <ValidationProvider
                        v-slot="{ errors }"
                        name="código de seguridad"
                        rules="required|min:5|max:5"
                        class="column"
                      >
                        <b-field
                          class="mt-5"
                          :type="errors.length > 0 ? 'is-danger':''"
                          :message="errors[0]"
                          label="Código de Seguridad"
                        >
                          <b-input
                            v-model="code"
                            placeholder="Código"
                            :disabled="!codeSent"
                          >
                          </b-input>
                        </b-field>
                      </ValidationProvider>
                      <div class="column is-4 item">
                        <b-button
                          type="is-success"
                          class="search-button"
                          @click.prevent="verifyCode"
                          :disabled="invalid || !codeSent || verified"
                          expanded
                        >
                          Verificar
                        </b-button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </ValidationObserver>
      </div>
    </transition>
    <send-code-modal
      v-if="event"
      ref="send-code-modal"
      :beneficiaries="selectedVehicle.beneficiaries"
      :vehicle-id="selectedVehicle.id"
      :owner-id="callerBeneficiary.id"
      :event="event"
      @code-sent="enableCodeInput"
    />
    <transition name="slide-fade">
      <div
        v-show="verified"
        class="form-section columns is-multiline p-3"
        ref="section-4"
      >
        <div class="column is-12">
          <hr class="divider mb-5">
        </div>
        <div class="column is-12">
          <div class="columns is-vcentered">
            <div class="column is-1 p-0">
              <b-icon icon="numeric-4-circle" size="is-large" />
            </div>
            <div class="column is-11">
              
              <h5 class="title is-5">Preguntas</h5>
              <div class="columns">
                <ValidationObserver v-slot="{ handleSubmit }" class="column is-12">
                  <form @submit.prevent="handleSubmit(saveAnswers)">
                    <ValidationProvider
                          v-slot="{ errors }"
                          name="ubicación de evento"
                          rules="required"
                        >
                      <b-field
                          :type="errors.length > 0 ? 'is-danger':''"
                          :message="errors[0]"
                          label="Ubicación de evento">
                          <input type="hidden" v-model="origin.address">
                          <gmap-autocomplete ref="autocomplete" @place_changed="changeEventLocation" :value="origin.address" :class="['input',{'is-danger': errors[0]}]" :disabled="questionsAnswered"></gmap-autocomplete>
                      </b-field>
                    </ValidationProvider>
                    <b-field>
                      <GmapMap
                        :center="origin && origin.location ? origin.location : { lat: -38.7410298, lng: -72.6269321 }"
                        :zoom="15"
                        map-type-id="roadmap"
                        style="width: 100%; height: 250px"
                      >
                        <GmapMarker
                          v-if="origin"
                          key="marker"
                          :draggable="!questionsAnswered"
                          @dragend="manualPositionChange"
                          :position="origin.location"
                        />
                      </GmapMap>
                    </b-field>
                    <div v-for="(question, index) in questions" :key="question.id" class="columns is-vcentered">
                      <div class="column is-5">
                        <h6 class="title is-6">{{ `${question.order}. ¿${question.name}?` }}</h6>
                      </div>
                      <ValidationProvider
                        v-slot="{ errors }"
                        :name="`${question.name}`"
                        rules="required"
                        class="column is-7"
                      >
                        <b-field
                          :type="errors.length > 0 ? 'is-danger':''"
                          :message="errors[0]"
                          label=""
                        >
                          <b-input
                            v-if="question.order === 1 || question.order === 3"
                            v-model="questionsAnswers[index]"
                            :type="question.order === 3 ? 'number': 'text'"
                            :placeholder="question.description"
                            :disabled="questionsAnswered"
                          >
                          </b-input>
                          <div v-else>
                            <b-radio
                              v-model="questionsAnswers[index]"
                              native-value="Si"
                              :disabled="questionsAnswered"
                            >
                              Si
                            </b-radio>
                            <b-radio
                              v-model="questionsAnswers[index]"
                              native-value="No"
                              :disabled="questionsAnswered"
                            >
                              No
                            </b-radio>
                          </div>
                        </b-field>
                      </ValidationProvider>
                    </div>
                    <div class="container-right">
                      <b-button
                        type="is-link"
                        class="search-button mt-3"
                        native-type="submit"
                        :disabled="questionsAnswered"
                      >
                        Guardar respuestas
                      </b-button>
                    </div>
                  </form>
                </ValidationObserver>
              </div>
              <h5 class="title is-5">Acciones</h5>
              <div class="columns">
                <div class="column is-3">
                  <div slot="content">
                    <b-button
                      v-for="(action, index) in actions"
                      @click="confirmAction(action)"
                      :icon-left="action.icon"
                      :type="action.type"
                      :class="index > 0 ? 'mt-4 is-medium' : 'is-medium'"
                      expanded
                      :key="`button-${action.code}`"
                      :disabled="action.deactivated || !verified"
                    >
                      {{ action.name }}
                    </b-button>
                    <b-button
                      @click="showRoute()"
                      icon-left="map-marker"
                      type="is-warning"
                      class="mt-4 is-medium"
                      expanded
                      key="button-route"
                      :disabled="!points.length || !verified"
                    >
                      Ver Recorrido
                    </b-button>
                  </div>
                </div>
                <div class="column is-9">
                  <tasks-timeline ref="tasks-timeline" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <div
      v-if="verified"
      class="form-section columns is-multiline p-3"
      ref="section-4"
    >
      <div class="column is-12">
          <hr class="divider mb-5">
        </div>
      <div class="column is-12">
        <div class="columns is-vcentered">
          <div class="column is-1">
            <b-icon icon="numeric-5-circle" size="is-large" />
          </div>
          <div class="column is-13">
            <h5 class="title is-5">Finalizar</h5>
            <div class="columns is-centered">
              <b-button
                @click="showLeaveEventModal()"
                icon-left="arrow-left"
                type="is-warning"
                class="column m-4 is-medium"
                expanded
              >
                Salir
              </b-button>
              <b-button
                @click="showFinishEventModal()"
                icon-left="car"
                type="is-link"
                class="column m-4 is-medium"
                expanded
              >
                Finalizar Evento
              </b-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Formatters } from '@/mixins/formatters';
import FoundBeneficiariesTable from './found_beneficiaries_table';
import FoundVehiclesTable from './found_vehicles_table';
import SendCodeModal from './send_code_modal';
import TasksTimeline from './tasks_timeline';
import IdleModal from '@/components/common/idle_modal';

export default {
  components: {
    FoundVehiclesTable,
    FoundBeneficiariesTable,
    SendCodeModal,
    TasksTimeline,
    IdleModal,
  },
  mixins: [Formatters()],
  data() {
    return {
      plateNumber: '',
      code: '',
      dni: '',
      callerBeneficiary: null,
      selectedVehicle: null,
      codeSent: false,
      verified: false,
      event: null,
      verifierBeneficiaryId: '',
      origin: {
        address: null,
        location: null,
        place_id: null
      },
      actions: [
        {
          code: 'locate',
          name: 'Localizar',
          icon: 'earth',
          type: 'is-success',
          deactivated: false,
        },
        {
          code: 'turn_off',
          name: 'Apagar',
          icon: 'engine-off',
          type: 'is-danger',
          deactivated: false,
        },
        {
          code: 'turn_on',
          name: 'Encender',
          icon: 'engine',
          type: 'is-info',
          deactivated: false,
        },
      ],
      tasks: [],
      answers: [],
      points: [
        /*{
          lat: -39.28,
          lng: -71.92,
          infoWindowOpen: false,
          date: new Date(),
        },
        {
          lat: -39.28,
          lng: -71.90,
          infoWindowOpen: false,
          date: new Date(),
        },
        {
          lat: -39.29,
          lng: -71.915,
          infoWindowOpen: false,
          date: new Date(),
        },*/
      ],
      time: 15000,
      questions: [],
      questionsAnswers: [],
      questionsAnswered: false,
    }
  },
  computed: {
    query() {
      const queryAux = {};
      if (this.plateNumber !== '') queryAux.plate_number = this.plateNumber;
      if (this.dni !== '') queryAux.dni = this.dni.replaceAll('.', '');
      return queryAux;
    },
  },
  watch: {
    plateNumber: {
      handler(val) {
        this.plateNumber = val.toUpperCase();
      },
    },
    code: {
      handler(val) {
        this.code = val.toUpperCase();
      },
    },
    dni: {
      handler(val) {
        this.dni = this.runFormatting(val);
      },
    },
    callerBeneficiary(val) {
      if (val !== null && val !== {}) {
        this.$nextTick(() => {
          if (this.$refs['section-2']) this.$refs['section-2'].scrollIntoView();
        });
      }
    },
    selectedVehicle(val) {
      if (val !== null && val !== {}) {
        this.loadEvent();
      }
    },
    event(val) {
      if (val !== null && val !== {}) {
        this.$nextTick(() => {
          if (this.$refs['section-3']) this.$refs['section-3'].scrollIntoView();
        });
      }
    },
    verified(val) {
      if (val) {
        this.$nextTick(() => {
          if (this.$refs['section-4']) this.$refs['section-4'].scrollIntoView();
        });
      }
    },
  },
  beforeDestroy() {
    if (this.event) {
      Echo.leave(`event.${this.event.id}`);
    }
  },
  methods: {
    showRoute() {
      this.$refs['tasks-timeline'].showRoute(this.points);
    },
    async loadEvent() {
      const response = await this.$store.dispatch('events/findInProgress', {
        beneficiaryId: this.callerBeneficiary.id,
        vehicleId: this.selectedVehicle.id,
      });
      if (response && response.data && _.has(response.data, 'id')) {
        this.event = response.data;
        this.origin = this.event.origin

        this.initAnswersListener(this.event.id);
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
      } else {
        const createdEventData = await this.$store.dispatch(
          'events/createEvent',
          {
            beneficiary_id: this.callerBeneficiary.id,
            vehicle_id: this.selectedVehicle.id,
          }
        );
        this.event = createdEventData.data;
        this.initAnswersListener(this.event.id);
      }
    },
    searchBeneficiaries() {
      this.$refs['found-beneficiaries-table'].loadBeneficiaries();
    },
    selectBeneficiary(beneficiary) {
      this.callerBeneficiary = beneficiary;
    },
    searchVehicles() {
      this.$refs['found-vehicles-table'].loadVehicles();
    },
    selectVehicle(vehicle) {
      this.selectedVehicle = vehicle;
    },
    showSendCodeModal() {
      this.$refs['send-code-modal'].showModal();
    },
    enableCodeInput(beneficiaryId) {
      this.verifierBeneficiaryId = beneficiaryId;
      this.$refs['send-code-modal'].hideModal();
      this.codeSent = true;
    },
    async verifyCode() {
      const response = await this.$store.dispatch('events/validateCode', {
        beneficiaryId: this.verifierBeneficiaryId,
        eventId: this.event.id,
        code: this.code,
      });

      if (response && response.is_valid) {
        this.$buefy.dialog.alert({
          icon: 'check-circle',
          hasIcon: true,
          message: `¡Código Correcto!`,
          confirmText: 'Aceptar',
          type: 'is-success',
        });
        this.verified = true;
        await this.changeEventToInProgress();
        await this.loadQuestions();
      } else {
        this.$buefy.dialog.alert({
          icon: 'close-thick',
          hasIcon: true,
          message: `Código inválido. Intente de nuevo.`,
          confirmText: 'Aceptar',
          type: 'is-danger',
        });
      }
    },
    async loadQuestions() {
      const response = await this.$store.dispatch('eventQuestions/getEventQuestions', {});
      this.questions = response.data;
      
      if (this.event.answers && this.event.answers.length >= this.questions.length) {
        this.questionsAnswered = true;
        this.questionsAnswers = this.event.answers
          .slice(this.event.answers.length - this.questions.length, this.event.answers.length)
          .map(answer => { return answer.answer });
      }
    },
    async saveAnswers() {
      const responseEvent = await this.$store.dispatch('events/updateEvent', {
        id: this.event.id,
        origin: JSON.stringify(this.origin),
      });
      const response = await this.$store.dispatch(
        'eventQuestionsApplied/createList',
        this.questionsAnswers.map((answer, index) => {
          return {
            event_id: this.event.id,
            event_question_id: this.questions[index].id,
            answer,
          };
        })
      );
      if (response && response.message === 'answer registered successfully') {
        this.questionsAnswered = true;
        this.$buefy.dialog.alert({
          icon: 'check-circle',
          hasIcon: true,
          message: `Se han guardado las respuestas exitosamente.`,
          confirmText: 'Aceptar',
          type: 'is-success',
        });
      } else {
        this.$buefy.dialog.alert({
          icon: 'close-thick',
          hasIcon: true,
          message: `Ocurrió un error al guardar las respuestas.`,
          confirmText: 'Aceptar',
          type: 'is-danger',
        });
      }
    },
    confirmAction(action) {
      this.$buefy.dialog.confirm({
        title: 'Confirmar Acción',
        icon: action.icon,
        hasIcon: true,
        message: `¿Está seguro que desea realizar la acción de <b>${action.name}</b> vehículo?`,
        cancelText: 'Cancelar',
        confirmText: 'Aceptar',
        type: action.type,
        onConfirm: () => this.doAction(action.code)
      });
    },
    async doAction(code) {
      try {
        const taskResponse = await this.$store.dispatch('actions/send', {
          code,
          vehicleId: this.selectedVehicle.id,
          eventId: this.event.id,
        });
        this.actions.forEach((action, index) => {
          if (action.code === code) this.actions[index].deactivated = true;
        })
        const task = _.cloneDeep(taskResponse.data);
        this.tasks.push(task);
        this.$refs['tasks-timeline'].pushTaskInTimeline(task);
        this.$buefy.dialog.alert({
          icon: 'check-circle',
          hasIcon: true,
          message: `Se ha realizado la acción exitosamente.`,
          confirmText: 'Aceptar',
          type: 'is-success',
        });
      } catch (error) {
        console.log(error); 
        this.$buefy.dialog.alert({
          icon: 'close-thick',
          hasIcon: true,
          message: `No se pudo realizar la acción.`,
          confirmText: 'Aceptar',
          type: 'is-danger',
        });
      }
      setTimeout(() => {
        this.actions.forEach((action, index) => {
          if (action.code === code) this.actions[index].deactivated = false;
        });
      }, 4000);
    },
    initAnswersListener(eventId) {
      Echo.channel(`event.${eventId}`).listen('.new-answer', (e) => {
        if (e.task_answer) {
          const answer = _.cloneDeep(e.task_answer);
          answer.type = 'ANSWER';
          this.receiveAnswer(e.task_answer);
        }
      });
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
    async showFinishEventModal() {
      if (this.event) {
        this.$buefy.dialog.confirm({
          icon: 'check-circle',
          hasIcon: true,
          message: `El evento quedará finalizado`,
          confirmText: 'Aceptar',
          type: 'is-success',
          onConfirm: () => this.finishEvent(),
        });
      }
    },
    async finishEvent() {
      if (this.event) {
        const finishEventResponse = await this.$store.dispatch(
          'events/finishEvent',
          this.event.id
        );
        if (finishEventResponse && finishEventResponse.finished) {
          this.$inertia.visit('/');
        } else {
          this.$buefy.dialog.alert({
            icon: 'close-thick',
            hasIcon: true,
            message: `No se pudo finalizar el evento.`,
            confirmText: 'Aceptar',
            type: 'is-danger',
          });
        }
      } else this.$inertia.visit('/');
    },
    async showLeaveEventModal() {
      if (this.event) {
        this.$buefy.dialog.confirm({
          icon: 'check-circle',
          hasIcon: true,
          message: `El evento quedará en progreso`,
          confirmText: 'Aceptar',
          type: 'is-warning',
          onConfirm: () => this.leaveEvent(),
        });
      }
    },
    async changeEventToInProgress() {
      const leaveEventResponse = await this.$store.dispatch(
        'events/leaveEvent',
        this.event.id
      );
      return leaveEventResponse;
    },
    async leaveEvent() {
      if (this.event) {
        const leaveEventResponse = await this.changeEventToInProgress();
        if (leaveEventResponse && leaveEventResponse.finished) {
          this.$inertia.visit('/');
        } else {
          this.$buefy.dialog.alert({
            icon: 'close-thick',
            hasIcon: true,
            message: `No se pudo actualizar el estado del evento.`,
            confirmText: 'Aceptar',
            type: 'is-danger',
          });
        }
      } else this.$inertia.visit('/');
    },
    changeEventLocation(data) {
      let lat = data.geometry.location.lat();
      let lng = data.geometry.location.lng();
      this.origin = {
        address: data.formatted_address,
        location: {
          lat,
          lng
        },
        place_id: data.place_id
      };
    },
    manualPositionChange(data){
      let lat = data.latLng.lat();
      let lng = data.latLng.lng();
      this.changePosition({lat,lng})
    },
    async changePosition(location){
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location }, (results, status) => {
        if (results.length) {
          let data = results[0];
          this.origin = {
            address: data.formatted_address,
            location: location,
            place_id: data.place_id
          };
        }
      });
    }
  },
}
</script>

<style scoped>
  .search-button {
    margin-top: auto;
  }

  .item {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .b-table {
    width: 100%;
  }

  .divider {
    width: 90%;
    height: 2pt;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    background-color: #dadada;
    border-radius: 10px;
  }

  .slide-fade-enter-active {
    transition: all .2s ease;
  }

  .slide-fade-leave-active {
    transition: all .2s ease;
  }

  .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateY(-10px);
    opacity: 0;
  }

  .container-right {
    text-align: right;
  }

  .map {
    width: 430pt;
    height: 280pt;
  }
</style>
