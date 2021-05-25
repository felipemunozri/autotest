<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">NUEVO EVENTO</h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <div class="card">
            <div class="card-content">
              <create-event-form />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card';
import CreateEventForm from '@/components/events/create_form';

export default {
  components: { Card, CreateEventForm },
  methods: {
    toIndex() {
      this.$inertia.visit('/');
    },
  },
}
</script>
