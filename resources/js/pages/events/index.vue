<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">HISTORIAL DE EVENTOS</h1>
        </div>
      </div>
      <div class="columns ml-1 mt-2">
        <div class="column is-narrow">
          <b-field label="Desde">
            <b-datepicker
              v-model="from"
              :max-date="to"
              locale="es-Es"
              placeholder="Click para seleccionar..."
              icon="calendar-today"
              trap-focus
            >
            </b-datepicker>
            <p class="control">
              <b-button @click="clearFrom" title="Borrar" icon-left="close" :disabled="!from" />
            </p>
          </b-field>
        </div>
        <div class="column is-narrow">
          <b-field class="ml-2" label="Hasta">
            <b-datepicker
              v-model="to"
              :min-date="from"
              locale="es-Es"
              placeholder="Click para seleccionar..."
              icon="calendar-today"
              trap-focus
            >
            </b-datepicker>
            <p class="control">
              <b-button @click="clearTo" title="Borrar" icon-left="close" :disabled="!to" />
            </p>
          </b-field>
        </div>
        <div class="column is-narrow">
          <b-field class="ml-2" label="Estado">
            <b-dropdown
              v-model="selectedStates"
              multiple
              aria-role="list"
            >
              <template #trigger>
                <b-button
                  type="is-primary"
                  icon-right="menu-down"
                  expanded
                >
                  {{
                    `${selectedStates.length} Seleccionado${selectedStates.length === 1 ? '' : 's'}`
                  }}
                </b-button>
              </template>

              <b-dropdown-item
                v-for="(eventStatus, index) in eventStates"
                :key="index"
                :value="eventStatus.id"
                aria-role="listitem"
              >
                <span>{{ eventStatus.name }}</span>
              </b-dropdown-item>
            </b-dropdown>
            <p class="control">
              <b-button @click="clearStates" title="Borrar" icon-left="close" :disabled="!selectedStates.length" />
            </p>
          </b-field>
        </div>
      </div>
      <card title="Listado de eventos" class="card-table" :header-search-input="true" @change-input-search="search($event)">
        <div slot="content">
          <events-table :eventFilter="filter" />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import EventsTable from '@/components/events/table'

export default {
  components: { Card, EventsTable },
  props: {
    urlFilter: {
      type: String,
      default() {
        return route().params.urlFilter;
      },
    },
  },
  data() {
    return {
      tableFilter: null,
      from: null,
      to: null,
      eventStates: [],
      selectedStates: [],
    }
  },
  computed: {
    filter() {
      return {
        to: this.to ? this.to.toISOString() : null,
        from: this.from ? this.from.toISOString() : null,
        plate_number: this.tableFilter ? this.tableFilter : null,
        dni: this.tableFilter ? this.tableFilter.replaceAll('.', '') : null,
        name: this.tableFilter ? this.tableFilter : null,
        status: this.selectedStates.length ? this.selectedStates : null,
      };
    },
  },
  created() {
    if (this.urlFilter) {
      if (this.urlFilter === 'last_month') {
        var date = new Date();
        this.from = new Date(date.getFullYear(), date.getMonth(), 1);
      } else if (this.urlFilter === 'last_week') {
        var date = new Date();
        this.from = new Date(date.setDate(date.getDate() - date.getDay() + 1));
      }
    }
  },
  async mounted() {
    await this.loadEventsStates();
  },
  methods: {
    async loadEventsStates() {
      const response = await this.$store.dispatch('eventStates/getEventsStates', {});
      this.eventStates = response.data;
    },
    search: _.debounce(function (text) {
      this.tableFilter = text;
    }, 400),
    clearFrom() {
      this.from = null;
    },
    clearTo() {
      this.to = null;
    },
    clearStates() {
      this.selectedStates = [];
    },
  }
}
</script>