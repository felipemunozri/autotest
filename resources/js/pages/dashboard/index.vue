<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">DASHBOARD</h1>
        </div>
      </div>
      <div class="columns">
        <div class="column is-8">
          <div class="card map-container">
            <events-map/>
          </div>
        </div>
        <div class="column is-4">
          <div class="box tenant-box p-5">
            <div class="columns mt-1 ml-2"><small>Organización</small></div>
            <div class="columns component has-icon is-vcentered mt-1 ml-2 mb-1">
              <b-icon icon="domain" />
              <span class="menu-item-label title is-5 ml-4">{{ currentTenant.name }}</span>
            </div>
          </div>
          <div v-if="summary.events" class="box p-5">
            <div class="columns component has-icon is-vcentered">
              <b-icon icon="alert" />
              <span class="menu-item-label title is-5 ml-4">Número de Eventos</span>
            </div>
            <div class="columns is-vcentered mt-1 is-mobile">
              <a class="column is-4" @click="toEvents('')">
                <small>Total</small><br />
                <h5 class="title is-5">{{ summary.events.all ? summary.events.all : 0 }}</h5>
              </a>
              <a class="column is-4" @click="toEvents('last_month')">
                <small>Este mes</small><br />
                <h5 class="title is-5">{{ summary.events.monthly ? summary.events.monthly : 0 }}</h5>
              </a>
              <a class="column is-4" @click="toEvents('last_week')">
                <small>Esta semana</small><br />
                <h5 class="title is-5">{{ summary.events.weekly ? summary.events.weekly : 0 }}</h5>
              </a>
            </div>
          </div>
          <div v-if="summary.devices" class="box p-5">
            <div class="columns component has-icon is-vcentered">
              <b-icon icon="cellphone" />
              <span class="menu-item-label title is-5 ml-4">Número de Dispositivos</span>
            </div>
            <div class="columns is-vcentered mt-1 is-mobile">
              <a class="column is-4" @click="toDevices('')">
                <small>Total</small><br />
                <h5 class="title is-5">{{ devicesTotal }}</h5>
              </a>
              <a class="column is-4" @click="toDevices('activated')">
                <small>Activos</small><br />
                <h5 class="title is-5">{{ summary.devices.activated ? summary.devices.activated : 0 }}</h5>
              </a>
              <a class="column is-4" @click="toDevices('disabled')">
                <small>Inactivos</small><br />
                <h5 class="title is-5">{{ summary.devices.disabled ? summary.devices.disabled : 0 }}</h5>
              </a>
            </div>
          </div>
          <div class="box p-5">
            <div class="columns component has-icon is-vcentered">
              <b-icon icon="book-open-blank-variant"  />
              <span class="menu-item-label title is-5 ml-4">Directorio</span>
            </div>
            <div class="columns is-vcentered mt-1 is-mobile">
              <div class="column is-narrow">
                <h5 class="title is-5">103</h5>
              </div>
              <div class="column">
                <small>Informaciones Generales</small>
              </div>
            </div>
            <div class="columns is-vcentered is-mobile">
              <div class="column is-narrow">
                <h5 class="title is-5">133</h5>
              </div>
              <div class="column">
                <small>Carabineros de Chile</small>
              </div>
            </div>
            <div class="columns is-vcentered is-mobile">
              <div class="column is-narrow">
                <h5 class="title is-5">134</h5>
              </div>
              <div class="column">
                <small>Policía de Investigaciones de Chile</small>
              </div>
            </div>
            <div class="columns is-vcentered is-mobile">
              <div class="column is-narrow">
                <h5 class="title is-5">132</h5>
              </div>
              <div class="column">
                <small>Bomberos</small>
              </div>
            </div>
            <div class="columns is-vcentered is-mobile">
              <div class="column is-narrow">
                <h5 class="title is-5">131</h5>
              </div>
              <div class="column">
                <small>Ambulancia</small>
              </div>
            </div>
            <div class="columns is-vcentered is-mobile">
              <div class="column is-narrow">
                <h5 class="title is-5">136</h5>
              </div>
              <div class="column">
                <small>Socorro Andino</small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <vehicle-models-ranking></vehicle-models-ranking>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import EventsMap from "@/components/dashboard/events_map.vue"
import VehicleModelsRanking from '@/components/dashboard/vehicle_models_ranking'

export default {
  components: { 
    EventsMap,
    VehicleModelsRanking
  },
  data() {
    return {
      summary: {},
      devicesTotal: 0,
    }
  },
  async mounted() {
    const response = await this.$store.dispatch('summary/getDashboard', {});
    this.summary = response;
  },
  computed: {
    currentTenant() {
      return this.$store.state.currentTenant || { name: null };
    },
  },
  watch: {
    'summary.devices': {
      handler(val) {
        let aux = 0;
        for (const stat in val) {
          aux += val[stat];
        }
        this.devicesTotal = aux;
      },
    },
  },
  methods: {
    toEvents(query) {
      this.$inertia.visit(route('events.index', { urlFilter: query }));
    },
    toDevices(query) {
      this.$inertia.visit(route('devices.index', { urlFilter: query }));
    },
  },
}
</script>

<style scoped>
  .map-container .map {
    margin-left: auto;
    margin-right: auto;
  }

  .tenant-box,
  .tenant-box .columns,
  .tenant-box .title {
    background-color: rgb(40, 115, 255);
    color: white;
    text-align: center;
  }

  .dashboard-element {
    margin-left: auto;
    margin-right: auto;
    background-color: white;
    border-radius: 5px;
  }
</style>
