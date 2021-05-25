<template>
  <div>
    <idle-modal :active="verified" @timer-end="leaveEvent" />
    <b-steps v-model="activeStep" v-bind="stepsConfig" class="mt-3">
      <b-step-item
        step="1"
        value="beneficiary-step"
        label="Identificación"
        :clickable="false"
        :type="{ 'is-success': isProfileSuccess }"
      >
        <beneficiary-step
          @onConfirmBeneficiarySelection="onSelectedBeneficiary"
        />
        <fade-in-trasition>
          <div v-if="dni" ref="vehicle-section">
            <vehicle-step
              :dni="dni"
              @onConfirmVehicleSelection="onSelectedVehicle"
            />
          </div>
        </fade-in-trasition>
      </b-step-item>
      <b-step-item
        step="2"
        value="verification-step"
        label="Verificación"
        :clickable="false"
        :type="{ 'is-success': isProfileSuccess }"
      >
        <div v-if="vehicle && event" ref="verification-section">
          <div class="columns mt-3">
            <div class="column">
              <beneficiary-info v-bind="beneficiary" />
            </div>
            <div class="column">
              <vehicle-info v-bind="vehicle" />
            </div>
          </div>
          <verification-step
            :vehicle-id="vehicle.id"
            :event-id="event.id"
            :beneficiaries="beneficiaries"
            @verifiedCode="onVerifiedCode"
          />
        </div>
      </b-step-item>
      <b-step-item
        step="3"
        value="actions-step"
        label="Acciones"
        :clickable="false"
        :type="{ 'is-success': isProfileSuccess }"
      >
        <div v-if="vehicle && event" ref="actions-section">
          <div class="columns mt-3">
            <div class="column">
              <beneficiary-info v-bind="beneficiary" />
              <event-info v-bind="event" />
            </div>
            <div class="column">
              <vehicle-info v-bind="vehicle" />
            </div>
          </div>
          <actions-step
            :simcard-id="simcardId"
            :vehicle-id="vehicle.id"
            :event-id="event.id"
            :initial-tasks="tasks"
          />
          <b-button type="is-link" @click="continueToQuestions">
            Continuar
          </b-button>
        </div>
      </b-step-item>
      <b-step-item
        step="4"
        value="questions-step"
        label="Preguntas"
        :clickable="false"
        :type="{ 'is-success': isProfileSuccess }"
      >
        <div v-if="event" ref="question-section">
          <div class="columns mt-3">
            <div class="column">
              <beneficiary-info v-bind="beneficiary" />
              <event-info v-bind="event" />
            </div>
            <div class="column">
              <vehicle-info v-bind="vehicle" />
            </div>
          </div>
          <questions-step :event="event" />
          <div class="buttons">
            <b-button type="is-link" @click="continueToActions">
              Volver
            </b-button>
            <b-button type="is-link" @click="continueToSummary">
              Continuar
            </b-button>
          </div>
        </div>
      </b-step-item>
      <b-step-item
        step="5"
        value="summary-step"
        label="Finalizar"
        :clickable="false"
        :type="{ 'is-success': isProfileSuccess }"
        :destroy-on-hide="true"
      >
        <div
          v-if="event && activeStep === 'summary-step'"
          ref="summary-section"
        >
          <div class="columns mt-3">
            <div class="column">
              <beneficiary-info v-bind="beneficiary" />
            </div>
            <div class="column">
              <vehicle-info v-bind="vehicle" />
            </div>
          </div>
          <summary-step :event-id="event.id" />
          <div class="columns">
            <div class="column is-half">
              <div class="columns">
                <div class="column">
                  <b-button
                    expanded
                    type="is-link"
                    @click="continueToQuestions"
                  >
                    Volver
                  </b-button>
                </div>
                <div class="column">
                  <b-button
                    expanded
                    type="is-link"
                    @click="showLeaveEventModal"
                  >
                    Salir
                  </b-button>
                </div>
                <div class="column">
                  <b-button
                    expanded
                    type="is-link"
                    @click="showFinishEventModal"
                  >
                    Finalizar
                  </b-button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </b-step-item>
    </b-steps>
  </div>
</template>

<script>
import BeneficiaryStep from "@/components/events/create_flow/beneficiary_step";
import VehicleStep from "@/components/events/create_flow/vehicle_step";
import VerificationStep from "./create_flow/verification_step.vue";
import BeneficiaryInfo from "./create_flow/beneficiary_info.vue";
import VehicleInfo from "./create_flow/vehicle_info.vue";
import EventInfo from "./create_flow/event_info.vue";
import ActionsStep from "./create_flow/actions_step.vue";
import QuestionsStep from "./create_flow/questions_step.vue";
import SummaryStep from "./create_flow/summary_step.vue";
import IdleModal from "@/components/common/idle_modal";

export default {
  components: {
    BeneficiaryStep,
    VehicleStep,
    VerificationStep,
    BeneficiaryInfo,
    VehicleInfo,
    ActionsStep,
    QuestionsStep,
    SummaryStep,
    IdleModal,
    EventInfo,
  },
  data() {
    return {
      stepsConfig: {
        hasNavigation: false,
        labelPosition: "right",
      },
      event: null,
      beneficiary: null,
      vehicle: null,
      activeStep: "beneficiary-step",
      isStepsClickable: true,
      isProfileSuccess: true,
      verified: false,
      callStartTime: null,
      eventCall: null,
    };
  },
  watch: {
    event(newValue, oldValue) {
      if(newValue && !oldValue) {
        this.startCall();
      }
    }
  },
  computed: {
    dni() {
      return this.beneficiary ? this.beneficiary.dni : null;
    },
    beneficiaries() {
      return this.vehicle && this.vehicle.beneficiaries
        ? this.vehicle.beneficiaries
        : [];
    },
    tasks() {
      return this.event && this.event.tasks ? this.event.tasks : [];
    },
    simcardId() {
      return this.vehicle?.device?.sim_card?.id;
    },
  },
  methods: {
    async loadEvent() {
      const response = await this.$store.dispatch(
        "events/findInProgress",
        this.vehicle.id
      );
      if (response && response.data && _.has(response.data, "id")) {
        if (response.data.status && response.data.status.code === "created") {
          this.$buefy.dialog.confirm({
            message:
              '<div class="title is-3">Atención!</div> <p>Este vehículo posee un evento en progreso, por lo que se continuará trabajando sobre este.</p><p>Si desea no continuar con el evento, seleccione "Crear nuevo evento".</p>',
            confirmText: "Continuar evento",
            cancelText: "Crear nuevo evento",
            onConfirm: () => {
              this.event = response.data;
              this.event.status_code = "previously-initiated";
            },
            onCancel: () => {
              this.createNewEvent();
            },
          });
        } else {
          /* Evento en progreso, se continúa sin preguntar */
          this.event = response.data;
          this.event.status_code = "previously-initiated";
          this.$buefy.dialog.alert({
            message:
              '<div class="title is-3">Atención!</div> <p>Este vehículo posee un evento en progreso, por lo que se continuará trabajando sobre este.</p>',
            confirmText: "Continuar",
          });
        }
      } else {
        this.createNewEvent();
      }
    },
    async createNewEvent() {
      const createdEventData = await this.$store.dispatch(
        "events/createEvent",
        {
          beneficiary_id: this.beneficiary.id,
          vehicle_id: this.vehicle.id
        }
      );
      this.event = createdEventData.data;
      this.event.status_code = "new-event";
      this.notify({
        message: "¡Evento creado!",
        type: "is-success",
      });
    },
    onSelectedBeneficiary(beneficiary) {
      this.beneficiary = beneficiary;
      this.scrollToSection("vehicle-section");
    },
    async onSelectedVehicle(vehicle) {
      this.vehicle = vehicle;
      await this.loadEvent();
      this.toStep("verification-step");
      this.scrollToSection("verification-section");
    },
    async onVerifiedCode() {
      await this.changeEventToInProgress();
      this.verified = true;
      this.toStep("actions-step");
      this.scrollToSection("actions-section");
    },
    continueToQuestions() {
      this.toStep("questions-step");
      this.scrollToSection("questions-section");
    },
    continueToActions() {
      this.toStep("actions-step");
      this.scrollToSection("actions-section");
    },
    continueToSummary() {
      this.toStep("summary-step");
      this.scrollToSection("summary-section");
    },
    toStep(index) {
      this.$nextTick(() => {
        this.activeStep = index;
      });
    },
    async showFinishEventModal() {
      if (this.event) {
        this.$buefy.dialog.confirm({
          icon: "check-circle",
          hasIcon: true,
          message: `El evento quedará finalizado`,
          confirmText: "Aceptar",
          type: "is-success",
          onConfirm: () => this.finishEvent(),
        });
      }
    },
    async finishEvent() {
      if (this.event) {
        const finishEventResponse = await this.$store.dispatch(
          "events/finishEvent",
          this.event.id
        );
        if (finishEventResponse && finishEventResponse.finished) {
          this.$inertia.visit("/");
        } else {
          this.$buefy.dialog.alert({
            icon: "close-thick",
            hasIcon: true,
            message: `No se pudo finalizar el evento.`,
            confirmText: "Aceptar",
            type: "is-danger",
          });
        }
      } else this.$inertia.visit("/");
    },
    async showLeaveEventModal() {
      if (this.event) {
        this.$buefy.dialog.confirm({
          icon: "check-circle",
          hasIcon: true,
          message: `El evento quedará en progreso`,
          confirmText: "Aceptar",
          type: "is-warning",
          onConfirm: () => this.leaveEvent(),
        });
      }
    },
    async leaveEvent() {
      if (this.event) {
        const leaveEventResponse = await this.changeEventToInProgress();
        if (leaveEventResponse && leaveEventResponse.finished) {
          this.$inertia.visit("/");
        } else {
          this.$buefy.dialog.alert({
            icon: "close-thick",
            hasIcon: true,
            message: `No se pudo actualizar el estado del evento.`,
            confirmText: "Aceptar",
            type: "is-danger",
          });
        }
      } else this.$inertia.visit("/");
    },
    scrollToSection(section) {
      this.$nextTick(() => {
        if (this.$refs[section]) this.$refs[section].scrollIntoView();
      });
    },
    async changeEventToInProgress() {
      const leaveEventResponse = await this.$store.dispatch(
        "events/leaveEvent",
        this.event.id
      );
      return leaveEventResponse;
    },
    preventNav(e) {
      if (this.event) {
        e.preventDefault();
        e.returnValue = "";
        this.finishCall();
      } else {
        return true;
      }
    },
    async finishCall() {
      if (this.eventCall) {
        const callFinishTime = new Date().toISOString().replace('T', ' ').replace('Z', '');
        const response = await this.$store.dispatch('eventCalls/finishEventCall', {
          id: this.eventCall.id,
          call_end: callFinishTime
        });
      }
    },
    async startCall() {
      const { data } = await this.$store.dispatch('eventCalls/createEventCall', {
        beneficiary_id: this.event.id,
        event_id: this.beneficiary.id,
        call_start: this.callStartTime
      });
      this.eventCall = data;
    }
  },
  created () {
    this.callStartTime = new Date().toISOString().replace('T', ' ').replace('Z', '');
  },
  beforeMount() {
    window.addEventListener("beforeunload", this.preventNav);
    this.$once("hook:beforeDestroy", () => {
      this.finishCall();
      window.removeEventListener("beforeunload", this.preventNav);
    });
  },
};
</script>
