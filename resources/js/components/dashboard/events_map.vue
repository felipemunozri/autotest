<template>
  <gmap-map
    :center="center"
    :options="mapOptions"
    :zoom="15"
    map-type-id="roadmap"
    class="map"
    ref="gMap"
  >
    <gmap-info-window
      :options="infoOptions"
      :position="selectedPosition.location"
      :opened="infoWinOpen"
      @closeclick="infoWinOpen=false"
      v-if="selectedPosition">
      <div>
        <h1 class="title is-6 mb-1">Información del Evento</h1>
        <b-field class="mb-0" custom-class="has-text-left mr-1" horizontal label="Dirección">
          <span class="is-size-6">{{ selectedPosition.address }}</span>
        </b-field>
        <b-field class="mb-0" custom-class="has-text-left mr-1" horizontal label="Fecha">
          <span class="is-size-6">{{ selectedPosition.created_at | moment('D [de] MMMM, YYYY') }}</span>
        </b-field>
      </div>
    </gmap-info-window>
    <gmap-cluster v-if="layerActive === 'marker'" imagePath="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m">
      <gmap-marker
        v-for="event in events"
        :key="event.id"
        :position="event.location"
        :clickable="true" @click="toggleInfoWindow(event)"
      />
    </gmap-cluster>
    <gmap-heatmap-layer v-else-if="layerActive === 'heatmap'"
      :data="heatData"
      :options="{radius: 12, dissipating: true}"
    />
  </gmap-map>
</template>

<script>
import { mapState } from 'vuex';
import { gmapApi } from 'gmap-vue';
export default {
  data() {
    return {
      events: [],
      layerActive: 'heatmap',
      layersContorls: [
        {
          key: 'heatmap',
          buttonText: 'Mapa de Calor',
          buttonId: 'btn-layer-heatmap',
          onClick: this.selectLayer
        },
        {
          key: 'marker',
          buttonText: 'Puntos',
          buttonId: 'btn-layer-marker',
          onClick: this.selectLayer
        }
      ],
      mapOptions: {
        styles: [
            {
              featureType: "poi.attraction",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "poi.business",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "poi.government",
              stylers: [{ visibility: "on" }],
            },
            {
              featureType: "poi.medical",
              stylers: [{ visibility: "on" }],
            },
            {
              featureType: "poi.park",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "poi.place_of_worship",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "poi.school",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "poi.sports_complex",
              stylers: [{ visibility: "off" }],
            },
            {
              featureType: "transit",
              elementType: "labels.icon",
              stylers: [{ visibility: "off" }],
            },
          ],
      },
      infoOptions:{
        maxWidth: 400,
        pixelOffset: { width: 0, height: -35 }
      },
      infoWinOpen: false,
      selectedPosition: null,
      selectedButtonId: null
    }
  },
  computed:{
    google: gmapApi,
    heatData(){
      if(this.google){
        return this.events.map(x => {
          return new google.maps.LatLng(x.location)
        });
      }
      return [];
    },
    ...mapState([
      'currentTenant'
    ]),
    center() {
      return this.currentTenant?.location || {"lat": -33.4421635, "lng": -70.6560537};
    }
  },
  methods:{
    toggleInfoWindow(event){
      if(!this.selectedPosition || this.selectedPosition.id != event.id){
        this.infoWinOpen = true;
        this.selectedPosition = event;
      } else {
        this.infoWinOpen = false;
        this.selectedPosition = null;
      }
    },
    async fetchData(){
      const response = await this.$store.dispatch(
        'events/getEvents',
        {
          query: {},
          include: []
        }
      );
      let events = response.data.filter(x => x.origin && x.origin.location).map(x => {
        let origin = x.origin || {};
        if (x.origin) delete x.origin
        return {
          ...x,
          ...origin
        }
      });
      this.events = events;
    },
    addControls(map){
      let fieldContainer = document.createElement('div')
      fieldContainer.classList.add('field')
      fieldContainer.classList.add('has-addons')
      fieldContainer.classList.add('p-3')

      this.layersContorls.forEach(control => {
        let controlContainer = document.createElement('p')
        controlContainer.classList.add('control')

        let button = document.createElement('button')
        button.innerHTML = control.buttonText
        button.classList.add('button')
        button.classList.add('is-medium')
        button.setAttribute("id", control.buttonId);
        button.addEventListener('click', control.onClick)
        controlContainer.appendChild(button)
        fieldContainer.appendChild(controlContainer);

        if(this.layerActive == control.key){
          button.classList.add('is-primary');
          button.classList.add('is-selected');
          this.selectedButtonId = control.buttonId;
        }
        
      });
      
      map.controls[this.google.maps.ControlPosition.TOP].push(fieldContainer)
    },
    selectLayer(event){
      let buttonId = event.target.id;
      let control = this.layersContorls.find(x => x.buttonId == buttonId);
      if (control) {
        this.layerActive = control.key
        this.selectedButtonId = control.buttonId
      }

    },
    applySelectedControlStyle(buttonId){
      let button = document.getElementById(buttonId);
      if(button) {
        button.classList.add('is-primary');
        button.classList.add('is-selected');
      }
    },
    removeSelectedControlStyle(buttonId){
      let button = document.getElementById(buttonId);
      if(button) {
        button.classList.remove('is-primary');
        button.classList.remove('is-selected');
      }
    }
  },
  watch:{
    selectedButtonId(newValue, oldValue){
      if(newValue) this.applySelectedControlStyle(newValue)
      if(oldValue) this.removeSelectedControlStyle(oldValue)
    }
  },
  mounted() {
    this.$refs.gMap.$mapPromise.then((map) => {
        this.addControls(map)
      })
    this.fetchData()
  }
}
</script>

<style>

</style>