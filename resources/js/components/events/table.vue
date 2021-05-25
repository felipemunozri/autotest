<template>
  <b-table
    :data="events"
    striped
    hoverable
    paginated
    backend-pagination
    :total="total"
    :loading="isLoading"
    :per-page="perPage"
    :current-page.sync="currentPage"
    @page-change="loadEvents"
  >
    <h6 class="empty is-6" slot="empty">No se encontraron resultados</h6>
    <b-table-column field="event-date" label="Fecha" v-slot="props">
      <span v-if="props.row.eventDate">{{ moment(props.row.eventDate).format('D [de] MMMM, YYYY') }}</span>
      <span v-else>{{ moment(props.row.eventCreatedAt).format('D [de] MMMM, YYYY') }}</span>
    </b-table-column>
    <b-table-column field="event-time" label="Hora" v-slot="props">
      <span v-if="props.row.eventDate">{{ moment(props.row.eventDate).format('HH:mm') }}</span>
      <span v-else>{{ moment(props.row.eventCreatedAt).format('HH:mm') }}</span>
    </b-table-column>
    <b-table-column field="beneficiary-name" label="Beneficiario" v-slot="props">
      {{ `${props.row.beneficiary.name} ${props.row.beneficiary.lastname}` }}
    </b-table-column>
    <b-table-column field="beneficiary-dni" label="RUN" v-slot="props">
      {{ runFormatting(props.row.beneficiary.dni) }}
    </b-table-column>
    <b-table-column field="vehicle-plate-number" label="Vehículo" v-slot="props">
      {{ props.row.vehicle.plateNumber }}
    </b-table-column>
    <b-table-column field="receiver-name" label="Operador" v-slot="props">
      {{ `${props.row.receiver.name} ${props.row.receiver.lastname}` }}
    </b-table-column>
    <b-table-column field="event-end" label="Termino" v-slot="props">
      <span v-if="props.row.eventEnd">{{ moment(props.row.eventEnd).format('DD/MM/YYYY HH:mm') }}</span>
    </b-table-column>
    <b-table-column field="status" label="Estado" v-slot="props">
      <span
        class="tag"
        :class="statusStyle[props.row.status.code]"
        expanded
      >
        {{ props.row.status.name }}
      </span>
    </b-table-column>
    <b-table-column v-slot="props">
      <b-button type="is-link" size="is-small" @click="toDetails(props.row.id)">
        Ver Detalles
      </b-button>
    </b-table-column>
  </b-table>
</template>

<script>
import { Formatters } from '@/mixins/formatters';

export default {
  mixins: [Formatters()],
  props: {
    eventFilter: {
      type: Object,
      default() {
        return {};
      },
    },
  },
  data() {
    return {
      events: [],
      isLoading: false,
      statusStyle: {
        'finished': 'is-info',
        'in-progress': 'is-warning',
        'created': 'is-validated',
        'validated': 'is-success',
      },
      currentPage: 1,
      perPage: 10,
      total: 0,
    }
  },
  watch: {
    eventFilter: {
      handler(val) {
        this.loadEvents();
      },
    },
  },
  mounted() {
    this.loadEvents();
  },
  methods: {
    async loadEvents() {
      this.isLoading = true;
      this.eventFilter.per_page = this.perPage;
      this.eventFilter.page = this.currentPage;
      const { data, meta } = await this.$store.dispatch(
        'events/getEvents',
        this.eventFilter !== {}
          ? {
              query: this.eventFilter,
            }
          : { query: {} }
      );
      this.events = data.map(event => {
        return {
          'id': event.id,
          'tenantId': event.tenant_id,
          'beneficiaryId': event.beneficiary_id,
          'vehicleId': event.vehicle_id,
          'receivedBy': event.received_by,
          'detail': event.detail,
          'eventDate': event.event_start,
          'eventCreatedAt': event.created_at,
          'eventEnd': event.event_end,
          'eventTime': event.event_time,
          'observation': event.observation,
          'eventStatusId': event.event_status_id,
          'origin': event.origin,
          'beneficiary': this.renameKeysOfObject(event.beneficiary, {
            'address': 'address',
            'created_at': 'createdAt',
            'dni': 'dni',
            'dni_serial_number': 'dniSerialNumber',
            'email': 'email',
            'id': 'id',
            'lastname': 'lastname',
            'name': 'name',
            'phone_number': 'phoneNumber',
            'updated_at': 'updatedAt',
          }),
          'vehicle': this.renameKeysOfObject(event.vehicle, {
            'card_brand_id': 'carBrandId',
            'chassis_number': 'chassisNumber',
            'color': 'color',
            'color_code': 'colorCode',
            'country_id': 'countryId',
            'created_at': 'createdAt',
            'drive': 'drive',
            'engine_capacity': 'engineCapacity',
            'fuel_type_id': 'fuelTypeId',
            'id': 'id',
            'key': 'key',
            'model': 'model',
            'model_id': 'modelId',
            'owner_acquisition_date': 'ownerAcquisitionDate',
            'owner_dni': 'ownerDni',
            'owner_name': 'ownerName',
            'plate_number': 'plateNumber',
            'tenant_id': 'tenantId',
            'updated_at': 'updatedAt',
            'vehicle_type_id': 'vehicleTypeId',
            'year': 'year',
          }),
          'status': event.status,
          'receiver': this.renameKeysOfObject(event.receiver, {
            'api_token': 'apiToken',
            'created_at': 'createdAt',
            'dni': 'dni',
            'email': 'email',
            'email_verified_at': 'emailVerifiedAt',
            'id': 'id',
            'lastname': 'lastname',
            'name': 'name',
            'phone': 'phone',
            'provider': 'provider',
            'provider_id': 'providerId',
            'second_lastname': 'secondLastname',
            'second_name': 'secondName',
            'updated_at': 'updatedAt',
            'username': 'username',
          }),
        }
      });
      this.total = meta.total;
      this.isLoading = false;
    },
    toDetails(id) {
      this.$inertia.visit(route('events.show', id));
    },
  },
}
</script>

<style scoped>
  .empty {
    text-align: center;
  }
  
  .tag {
    width: 100%;
  }
</style>
