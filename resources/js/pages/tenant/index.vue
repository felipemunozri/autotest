<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">Organización</h1>
        </div>
      </div>
      <card title="Datos de la organización">
        <div slot="header-right" class="p-2" v-if="!canEdit">
          <b-button type="is-info" size="is-small" icon-left="pencil" @click="canEdit = true"> Editar </b-button>
        </div>
        <div slot="content">
          <edit-form :tenant-id="tenantId" :can-edit.sync="canEdit" />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import EditForm from '@/components/tenant/edit_form'
import { mapState } from 'vuex';

export default {
  components: { Card, EditForm },
  data() {
    return {
      canEdit: false,
    }
  },
  computed: {
    ...mapState([
      'currentTenant',
    ]),
    tenantId() {
      return this.currentTenant ? this.currentTenant.id : null;
    }

  },
}
</script>
