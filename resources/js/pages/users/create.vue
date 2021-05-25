<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">NUEVO USUARIO</h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <card title="Formulario de ingreso">
        <div slot="content">
          <create-user-form />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import CreateUserForm from '@/components/users/create_form'

export default {
  components: { Card, CreateUserForm },
  methods: {
    toIndex() {
      this.$inertia.visit(route('users.index'))
    },
  },
}
</script>
