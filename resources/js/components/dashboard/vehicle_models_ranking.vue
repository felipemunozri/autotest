<template>
  <div class="card card-table">
    <header class="card-header">
      <p class="card-header-title">
        <b-icon icon="car-multiple" />
        &nbsp; Vehículos más reportados
      </p>
      <!-- <b-button
        class="card-header-icon m-2"
        type="is-primary"
        @click="downloadDocument">
        <b-icon icon="file-download" />
      </b-button> -->
      <a
        :href="downloadDocumentRoute"
        class="button is-primary card-header-icon m-2"
        download
      >
        <b-icon icon="file-download" />
      </a>
    </header>
    <div class="card-content">
      <div class="content">
        <b-table
          :data="data"
          striped
          hoverable
          :paginated="false"
          :loading="isLoading"
          :per-page="perPage"
        >
          <b-table-column field="brand" label="Marca" v-slot="props">
            {{ props.row.brand }}
          </b-table-column>
          <b-table-column field="model" label="Modelo" v-slot="props">
            {{ props.row.model }}
          </b-table-column>
          <b-table-column
            field="vs"
            label="Recuperado / No Recuperado"
            v-slot="props"
          >
            <span
              >{{
                props.row.retrieved +
                props.row.retrieved_with_damage +
                props.row.total_loss
              }}
              / {{ props.row.not_retrieved }}</span
            >
          </b-table-column>
          <b-table-column
            field="issues"
            label="Total de Reportes"
            v-slot="props"
          >
            {{ props.row.total }}
          </b-table-column>
          <b-table-column field="last_month" label="Este Mes" v-slot="props">
            {{ props.row.last_month }}
          </b-table-column>
        </b-table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';
import Card from "@/components/common/card";

export default {
  components: {
    Card,
  },
  data() {
    return {
      data: [],
      isLoading: false,
      perPage: 10,
    };
  },
  computed:{
    ...mapState([
      'currentTenant'
    ]),
    downloadDocumentRoute() {
     return route('api.exports.vehicle-ranking.pdf', {
          tenant:this.currentTenant.id,
        })
    }
  },
  methods: {
    async fetchData() {
      this.isLoading = true;
      const { data } = await this.$store.dispatch("summary/getVehicleRanking", {
        page: 1,
        per_page: this.perPage,
      });
      this.data = data;
      this.isLoading = false;
    },
    async downloadDocument(){
      const response = await this.$store.dispatch("summary/downloadVehicleRanking", {
        tenant: this.currentTenant.id
      });
      console.log(response)
      let blob = new Blob([response.data], { type: 'application/pdf' });
      var fileURL = window.URL.createObjectURL(blob);
      var fileLink = document.createElement('a');
      fileLink.href = fileURL;
      fileLink.setAttribute('download', `Ranking_Vehículos_${this.$moment().format('LL')}.pdf`);
      document.body.appendChild(fileLink);
      fileLink.click();
    }
  },
  mounted() {
    this.fetchData();
  },
};
</script>
