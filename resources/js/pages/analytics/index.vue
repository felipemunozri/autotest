<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">Estadisticas</h1>
        </div>
        <div class="column is-narrow">
          <date-selector
            :selected-from-date.sync="filters.startDate"
            :selected-to-date.sync="filters.finishDate"
          />
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-half">
          <embeddable-statistics-card
            title="Últimos Eventos"
            :routeName="'public.embeddable.latest-events'"
            :filters="urlFilter"
            :showOptions="true"
          />
        </div>
        <div class="column is-half">
          <embeddable-statistics-card
            title="Eventos por día"
            :routeName="'public.embeddable.events-per-day'"
            :filters="urlFilter"
            :showOptions="true"
          />
        </div>
        <div class="column">
          <embeddable-statistics-card
            title="Eventos por hora"
            :routeName="'public.embeddable.events-per-hour'"
            :filters="urlFilter"
            :showOptions="true"
          />
        </div>
        <div class="column">
          <embeddable-statistics-card
            title="Top 10 vehículos más robados"
            :routeName="'public.embeddable.events-per-model'"
            :filters="urlFilter"
            :showOptions="true"
          />
        </div>
        <div class="column is-narrow">
          <embeddable-statistics-card
            title="Estado final de evento"
            :routeName="'public.embeddable.events-status-result'"
            :filters="urlFilter"
            :showOptions="true"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Card from "@/components/common/card";
import DevicesTable from "@/components/devices/table";
import EmbeddableIframe from "@/components/common/embeddableIframe";
import DateSelector from "@/components/common/controls/dateSelector";
import AnalyticsFiltersMixin from "@/mixins/filters/analytics";
import EmbeddableStatisticsCard from "../../components/common/embeddableStatisticsCard.vue";

export default {
  components: {
    Card,
    DevicesTable,
    EmbeddableIframe,
    DateSelector,
    EmbeddableStatisticsCard,
  },
  mixins: [AnalyticsFiltersMixin],
  perimeters: [],
  data() {
    return {};
  },
};
</script>