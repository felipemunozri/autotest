<template>
  <a
    class="navbar-item columns is-vcentered"
    :class="{ 'unread': notification.readAt === null }"
    title="Notificación"
    @click="redirect"
  >
    <div class="column is-1 m-3">
      <b-icon :icon="icons[notification.code]" custom-size="mdi-36px" />
    </div>
    <div class="column">
      <span>{{ `${notification.technical.name} ${notification.technical.lastname} registró el vehículo ${notification.vehicle.plateNumber}` }}</span>
      <h6 class="subtitle is-6">{{ moment(notification.createdAt).format('HH:mm') }}</h6>
    </div>
    <div class="column is-1">
      <div
        v-if="notification.readAt === null"
        title="Marcar como leída"
        class="circle"
        @click="readNotification"
      >
      </div>
    </div>
  </a>
</template>

<script>
export default {
  props: {
    notification: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      icons: {
        'vehicle-activated': 'car',
      },
    }
  },
  methods: {
    redirect() {
      if (this.notification.redirectUrl) {
        this.$inertia.visit(route(this.notification.redirectUrl));
      } else {
        this.$inertia.visit(route("notifications.index"));
      }
    },
    async readNotification() {
      const response = await this.$store.dispatch('users/readNotifications', {
        notificationId: this.notification.id,
      });
      if (response === 'successfully read') this.$emit('read');
    },
  },
}
</script>

<style scoped>
  .unread {
    background-color: rgb(227, 237, 253);
  }
  .circle {
    border-radius: 50%;
    background-color: rgb(69, 126, 211);
    min-height: 1rem;
    min-width: 1rem;
  }
</style>