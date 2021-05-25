<template>
  <div>
    <hr class="my-2"/>
    <h1 class="title is-6 mb-2">Evento</h1>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Iniciado">
      <span class="is-size-6">{{ created_at | moment('D [de] MMMM, YYYY [a las] HH:mm')}}</span>
    </b-field>
    <b-field class="mb-0" custom-class="has-text-left" horizontal label="Estado">
      <b-tag :type="statusTag">{{statusName}}</b-tag>
    </b-field>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    created_at: {
      type: String,
    },
    status_code: {
      type: String,
    }
  },
  computed: {
    statusName(){
      let name = null;
      switch (this.status_code) {
        case 'previously-initiated':
          name = 'Previamente Iniciado';
          break;
        case 'new-event':
          name = 'Nuevo'
          break;
      
        default:
          break;
      }
      return name
    },
    statusTag(){
      let tag = 'is-default';
      switch (this.status_code) {
        case 'previously-initiated':
          tag = 'is-warning'
          break;
        case 'new-event':
          tag = 'is-primary';
          break;
      
        default:
          break;
      }
      return tag
    }
  }
};
</script>