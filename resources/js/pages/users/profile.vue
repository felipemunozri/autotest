<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">PERFIL</h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <card title="Datos de usuario">
        <div slot="header-right" class="p-2" v-if="!canEdit">
          <b-button type="is-info" size="is-small" icon-left="pencil" @click="canEdit = true"> Editar </b-button>
        </div>
        <div slot="content">
          <profile-form :can-edit.sync="canEdit" />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import ProfileForm from '@/components/users/profile_form'

export default {
  components: { Card, ProfileForm },
  data() {
    return {
      canEdit: false,
    }
  },
  methods: {
    toIndex() {
      this.$inertia.visit(route('users.index'))
    },
  },
  computed: {
    user() {
      return this.$store.state.user;
    },
  },
}
</script>
