<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">EDITAR USUARIO</h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <card title="Formulario de edición">
        <div slot="content">
          <edit-user-form :user-id="id"/>
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card';
import EditUserForm from '@/components/users/edit_form';

export default {
  components: { Card, EditUserForm },
  props: {
    id: {
      type: String,
      require: true,
    },
  },
  methods: {
    toIndex() {
      this.$inertia.visit(route('users.index'));
    },
  },
}
</script>
