<template>
  <div class="navbar-item has-dropdown has-dropdown-with-icons" :class="{ 'is-active': isDropdownActive }">
    <a class="navbar-link is-arrowless" @click="toggle">
      <div class="badge-container">
        <span v-show="unread" title="Notificaciones" class="badge is-danger">{{ unread }}</span>
        <b-icon icon="bell-outline"></b-icon>
      </div>
    </a>
    <div class="is-right navbar-dropdown px-2 notifications-container">
      <div class="columns is-vcentered">
        <div class="column">
          <span>Notificaciones</span>
        </div>
        <div class="column is-narrow" v-show="unread">
          <b-button type="is-info" size="is-small" @click="readAll">Leer todas</b-button>
        </div>
      </div>
      <div v-if="notifications.length">
        <notification-item
          v-for="notification in notifications"
          :notification="notification"
          :key="notification.index"
          @read="fetchData"
        />
      </div>
      <div v-else>
        <p class="is-size-7 has-text-centered has-text-weight-bold">Sin notificaciones</p>
      </div>
      <div class="columns is-centered">
        <div class="column is-full has-text-centered">
          <b-button class="mt-2" size="is-small" @click="toNotifications">Ver Notificaciones Anteriores</b-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NotificationItem from './notification_item';
import { Formatters } from '@/mixins/formatters';

export default {
  components: { NotificationItem },
  mixins: [Formatters()],
  data() {
    return {
      isDropdownActive: false,
      notifications: [],
    }
  },
  created () {
    window.addEventListener('click', this.forceClose)
  },
  beforeDestroy () {
    window.removeEventListener('click', this.forceClose)
  },
  computed: {
    unread() {
      return this.notifications.filter(notification => notification.readAt === null).length;
    },
  },
  async mounted() {
    await this.fetchData();
    this.listenForNotification();
    this.$root.$on('update-notifications', this.fetchData);
  },
  methods: {
    toggle() {
      this.isDropdownActive = !this.isDropdownActive;
    },
    forceClose (e) {
      if (!this.$el.contains(e.target)) {
        this.isDropdownActive = false
      }
    },
    async readAll() {
      const response = await this.$store.dispatch('users/readNotifications', {
        readAll: true,
      });
      if (response === 'successfully read') this.fetchData();
    },
    async fetchData() {
      const response = await this.$store.dispatch(
        'users/getUnreadNotifications',
        { page: 1, perPage: 6 }
      );
      this.notifications = response.data.map(notification => {
        return {
          ...this.renameKeysOfObject(notification, {
            'id': 'id',
            'notifiable_id': 'notifiableId',
            'notifiable_type': 'notifiableType',
            'read_at': 'readAt',
            'type': 'type',
            'created_at': 'createdAt',
            'updated_at': 'updatedAt',
          }),
          ...notification.data
            ? {
              'code': notification.data.code,
              'redirectUrl': notification.data['redirect_url'],
              'device': this.renameKeysOfObject(notification.data.device, {
                'id': 'id',
                'model_name': 'modelName',
                'phone_number': 'phoneNumber',
                'serial_number': 'serialNumber',
              }),
              'technical': this.renameKeysOfObject(notification.data.technical, {
                'dni': 'dni',
                'email': 'email',
                'id': 'id',
                'lastname': 'lastname',
                'name': 'name',
                'second_lastname': 'secondLastname',
                'second_name': 'secondName',
              }),
              'vehicle': this.renameKeysOfObject(notification.data.vehicle, {
                'brand_name': 'brandName',
                'id': 'id',
                'model': 'model',
                'plate_number': 'plateNumber',
                'year': 'year',
              }),
            }
            : {},
        };
      });
    },
    listenForNotification() {
      Echo.private(`App.Models.User.${this.$store.state.user.id}`)
        .notification((notification) => {
          this.notifications.push(
            this.renameKeysOfObject(notification, {
              'id': 'id',
              'code': 'code',
              'notifiable_id': 'notifiableId',
              'notifiable_type': 'notifiableType',
              'read_at': 'readAt',
              'redirect_url': 'redirectUrl',
              'type': 'type',
              'created_at': 'createdAt',
              'updated_at': 'updatedAt',
              'device': {
                name: 'device',
                format: (device) => (this.renameKeysOfObject(device, {
                  'id': 'id',
                  'model_name': 'modelName',
                  'phone_number': 'phoneNumber',
                  'serial_number': 'serialNumber',
                })),
              },
              'technical': {
                name: 'technical',
                format: (technical) => (this.renameKeysOfObject(technical, {
                  'dni': 'dni',
                  'email': 'email',
                  'id': 'id',
                  'lastname': 'lastname',
                  'name': 'name',
                  'second_lastname': 'secondLastname',
                  'second_name': 'secondName',
                })),
              },
              'vehicle': {
                name: 'vehicle',
                format: (vehicle) => (this.renameKeysOfObject(vehicle, {
                  'brand_name': 'brandName',
                  'id': 'id',
                  'model': 'model',
                  'plate_number': 'plateNumber',
                  'year': 'year',
                })),
              },
            })
          );
        });
    },
    toNotifications() {
      this.$inertia.visit(route("notifications.index"));
      this.toggle();
    },
  },
}
</script>

<style scoped>
  .button {
    display: relative;
    text-align: center;
  }
  .button .icon:last-child:not(:first-child) {
    margin-left: auto !important;
    margin-right: auto !important;
  }
</style>
