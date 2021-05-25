<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">USUARIOS</h1>
        </div>
        <div class="column is-narrow">
          <b-button type="is-link" @click="toCreate" icon-left="plus"  v-if="$users.isAllowed('create')">
            AÑADIR USUARIO
          </b-button>
        </div>
      </div>
      <card title="Listado de usuarios" class="card-table" :header-search-input="false" @change-input-search="searchText = $event">
        <div slot="content">
          <users-table :filter="filter" />
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import UsersTable from '@/components/users/table'
import { UserPerimeter } from '@/perimeters'

export default {
  components: { Card, UsersTable },
  perimeters: [
    UserPerimeter,
  ],
  data() {
    return {
      searchText: null,
    }
  },
  computed: {
    filter() {
      return {
        query: {
          dni: this.searchText === '' ?  null : this.searchText ,
          include: [
            'roles'
          ]
        },
      }
    }
  },
  mounted() {
  },
  methods: {
    toCreate() {
      this.$inertia.visit(route('users.create'))
    },
  }
}
</script>