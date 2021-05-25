<template>
  <div class="timeline-container">
    <div v-if="timelineElements.length" class="timeline is-rtl">
      <header class="timeline-header">
        <span class="tag is-medium is-primary">Ahora</span>
      </header>
      <div
        v-for="(element, index) in timelineElements"
        class="timeline-item-container"
        :key="`element-${index}`"
      >
        <div
          v-if="element.type === 'TASK'"
          class="timeline-item"
        >
          <div class="timeline-marker is-icon">
            <b-icon
              class="m-3"
              :icon="element.icon"
            />
          </div>
          <div class="timeline-content">
            <p class="heading">{{ element.date }}</p>
            <h5 class="is-5 task">{{ `Acción de ${element.name} ejecutada` }}</h5>
          </div>
        </div>
        <div
          v-else-if="element.type === 'ANSWER'"
          :class="['timeline-item', element.class]"
        >
          <div
            :class="['timeline-marker is-icon', element.class]"
          >
            <b-icon :icon="element.icon" />
          </div>
          <div :class="['timeline-content', { 'timeline-content-map' : element.action === 'locate' }]">
            <p class="heading mb-3">{{ element.date }}</p>
            <div :class="['columns', element.answerClass]" v-if="element.action === 'locate'">
              <div class="column is-12">
                <GmapMap
                  :center="
                    {
                      lat: element.answer.lat,
                      lng: element.answer.long,
                    }
                  "
                  :zoom="14"
                  map-type-id="roadmap"
                  class="map timeline-map"
                >
                  <GmapMarker
                    :key="`marker-locate-${index}`"
                    :position="
                      {
                        lat: element.answer.lat,
                        lng: element.answer.long,
                      }
                    "
                  />
                </GmapMap>
              </div>
            </div>
            <h5 v-else-if="element.action === 'turn_on'" :class="['is-5', element.answerClass]">
              {{ `El vehículo ha sido encendido` }}
            </h5>
            <h5 v-else :class="['is-5', element.answerClass]">
              {{ `El vehículo ha sido apagado` }}
            </h5>
          </div>
        </div>
        <div
          v-else
          :class="['timeline-item', element.class]"
        >
          <div
            :class="['timeline-marker is-icon', element.class]"
          >
            <b-icon :icon="element.icon" />
          </div>
          <div class="timeline-content timeline-content-map">
            <p class="heading mb-3">{{ element.date }}</p>
            <div :class="['columns', element.answerClass]">
              <div class="column is-12 gmap-container">
                <GmapMap
                  :center="
                    {
                      lat: element.points[element.points.length - 1].lat,
                      lng: element.points[element.points.length - 1].lng,
                    }
                  "
                  :zoom="14"
                  map-type-id="roadmap"
                  class="map timeline-map"
                >
                  <GmapMarker
                    v-for="(point, i) in element.points"
                    :key="`route-marker-${index}-${i}`"
                    :position="
                      {
                        lat: point.lat,
                        lng: point.lng,
                      }
                    "
                    :options="{
                      opacity: 1 / (element.points.length - (i + 1)),
                    }"
                    @mouseover="showInfo(index, i)"
                    @mouseout="hideInfo(index, i)"
                  />
                  <GmapInfoWindow
                    v-for="(point, i) in element.points"
                    :key="`infowindow-${index}-${i}`"
                    :options="{
                      maxWidth: 300,
                      pixelOffset: { width: 0, height: -35 }
                    }"
                    :position="
                      {
                        lat: point.lat,
                        lng: point.lng,
                      }
                    "
                    :opened="point.infoWindowOpen"
                  >
                    <strong>{{ `Posición ${i + 1}` }}</strong>
                    <br>
                    <strong>
                      {{ moment(point.date).format('D [de] MMMM, YYYY - HH:mm') }}
                    </strong>
                  </GmapInfoWindow>
                  <GmapPolyline
                    :path="element.points"
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
          </div>
        </div>
      </div>
      <div class="timeline-header">
        <span class="tag is-medium is-primary">Inicio</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      timelineElements: [],
      infoWindowOpen: false,
      infoWindowLocation: { lat: 0, lng: 0 },
    };
  },
  methods: {
    pushTaskInTimeline(task) {
      this.timelineElements.unshift({
        id: task.id,
        type: 'TASK',
        action: task.action.code,
        name: task.action.name.toLowerCase(),
        date: this.moment(task.created_at).format('DD/MM/YYYY HH:mm'),
        icon: task.action.code === 'locate'
          ? 'earth'
          : task.action.code === 'turn_on'
            ? 'engine'
            : 'engine-off',
        class: task.action.code === 'locate'
          ? 'is-success'
          : task.action.code === 'turn_on'
            ? 'is-info'
            : 'is-danger',
      });
    },
    pushAnswerInTimeline(answer) {
      this.timelineElements.unshift({
        id: answer.id,
        type: 'ANSWER',
        action: answer.detail.type,
        name: answer.detail.type  === 'locate'
          ? 'localizar'
          : answer.detail.type === 'turn_on'
            ? 'encender'
            : 'apagar',
        date: this.moment(answer.created_at).format('DD/MM/YYYY HH:mm'),
        icon: answer.detail.type === 'locate'
          ? 'earth'
          : answer.detail.type === 'turn_on'
            ? 'engine'
            : 'engine-off',
        class: answer.detail.type === 'locate'
          ? 'is-success'
          : answer.detail.type === 'turn_on'
            ? 'is-info'
            : 'is-danger',
        answerClass: answer.detail.type === 'locate'
          ? 'locate-answer'
          : answer.detail.type === 'turn_on'
            ? 'turn-on-answer'
            : 'turn-off-answer',
        answer: answer.detail.type === 'locate'
          ? { 
              lat: parseFloat(answer.detail.schema.lat),
              long: parseFloat(answer.detail.schema.long),
            }
          : answer.detail,
      });
    },
    showRoute(points) {
      this.timelineElements.unshift({
        id: '',
        type: 'ROUTE',
        action: 'route',
        name: 'recorrido',
        date: this.moment().format('DD/MM/YYYY HH:mm'),
        points: _.cloneDeep(points),
        icon: 'map-marker',
        class: 'is-warning',
        answerClass: 'route-answer',
      });
    },
    showInfo(index, pointIndex) {
      this.timelineElements[index].points[pointIndex].infoWindowOpen = true;
    },
    hideInfo(index, pointIndex) {
      this.timelineElements[index].points[pointIndex].infoWindowOpen = false;
    },
  }
}
</script>

<style scoped>
  .timeline-container {
    min-width: 100%;
    min-height: 300px;
    background-color: #ECEFF1;
    border-radius: 10px;
    max-height: 500px;
    padding: 15px;
    overflow-y: auto;
  }

  .timeline-map {
    height: 250px !important;
  }

  .task {
    padding: 5pt;
    background-color: #f8f8f8;
    border-radius: 5pt;
  }

  .locate-answer {
    background-color: #48c77475;
    font-weight: bold;
    border-radius: 5pt;
  }

  .route-answer {
    background-color: #ffdd5775;
    font-weight: bold;
    border-radius: 5pt;
  }

  .turn-on-answer {
    padding: 5pt;
    background-color: #6cb2eb75;
    font-weight: bold;
    border-radius: 5pt;
  }

  .turn-off-answer {
    padding: 5pt;
    background-color: #e3342f75;
    font-weight: bold;
    border-radius: 5pt;
  }

  .map-wh-name {
    font-size: 0.9rem;
    font-weight: 700;
  }

  .map-wh-text {
    font-size: 0.8rem;
    margin-top: -4px;
  }

  .map-spinner {
    height: 50pt;
    width: 50pt;
  }

  .timeline-item-container {
    width: 95%;
  }

  .timeline-item,
  .timeline-content-map {
    width: 95%;
  }

  .gmap-container,
  .gmap-container .gmap-map {
    height: 100% !important;
  }
</style>
