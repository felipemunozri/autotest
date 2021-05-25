<template>
  <div>
    <h1 class="title is-6">Información de Beneficiario</h1>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Nombre">
      <span class="is-size-6">{{ fullname }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="RUT">
      <span class="is-size-6">{{ runFormatting(dni) }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Teléfono">
      <span class="is-size-6">{{ phone_number }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Dirección">
      <span class="is-size-6">{{ address }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Email">
      <span class="is-size-6">{{ email }}</span>
    </b-field>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    name: {
      type: String,
    },
    dni: {
      type: String,
    },
    lastname: {
      type: String,
    },
    phone_number: {
      type: String,
    },
    address: {
      type: String,
    },
    email: {
      type: String,
    },
  },
  computed: {
    fullname() {
      return `${this.name} ${this.lastname}`;
    },
  },
};
</script>