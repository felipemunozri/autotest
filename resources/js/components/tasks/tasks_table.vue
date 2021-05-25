<template>
  <b-table
    :data="tasks"
    striped
    hoverable
    paginated
    :loading="isLoading"
    :per-page="10"
  >
    <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
    <b-table-column field="action" label="Acción" v-slot="props">
      {{ props.row.action.name }}
    </b-table-column>
    <b-table-column field="vehicle" label="Vehículo" v-slot="props">
      {{ props.row.vehicle.model }}
    </b-table-column>
    <b-table-column field="plate_number" label="Patente" v-slot="props">
      {{ props.row.vehicle.plate_number }}
    </b-table-column>
    <b-table-column field="executor" label="Ejecutado por" v-slot="props">
      {{ `${props.row.executor.name} ${props.row.executor.lastname}` }}
    </b-table-column>
    <b-table-column field="observation" label="Fecha" v-slot="props">
      {{ moment(props.row.created_at).format('D [de] MMMM, YYYY, HH:mm') }}
    </b-table-column>
    <b-table-column label="Respuesta" v-slot="props">
      <answer-dialog v-if="props.row.answers.length" :answer="props.row.answers[0]" />
      <p v-else>No Disponible</p>
    </b-table-column>
  </b-table>
</template>

<script>
import AnswerDialog from '@/components/tasks/answer_dialog';

export default {
  components: { AnswerDialog },
  props: {
    taskFilter: {
      type: String,
      default() {
        return ''
      },
    },
  },
  data() {
    return {
      tasks: [],
      isLoading: false,
    }
  },
  watch: {
    taskFilter: {
      handler(val) {
        this.loadTasks()
      },
    },
  },
  mounted() {
    this.loadTasks();
  },
  methods: {
    async loadTasks() {
      this.isLoading = true;
      const tasksData = await this.$store.dispatch(
        'tasks/getTasks',
        this.taskFilter !== ''
          ? {
              query: {
                detail: this.taskFilter.toUpperCase(),
              },
            }
          : {
              query: {},
          }
      );
      this.tasks = tasksData.data;
      this.isLoading = false;
    },
    toDetails(id) {
      this.$inertia.visit(route('tasks.show', id));
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }
</style>
