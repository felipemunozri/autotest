<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">
            EDITAR BENEFICIARIO
          </h1>
        </div>
        <div class="column is-narrow">
          <b-button @click="toIndex" icon-left="arrow-left">
            VOLVER
          </b-button>
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <card title="Formulario de edición">
            <div slot="content">
              <edit-form/>
            </div>
          </card>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { Formatters } from '@/mixins/formatters';
import Card from '@/components/common/card';
import EditForm from '@/components/beneficiaries/editForm';

export default {
  components: { Card, EditForm },
  mixins: [Formatters()],
  props: {},
  data() {
    return {
    }
  },
  computed: {
  },
  async mounted() {
  },
  methods: {
    toIndex() {
      this.$inertia.visit(route('dashboard.index'))
    },
  },
}
</script>
