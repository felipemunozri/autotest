<template>
  <div>
    <div class="columns">
        <div class="column">
          <h1 class="title has-text-left">Panel de Acciones</h1>
        </div>
        <div class="is-narrow">
          <h1 class="title is-5 has-text-rigth">{{ balance }}</h1>
          <div class="subtitle is-5">Saldo de Dispositivo</div>
        </div>
    </div>
    <div class="columns">
      <div class="column is-one-fifth">
        <h1 class="title is-6">Acciones</h1>
        <div class="buttons">
          <b-button
            v-for="button in actionsButtons"
            @click="button.onClick(button)"
            :icon-left="button.icon"
            :type="button.type"
            :key="`button-${button.code}`"
            :disabled="button.deactivated || !verified"
            expanded
          >
            {{ button.name }}
          </b-button>
        </div>
      </div>
      <div class="column">
        <h1 class="title is-6">Historial de acciones</h1>
        <tasks-timeline ref="tasks-timeline" />
      </div>
    </div>
  </div>
</template>

<script>
import TasksTimeline from "@/components/events/tasks_timeline";
export default {
  components: {
    TasksTimeline,
  },
  props: {
    vehicleId: {
      type: String,
      required: true,
    },
    simcardId: {
      type: String,
      required: true,
    },
    eventId: {
      type: String,
      requiered: true,
    },
    initialTasks: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      verified: true,
      tasks: [],
      answers: [],
      points: [],
      actionsButtons: [
        {
          code: "locate",
          name: "Localizar",
          icon: "earth",
          type: "is-success",
          deactivated: false,
          onClick: (action) => this.confirmAction(action),
        },
        {
          code: "turn_off",
          name: "Apagar",
          icon: "engine-off",
          type: "is-danger",
          deactivated: false,
          onClick: (action) => this.confirmAction(action),
        },
        {
          code: "turn_on",
          name: "Encender",
          icon: "engine",
          type: "is-info",
          deactivated: false,
          onClick: (action) => this.confirmAction(action),
        },
        {
          code: "route",
          name: "Ver Recorrido",
          icon: "map-marker",
          type: "is-warning",
          deactivated: false,
          onClick: (action) => this.showRoute(),
        },
      ],
      simcard: null
    };
  },
  computed:{ 
    balance () {
      return this.simcard ? `$${this.simcard.balance}` :  '$0'
    }
  },
  methods: {
    confirmAction(action) {
      this.$buefy.dialog.confirm({
        title: "Confirmar Acción",
        icon: action.icon,
        hasIcon: true,
        message: `¿Está seguro que desea realizar la acción de <b>${action.name}</b> vehículo?`,
        cancelText: "Cancelar",
        confirmText: "Aceptar",
        type: action.type,
        onConfirm: () => this.doAction(action.code),
      });
    },
    async doAction(code) {
      try {
        let actionButton = this.actionsButtons.find((x) => x.code === code);
        if (!actionButton) return false;
        actionButton.deactivated = true;

        const response = await this.$store.dispatch("actions/send", {
          code,
          vehicleId: this.vehicleId,
          eventId: this.eventId,
        });

        const task = _.cloneDeep(response.data);
        this.tasks.push(task);
        this.$nextTick(() => {
          if(this.$refs["tasks-timeline"]) this.$refs["tasks-timeline"].pushTaskInTimeline(task);
        });
        this.notify({
          message: "Se ha realizado la acción exitosamente.",
          type: "is-success",
        });
      } catch (error) {
        console.log(error);
        this.notify({
          message: "No se pudo realizar la acción.",
          type: "is-danger",
        });
      }
      setTimeout(() => {
        let actionButton = this.actionsButtons.find((x) => x.code === code);
        actionButton.deactivated = false;
      }, 4000);
    },
    async fetchSimCard() {
      if (this.simcardId) {
        const { data } = await this.$store.dispatch('simCards/getSimCard', this.simcardId);
        this.simcard = data;
      }
    },
    showRoute() {
      this.$nextTick(() => {
        if(this.$refs["tasks-timeline"])this.$refs["tasks-timeline"].showRoute(this.points);
      })
    },
    initAnswersListener() {
      Echo.channel(`event.${this.eventId}`).listen(".new-answer", (e) => {
        if (e.task_answer) {
          const answer = _.cloneDeep(e.task_answer);
          answer.type = "ANSWER";
          this.$nextTick(() => {
            this.receiveAnswer(e.task_answer);
            this.fetchSimCard();
          });
        }
      });
    },
    receiveAnswer(answer) {
      this.answers.push(answer);
      if(this.$refs["tasks-timeline"])this.$refs["tasks-timeline"].pushAnswerInTimeline(answer);
      if (answer.detail.type === "locate") {
        this.points.push({
          lat: parseFloat(answer.detail.schema.lat),
          lng: parseFloat(answer.detail.schema.long),
          infoWindowOpen: false,
          date: new Date(answer.created_at),
        });
      }
    },
  },
  mounted() {
    this.initAnswersListener();
    this.tasks = this.initialTasks;
    this.fetchSimCard();
    this.$nextTick(() => {
      this.tasks.forEach((task) => {
        if(this.$refs["tasks-timeline"]) this.$refs["tasks-timeline"].pushTaskInTimeline(task);
        if (task.answers) {
          task.answers.forEach((answer) => {
            this.receiveAnswer(answer);
          });
        }
      });
    })
  },
  beforeDestroy() {
    if (this.event) {
      Echo.leave(`event.${this.eventId}`);
    }
  },
};
</script>

<style lang="scss" scoped>
</style>