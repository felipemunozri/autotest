<template>
  <div>
    <b-button type="is-link" size="is-small" @click="showAnswer">
      Ver Respuesta
    </b-button>
    <b-modal
      v-model="modalShowing"
      has-modal-card
      trap-focus
      :destroy-on-hide="false"
      aria-role="dialog"
      aria-label="Example Modal"
      aria-modal
      scroll="keep"
    >
      <div class="card p-3">
        <div class="card-content">
          <div class="columns">
            <div class="columns is-vcentered">
              <div class="column is-12">
                <h5 class="title is-5">Fecha de la respuesta: {{ this.moment(answer.created_at).format('D [de] MMMM, YYYY, HH:mm') }}</h5>
              </div>
            </div>
          </div>
          <div class="columns" v-if="answer.detail.type === 'locate'">
            <div class="column is-8">
              <div class="columns">
                <div class="column is-12">
                  <GmapMap
                    :center="{ lat: answerDetail.latitude, lng: answerDetail.longitude }"
                    :zoom="14"
                    map-type-id="roadmap"
                    class="map"
                  >
                    <GmapMarker
                      key="marker"
                      :position="{ lat: answerDetail.latitude, lng: answerDetail.longitude }"
                    />
                  </GmapMap>
                </div>
              </div>
            </div>
            <div class="column is-4">
              <div class="answer-attribute columns is-vcentered mt-1">
                <div class="column is-7">
                  <h5 class="title is-5">Control de crucero:</h5>
                </div>
                <div class="column is-5">
                  <b-icon
                    icon="car-cruise-control"
                    :class="answerDetail.cruiseControl ? 'on' : 'off'"
                    size="is-large"
                  /><br />
                  <small>{{ answerDetail.cruiseControl ? 'Encendido' : 'Apagado' }}</small>
                </div>
              </div>
              <div class="answer-attribute columns is-vcentered mt-5">
                <div class="column is-7">
                  <h5 class="title is-5">Puerta:</h5>
                </div>
                <div class="column is-5">
                  <b-icon
                    :icon="answerDetail.door ? 'car-door' : 'car-door-lock'"
                    :class="answerDetail.door ? 'on' : 'off'"
                    size="is-large"
                  /><br />
                  <small>{{ answerDetail.door ? 'Abierta' : 'Cerrada' }}</small>
                </div>
              </div>
              <div class="answer-attribute columns is-vcentered mt-5">
                <div class="column is-7">
                  <h5 class="title is-5">Poder:</h5>
                </div>
                <div class="column is-5">
                  <b-icon
                    :icon="answerDetail.power ? 'power-plug' : 'power-plug-off'"
                    :class="answerDetail.power ? 'on' : 'off'"
                    size="is-large"
                  /><br />
                  <small>{{ answerDetail.power ? 'Encendido' : 'Apagado' }}</small>
                </div>
              </div>
              <div class="answer-attribute columns is-vcentered mt-5">
                <div class="column is-7">
                  <h5 class="title is-5">Velocidad:</h5>
                </div>
                <div class="column is-5">
                  <h5 class="title is-5">{{ answerDetail.velocity }} km/h</h5>
                </div>
              </div>
            </div>
          </div>
          <div v-else-if="answer.detail.type === 'turn_on'" class="has-text-centered">
            <b-icon icon="check-circle" size="is-large" /><br />
            <h4 class="title is-4 mt-5">Se ha encendido la corriente.</h4>
          </div>
          <div v-else class="has-text-centered">
            <b-icon icon="check-circle" size="is-large" /><br />
            <h4 class="title is-4 mt-5">Se ha cortado la corriente.</h4>
          </div>
          <div class="columns">
            <div class="column">
              <b-button class="is-pulled-right" @click="hideAnswer">
                Cerrar
              </b-button>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
export default {
  props: {
    answer: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      modalShowing: false,
    }
  },
  computed: {
    answerDetail() {
      if (!this.answer.detail) return {};
      const detail = {
        cruiseControl: this.answer.detail.schema.acc === 'ON' ? true : false,
        door: this.answer.detail.schema.door === 'ON' ? true : false,
        power: this.answer.detail.schema.pwr === 'ON' ? true : false,
        velocity: parseFloat(this.answer.detail.schema.speed),
        latitude: parseFloat(this.answer.detail.schema.lat),
        longitude: parseFloat(this.answer.detail.schema.long),
        date: this.moment(this.answer.detail.schema.timestamp).format('D [de] MMMM, YYYY, HH:mm'),
      }
      return detail;
    },
  },
  methods: {
    showAnswer() {
      this.modalShowing = true;
    },
    hideAnswer() {
      this.modalShowing = false;
    },
  },
}
</script>

<style scoped>
  .answer-attribute {
    margin-left: auto;
    margin-right: auto;
    background-color: #dadada;
    border-radius: 5px;
  }

  .on {
    color: green;
  }

  .off {
    color: red;
  }

  .map {
    width: 430pt;
    height: 280pt;
  }
</style>
