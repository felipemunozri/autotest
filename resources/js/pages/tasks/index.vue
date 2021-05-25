<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">TAREAS</h1>
        </div>
      </div>
      <card title="Listado de tareas" :header-search-input="false">
        <div slot="header-right" class="card-header-icon">
          <b-input v-model="searchText" class=""></b-input>
          <b-button icon-left=magnify type="is-primary is-light" @click="search"></b-button>
        </div>
        <div slot="content">
          <tasks-table :taskFilter="tableFilter" />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import TasksTable from '@/components/tasks/tasks_table'

export default {
  components: { Card, TasksTable },
  props: ['data'],
  data() {
    return {
      searchText: '',
      tableFilter: '',
    }
  },
  mounted() {
  },
  methods: {
    search() {
     this.tableFilter = this.searchText;
    },
  }
}
</script>