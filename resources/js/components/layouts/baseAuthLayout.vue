<template>
  <div style=" min-height: 100vh;">
    <slot></slot>
  </div>
</template>

<script>

export default {
  components: {
  },
  computed: {
    menu () {
      return [
        'Menú'
      ]
    }
  },
  methods: {
  },
  created () {
  }
}
</script>
