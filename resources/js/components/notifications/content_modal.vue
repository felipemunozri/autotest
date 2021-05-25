<template>
  <div>
    <b-button type="is-link" @click="openModal" icon-left="eye">
      Ver
    </b-button>
    <b-modal v-model="isOpened" :width="640" scroll="keep">
      <div class="card">
      <div class="card-content">
        <div class="media">
          <div class="media-left">
            <b-icon :icon="icons[notification.code]" class="m-4" custom-size="mdi-36px" />
          </div>
          <div class="media-content">
            <p class="title is-4">{{ `${notification.technical.name} ${notification.technical.lastname}` }}</p>
            <p class="subtitle is-6">{{ notification.technical.email }}</p>
          </div>
        </div>

        <div class="content">
          <span>
            {{ `${notification.technical.name} ${notification.technical.lastname} registró el vehículo de patente ${notification.vehicle.plateNumber}.` }}
          </span>
          <br>
          <span>
            {{ `Modelo de vehículo: ${notification.vehicle.model}` }}
          </span>
          <br>
          <span>
            {{ `RUT de informante: ${runFormatting(notification.technical.dni)}` }}
          </span>
          <br>
          <small class="mt-3">{{ moment(notification.createdAt).format('HH:mm') }} - {{ moment(notification.createdAt).format('D [de] MMMM, YYYY') }}</small>
          </div>
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  data() {
    return {
      isOpened: false,
      icons: {
        'vehicle-activated': 'car',
      },
    }
  },
  props: {
    notification: {
      type: Object,
      required: true,
    },
  },
  methods: {
    openModal() {
      this.isOpened = !this.isOpened;
      if (this.notification.readAt === null) this.$emit('read');
    },
  }
}
</script>