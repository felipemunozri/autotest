<template>
  <div class="columns is-multiline">
    <div class="column is-full">
      <h1 class="title has-text-left">Reporte del evento</h1>
    </div>
    <div class="column is-full">
      <ValidationObserver v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(() => {})">
          <ValidationProvider
            v-slot="{ errors }"
            name="ubicación de evento"
            rules=""
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Ubicación de evento"
            >
              <input type="hidden" v-model="origin.address" />
              <gmap-autocomplete
                ref="autocomplete"
                @place_changed="changeEventLocation"
                :value="origin.address"
                :class="['input', { 'is-danger': errors[0] }]"
                :disabled="questionsAnswered"
              ></gmap-autocomplete>
            </b-field>
          </ValidationProvider>
          <b-field>
            <GmapMap
              :center="
                origin && origin.location
                  ? origin.location
                  : { lat: -38.7410298, lng: -72.6269321 }
              "
              :zoom="15"
              map-type-id="roadmap"
              style="width: 100%; height: 250px"
            >
              <GmapMarker
                v-if="origin"
                key="marker"
                :draggable="!questionsAnswered"
                @dragend="manualPositionChange"
                :position="origin.location"
              />
            </GmapMap>
          </b-field>
          <ValidationProvider
            v-slot="{ errors }"
            name="Hora de Inicio"
            rules=""
            class="is-block field"
          >
            <b-field
              horizontal
              custom-class="has-text-left is-flex-grow-2"
              label="Hora de inicio"
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
            >
              <b-datetimepicker
                  placeholder="Seleccione una fecha de incio"
                  icon="calendar-today"
                  v-model="eventStart"
                  horizontal-time-picker
                  >
              </b-datetimepicker>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            :name="`${question.name}`"
            rules=""
            v-for="(question, index) in questions"
            :key="question.id"
            class="is-block field"
          >
            <b-field
              horizontal
              custom-class="has-text-left is-flex-grow-2"
              :label="`${index + 1}. ${question.description}`"
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
            >
              <b-select
                v-model="question.answerId"
                placeholder="Seleccione una opción"
                @input="changedAnswer(question)"
              >
                <template v-if="question.alternatives">
                  <option
                    :value="alternative.id"
                    v-for="alternative in question.alternatives"
                    :key="alternative.id"
                  >
                    {{ alternative.body }}
                  </option>
                </template>
              </b-select>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="estado de vehículo"
            rules=""
            class="is-block field"
          >
            <b-field
              horizontal
              custom-class="has-text-left is-flex-grow-2"
              label="Estado de vehículo"
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
            >
              <b-select
                v-model="eventResult"
                placeholder="Seleccione una opción"
              >
                  <option
                    :value="result.id"
                    v-for="result in eventResults"
                    :key="result.id"
                  >
                    {{ result.name }}
                  </option>
              </b-select>
            </b-field>
          </ValidationProvider>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    event: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      origin: {
        address: null,
        location: null,
        place_id: null,
      },
      eventResult: null,
      eventStart: null,
      questionsAnswered: false,
      questions: [],
      questionsAnswers: [],
      eventResults: []
    };
  },
  watch: {
    "origin.place_id"(value) {
      if (value) {
        this.saveEvent();
      }
    },
    eventResult (value) {
      if (value) {
        this.saveEvent();
      }
    },
    eventStart (value) {
      if (value) {
        this.saveEvent();
      }
    }
  },
  computed: {
    initialAnswer() {
      return this.event && this.event.answers ? this.event.answers : [];
    },
    startDateInUTC(){
      return this.eventStart ? this.$moment(this.eventStart).toISOString().replace('T', ' ').replace('Z', '') : null
    }
  },
  methods: {
    manualPositionChange(data) {
      let lat = data.latLng.lat();
      let lng = data.latLng.lng();
      this.changePosition({ lat, lng });
    },
    async changePosition(location) {
      let geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location }, (results, status) => {
        if (results.length) {
          let data = results[0];
          this.origin = {
            address: data.formatted_address,
            location: location,
            place_id: data.place_id,
          };
        }
      });
    },
    changeEventLocation(data) {
      let lat = data.geometry.location.lat();
      let lng = data.geometry.location.lng();
      this.origin = {
        address: data.formatted_address,
        location: {
          lat,
          lng,
        },
        place_id: data.place_id,
      };
    },
    async loadQuestions() {
      const response = await this.$store.dispatch(
        "eventQuestions/getEventQuestions",
        {
          query: {
            include: ["alternatives"],
          },
        }
      );
      this.questions = response.data.map((question) => {
        const answer = this.initialAnswer.find(
          (a) => a.event_question_id === question.id
        );
        return {
          ...question,
          answerId: answer ? answer.alternative_id : null,
        };
      });
    },
    async loadEventResults() {
      try {
        const { data } = await this.$store.dispatch("eventResults/getEventResults", { query: {}});
        this.eventResults = data;
      } catch (error) {
        console.log(error)
      }
    },
    async saveEvent() {
      const event = {
        id: this.event.id,
        origin: JSON.stringify(this.origin),
        event_result_id: this.eventResult,
        event_start: this.startDateInUTC
      }
      const responseEvent = await this.$store.dispatch("events/updateEvent", event);
    },
    changedAnswer(question) {
      this.$nextTick(() => {
        this.saveQuestion(question);
      });
    },
    async saveQuestion(question) {
      if (question && question.answerId) {
        const response = await this.$store.dispatch(
          "eventQuestionsApplied/updateOrCreate",
          {
            event_id: this.event.id,
            event_question_id: question.id,
            alternative_id: question.answerId,
          }
        );
      }
    },
  },
  mounted() {
    this.loadQuestions();
    this.loadEventResults();
  
    if(this.event) {
      this.eventResult = this.event.event_result_id;
      if (this.event.origin) this.origin = this.event.origin;

      const { event_start, created_at } = this.event;

      if (event_start) {
        this.eventStart = this.$moment(event_start).toDate()
      } else if (created_at) {
        this.eventStart = this.$moment(created_at).toDate()
      }

    }
  },
};
</script>
