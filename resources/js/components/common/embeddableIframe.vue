<template>
  <div>
    <iframe
      :src="src"
      frameborder="0"
      width="100%"
      height="280px"
      allowtransparency
      @load="loading = false"
    ></iframe>
    <b-loading :is-full-page="false" v-model="loading"></b-loading>
  </div>
</template>

<script>
  export default {
    props:{
      src: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        loading: true
      }
    },
    watch: {
      src(newValue) {
        if(newValue) {
          this.loading = true
        }
      }
    }
  }
</script>