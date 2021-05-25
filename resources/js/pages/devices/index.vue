<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">DISPOSITIVOS</h1>
        </div>
        <div class="column is-narrow">
          <b-button type="is-link" @click="toCreate" icon-left="plus"  v-if="$users.isAllowed('create')">
            AÑADIR DISPOSITIVO
          </b-button>
        </div>
      </div>
      <div class="columns ml-1 mt-2">
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
                v-for="(deviceStatus, index) in deviceStates"
                :key="index"
                :value="deviceStatus.id"
                aria-role="listitem"
              >
                <span>{{ deviceStatus.description }}</span>
              </b-dropdown-item>
            </b-dropdown>
            <p class="control">
              <b-button @click="clearStates" title="Borrar" icon-left="close" :disabled="!selectedStates.length" />
            </p>
          </b-field>
        </div>
      </div>
      <card title="Listado de dispositivos" class="card-table" :header-search-input="true" @change-input-search="keyword = $event">
        <div slot="content">
          <devices-table :filter="filter"/>
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import DevicesTable from '@/components/devices/table'
import { UserPerimeter } from '@/perimeters'

export default {
  components: { Card, DevicesTable },
  perimeters: [
    UserPerimeter,
  ],
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
      keyword: null,
      searchText: null,
      deviceStates: [],
      selectedStates: [],
    }
  },
  computed: {
    filter() {
      return {
        query: {
          serial_number: this.searchText === '' ?  null : this.searchText,
          status: this.selectedStates.length ? this.selectedStates : null,
        },
      }
    },
  },
  watch: {
    keyword: {
      handler(val) {
        this.updateSearchText()
      },
    },
  },
  async created() {
    await this.loadDevicesStates();
    if (this.urlFilter) {
      if (this.urlFilter === 'activated') {
        this.deviceStates.forEach(state => {
          if (state.name === 'activated') this.selectedStates.push(state.id);
        });
        console.log(this.urlFilter);
      } else if (this.urlFilter === 'disabled') {
        this.deviceStates.forEach(state => {
          if (state.name === 'disabled') this.selectedStates.push(state.id);
        });
      }
    }
  },
  methods: {
    async loadDevicesStates() {
      const { data } = await this.$store.dispatch('deviceStatus/getAllStatus', {});
      this.deviceStates = data;
    },
    updateSearchText: _.debounce(function () {
      this.searchText = this.keyword;
    }, 400),
    clearStates() {
      this.selectedStates = [];
    },
    toCreate() {
      this.$inertia.visit(route('devices.create'));
    },
  }
}
</script>