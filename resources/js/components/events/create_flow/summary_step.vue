<template>
  <div class="columns is-multiline">
    <div class="column is-full">
      <h1 class="title has-text-left">Resumen de evento</h1>
    </div>
    <div class="column is-half">
      <div>
        <b-field
          class="mb-0"
          custom-class="has-text-left is-flex-grow-2"
          horizontal
          label="Fecha de Inicio"
        >
          <span class="is-size-6">{{
            event.created_at | moment("D [de] MMMM, YYYY [a las] HH:mm")
          }}</span>
        </b-field>
        <b-field
          class="mb-0"
          custom-class="has-text-left is-flex-grow-2"
          horizontal
          label="Origen de Evento"
        >
          <span class="is-size-6">{{ address }}</span>
        </b-field>
        <b-field
          class="mb-0"
          custom-class="has-text-left is-flex-grow-2"
          horizontal
          label="Estado"
        >
          <span class="is-size-6">{{ status }}</span>
        </b-field>
      </div>
    </div>
    <div class="column is-half">
      <GmapMap
        :center="center"
        :zoom="14"
        map-type-id="roadmap"
        style="height: 250px"
        class="map"
      >
        <GmapMarker
          v-for="(point, i) in points"
          :key="`route-marker-${i}`"
          :position="point.location"
          :options="{
            opacity: 1 / (pointsCount - (i + 1)),
          }"
          @mouseover="showInfo(i)"
          @mouseout="hideInfo(i)"
        />
        <GmapInfoWindow
          v-for="(point, i) in points"
          :key="`infowindow-${i}`"
          :options="{
            maxWidth: 300,
            pixelOffset: { width: 0, height: -35 },
          }"
          :position="point.location"
          :opened="point.infoWindowOpen"
        >
          <strong>{{ `Posición ${i + 1}` }}</strong>
          <br />
          <strong>
            {{ moment(point.date).format("D [de] MMMM, YYYY - HH:mm") }}
          </strong>
        </GmapInfoWindow>
        <GmapPolyline
          :path="points.map((x) => x.location)"
          :options="{
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 0.2,
            strokeWeight: 3.0,
          }"
        >
        </GmapPolyline>
      </GmapMap>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    eventId: {
      type: String,
    },
  },
  data() {
    return {
      map: {
        center: {},
      },
      event: {
        created_at: null,
        origin: null,
        status: null,
      },
      points: [],
    };
  },
  methods: {
    async fetchData() {
      const response = await this.$store.dispatch(
        "events/getEvent",
        this.eventId
      );

      this.event = response.data;
      this.points = this.event.tasks
        .filter((x) => x.action.code === "locate")
        .reduce((accumulator, task) => {
          const taskPoints = task.answers
            .filter((k) => k.detail.type === "locate")
            .map((answer) => {
              return {
                location: {
                  lat: parseFloat(answer.detail.schema.lat),
                  lng: parseFloat(answer.detail.schema.long),
                },
                date: new Date(answer.created_at),
                infoWindowOpen: false,
              };
            });
          return accumulator.concat(taskPoints);
        }, []);
    },
    showInfo(pointIndex) {
      this.points[pointIndex].infoWindowOpen = true;
    },
    hideInfo(pointIndex) {
      this.points[pointIndex].infoWindowOpen = false;
    },
  },
  computed: {
    status() {
      return this.event.status ? this.event.status.name : "";
    },
    center() {
      return this.points && this.pointsCount
        ? this.points[this.pointsCount - 1].location
        : { lat: 0, lng: 0 };
    },
    pointsCount() {
      return this.points ? this.points.length : 0;
    },
    address() {
      return this.event && this.event.origin ? this.event.origin.address : null;
    },
  },
  mounted() {
    this.fetchData();
  },
};
</script>