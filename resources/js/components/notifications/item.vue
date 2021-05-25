<template>
  <a
    class="navbar-item columns is-vcentered"
    :class="{ 'unread': notification.readAt === null }"
    title="Notificación"
  >
    <div class="column is-1 m-3">
      <b-icon :icon="icons[notification.code]" custom-size="mdi-36px" />
    </div>
    <div class="column">
      <span>{{ `${notification.technical.name} ${notification.technical.lastname} registró el vehículo ${notification.vehicle.plateNumber}` }}</span>
      <h6 class="subtitle is-6">{{ moment(notification.createdAt).format('HH:mm') }}</h6>
    </div>
    <div class="column is-1">
      <content-modal :notification="notification" @read="readNotification" />
    </div>
  </a>
</template>

<script>
import ContentModal from './content_modal';

export default {
  components: { ContentModal },
  props: {
    index: {
      type: Number,
      default() {
        return 0;
      },
    },
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
      this.$inertia.visit(route(this.notification.redirectUrl));
    },
    async readNotification() {
      const response = await this.$store.dispatch('users/readNotifications', {
        notificationId: this.notification.id,
      });
      if (response === 'successfully read') this.$emit('read', this.index);
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
    height: 1rem;
    width: 1rem;
  }
</style>