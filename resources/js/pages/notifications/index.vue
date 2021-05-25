<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">NOTIFICACIONES</h1>
        </div>
        <div class="column is-2">
          <b-button type="is-info" @click="readAll" icon-left="pencil">
            LEER TODAS
          </b-button>
        </div>
      </div>
      <card title="Listado de notificaciones" :header-search-input="false">
        <div slot="content">
          <div v-if="notifications.length">
            <item
              v-for="(notification, index) in notifications"
              :notification="notification"
              :index="index"
              :key="index"
              @read="reload"
            />
          </div>
          <div v-else>
            <p class="is-size-7 has-text-centered has-text-weight-bold">Sin notificaciones</p>
          </div>
          <div>
            <b-loading v-if="loading" :is-full-page="false" v-model="loading" :can-cancel="true"></b-loading>
            <b-button v-else @click="loadMore" expanded>
              Cargar Más
            </b-button>
          </div>
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import Item from '@/components/notifications/item';
import { Formatters } from '@/mixins/formatters';

export default {
  components: { Card, Item, },
  mixins: [Formatters()],
  data() {
    return {
      notifications: [],
      currentNotification: null,
      page: 1,
      perPage: 10,
      loading: false
    }
  },
  computed: {
    unread() {
      return this.notifications.filter(notification => notification.readAt === null).length;
    },
  },
  async mounted() {
    await this.fetchData();
  },
  methods: {
    async readAll() {
      const response = await this.$store.dispatch('users/readNotifications', {
        readAll: true,
      });
      if (response === 'successfully read') this.fetchData();
    },
    async fetchData() {
      this.loading = true;
      const response = await this.$store.dispatch(
        'users/getNotifications',
        { page: this.page, perPage: this.perPage }
      );
      const notifications = response.data.map(notification => {
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
      this.notifications = [...this.notifications, ...notifications];
      this.loading = false;
      return response.data.length;
    },
    reload(index) {
      this.$root.$emit('update-notifications');
      this.notifications[index].readAt = new Date();
    },
    async loadMore() {
      this.page++;
      const amount = await this.fetchData();
      if (!amount) this.page--;
    },
    toIndex() {
      this.$inertia.visit('/');
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
