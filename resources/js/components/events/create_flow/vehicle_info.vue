<template>
  <div>
    <h1 class="title is-6">Información de Vehículo</h1>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Patente">
      <span class="is-size-6">{{ plate_number }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Marca">
      <span class="is-size-6">{{ brandName }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Modelo">
      <span class="is-size-6">{{ model }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Año">
      <span class="is-size-6">{{ year }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Color">
      <span class="is-size-6">{{ color }}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Dueño">
      <span class="is-size-6">{{ owner }}</span>
    </b-field>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    plate_number: {
      type: String,
    },
    year: {
      type: Number,
    },
    owner_dni: {
      type: String,
    },
    owner_name: {
      type: String,
    },
    car_brand: {
      type: Object,
    },
    color: {
      type: String,
    },
    model: {
      type: String,
    },
  },
  computed: {
    owner() {
      return `${this.owner_name}, ${this.runFormatting(this.owner_dni)}`;
    },
    brandName() {
      return this.car_brand ? this.car_brand.name : "";
    },
  },
};
</script>
