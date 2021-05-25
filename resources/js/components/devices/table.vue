<template>
  <b-table
    :data="data"
    striped
    hoverable
    paginated
    backend-pagination
    :total="total"
    :loading="isLoading"
    :per-page="perPage"
    :current-page.sync="currentPage"
    @page-change="fetchData"
  >
    <b-table-column field="serial_number" label="Número de Serie" v-slot="props">
      {{ props.row.serial_number }}
    </b-table-column>
    <b-table-column field="model_name" label="Modelo" v-slot="props">
      {{ props.row.model.name }}
    </b-table-column>
    <b-table-column field="phone_number" label="Télefono" v-slot="props">
      <span v-if="props.row.sim_card">{{ props.row.sim_card.phone_number }}</span>
    </b-table-column>
    <b-table-column field="status" label="Estado" v-slot="props" centered>
      <b-tag :type="props.row.status && props.row.status.name == 'activated' ? 'is-success' : 'is-light'" v-if="props.row.status">{{props.row.status.description}}</b-tag>
    </b-table-column>
    <b-table-column field="created_at" label="Creado" v-slot="props" centered>
      {{ props.row.created_at | moment('D [de] MMMM, YYYY') }}
    </b-table-column>
    <b-table-column field="balance" label="Saldo" v-slot="props" numeric>
      <span v-if="props.row.sim_card">${{ numberFormat(props.row.sim_card.balance) }}</span>
    </b-table-column>
    <b-table-column v-slot="props">
      <div class="buttons">
          <b-button
            size="is-small"
            type="is-success"  @click="toDetails(props.row.id)" v-if="$users.isAllowed('edit')">
            <b-icon size="is-small" icon="pencil"></b-icon>
          </b-button>
          <!-- <b-button
            size="is-small"
            type="is-danger">
            <b-icon size="is-small" icon="close"></b-icon>
          </b-button> -->
      </div>
    </b-table-column>
  </b-table>
</template>

<script>
import { Formatters } from "@/mixins/formatters";
import { UserPerimeter } from '@/perimeters';

export default {
  mixins: [Formatters()],
  perimeters: [
    UserPerimeter
  ],
  props: {
    filter: {
      type: Object,
      default: () => ({ query: {} }),
    },
  },
  data() {
    return {
      data: [],
      isLoading: false,
      currentPage: 1,
      perPage: 10,
      total: 0,
    }
  },
  watch: {
    filter: {
      handler(val) {
        this.fetchData()
      },
    },
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.isLoading = true;
      this.filter.query.include = [
        'vehicle',
        'simCard',
        'model',
        'status'
      ];
      this.filter.query.per_page = this.perPage;
      this.filter.query.page = this.currentPage;
      const { data, meta } = await this.$store.dispatch(
        'devices/getDevices',
        this.filter
      );
      this.data = data;
      this.total = meta.total;
      this.isLoading = false;
    },
    toDetails(id) {
      this.$inertia.visit(route('devices.edit', id));
    },
  },
}
</script>