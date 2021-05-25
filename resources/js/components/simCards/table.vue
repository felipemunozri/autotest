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
    <b-table-column field="phone_number" label="Número de Serie" v-slot="props">
      {{ props.row.phone_number }}
    </b-table-column>
    <b-table-column field="carrier_id" label="Carrier" v-slot="props">
      <span v-if="props.row.carrier">{{ props.row.carrier.name }}</span>
    </b-table-column>
    <b-table-column field="country_id" label="País" v-slot="props" centered>
      <span v-if="props.row.country">{{ props.row.country.name }}</span>
    </b-table-column>
    <b-table-column field="sim_card_status_id" label="Estado" v-slot="props" centered>
      <b-tag :type="props.row.status && props.row.status.name == 'installed' ? 'is-success' : 'is-light'" v-if="props.row.status">{{props.row.status.description}}</b-tag>
    </b-table-column>
    <b-table-column field="created_at" label="Creado" v-slot="props" centered>
      {{ props.row.created_at | moment('D [de] MMMM, YYYY') }}
    </b-table-column>
    <b-table-column field="balance" label="Saldo" v-slot="props" numeric>
      <span v-if="props.row.balance">${{ numberFormat(props.row.balance) }}</span>
    </b-table-column>
    <b-table-column v-slot="props">
      <div class="buttons">
          <b-button
            size="is-small"
            type="is-success"  @click="toDetails(props.row.id)" v-if="$users.isAllowed('edit')">
            <b-icon size="is-small" icon="pencil"></b-icon>
          </b-button>
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
        'carrier',
        'country',
        'status'
      ];
      this.filter.query.per_page = this.perPage;
      this.filter.query.page = this.currentPage;
      const { data, meta } = await this.$store.dispatch(
        'simCards/getSimCards',
        this.filter
      );
      this.data = data;
      this.total = meta.total;
      this.isLoading = false;
    },
    toDetails(id) {
      this.$inertia.visit(route('simcard.edit', id));
    },
  },
}
</script>