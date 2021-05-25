<template>
  <div class="card">
    <header class="card-header">
      <div class="card-header-title" v-if="title">
        <p class="card-title">{{title}}</p>
        <p class="card-subtitle" v-if="subtitle">{{ subtitle }}</p>
      </div>
      <div style="padding: 0.75rem;">
        <slot name="header-left"/>
      </div>
      <div v-if="headerSearchInput" style="padding: 0.75rem;">
        <b-field>
          <b-input placeholder="Buscar" icon="magnify" @input="changeInputSearch" ></b-input>
        </b-field>
      </div>
      <slot name="header-right"/>
    </header>
    <div :class="{'card-content': paddingContent}">
      <slot name="content"/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    subtitle: {
      type: String,
      default: null
    },
    title: {
      type: String,
      default: null
    },
    icon: {
      type: String,
      default: null
    },
    headerIcon: {
      type: String,
      default: null
    },
    headerSearchInput: {
      type: Boolean,
      default: false
    },
    paddingContent: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    headerIconClick () {
      this.$emit('header-icon-click')
    },
    changeInputSearch (event) {
      this.$emit('change-input-search', event)
    }
  }
}
</script>

<style scoped>
  .card-header{
    background-color: white;
    /*border: */
    border-radius: 6px 6px 0px 0px;
    border-bottom: 1px solid rgba(24, 28, 33, 0.06);
  }
  .card .card-header-title {
    display: block !important;
  }
  .card .card-header-title * {
    color: #424242 !important;
  }

  .card-title{
    font-weight: 500;
    line-height: 15px;
    font-size: 1.1rem;
  }

  .card-subtitle{
    font-weight: 200;
    line-height: 18px;
  }
</style>
