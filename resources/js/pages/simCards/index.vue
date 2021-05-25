<template>
  <section class="section">
    <div class="container">
      <div class="columns is-vcentered">
        <div class="column">
          <h1 class="title" title-weight="weight-normal">TARJETAS SIM</h1>
        </div>
        <div class="column is-narrow">
          <b-button type="is-link" @click="toCreate" icon-left="plus"  v-if="$users.isAllowed('create')">
            AÑADIR SIM
          </b-button>
        </div>
      </div>
      <card title="Listado de tarjetas" class="card-table" :header-search-input="true" @change-input-search="keyword = $event">
        <div slot="content">
          <sim-cards-table :filter="filter"/>
        </div>
      </card>
    </div>
  </section>
</template>

<script>
import Card from '@/components/common/card'
import SimCardsTable from '@/components/simCards/table'
import { UserPerimeter } from '@/perimeters'

export default {
  components: { Card, SimCardsTable },
  perimeters: [
    UserPerimeter,
  ],
  data() {
    return {
      keyword: null,
      searchText: null,
    }
  },
  computed: {
    searchTextFormated() {
      return !this.searchText || this.searchText === '' ?  null : `%${this.searchText}%`;
    },
    filter() {
      return {
        query: {
          phone_number: this.searchTextFormated,
        },
      }
    },
  },
  watch: {
    keyword: {
      handler(val) {
        this.updateSearchText()
      },
    },
  },
  methods: {
    toCreate() {
      this.$inertia.visit(route('simcard.create'))
    },
    updateSearchText: _.debounce(function () {
      this.searchText = this.keyword;
    }, 400),
  }
}
</script>