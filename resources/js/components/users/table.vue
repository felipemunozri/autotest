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
    <b-table-column field="dni" label="RUN" v-slot="props">
      {{ runFormatting(props.row.dni) }}
    </b-table-column>
    <b-table-column field="name" label="Nombre" v-slot="props">
      {{ props.row.name }}
    </b-table-column>
    <b-table-column field="lastname" label="Apellido" v-slot="props">
      {{ props.row.lastname }}
    </b-table-column>
    <b-table-column field="email" label="Correo" v-slot="props">
      {{props.row.email}}
    </b-table-column>
    <b-table-column field="email" label="Perfil" v-slot="props">
      <p class="is-capitalized">{{ props.row.roles && props.row.roles[0] ? props.row.roles[0].description : null }}</p>
    </b-table-column>
    <b-table-column field="created_at" label="Creado" v-slot="props">
      {{ props.row.created_at | moment('D [de] MMMM, YYYY') }}
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
      this.filter.query.per_page = this.perPage;
      this.filter.query.page = this.currentPage;
      const { data, meta } = await this.$store.dispatch(
        'users/getUsers',
        this.filter
      );
      this.data = data;
      this.total = meta.total;
      this.isLoading = false;
    },
    toDetails(id) {
      this.$inertia.visit(route('users.edit', id));
    },
  },
}
</script>