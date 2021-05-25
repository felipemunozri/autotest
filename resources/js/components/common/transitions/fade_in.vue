<template>
  <transition
      enter-active-class="animate__animated  animate__fadeIn"
      leave-active-class="animate__animated  animate__fadeOut"
      :duration="duration"
    >
    <slot></slot>
  </transition>
</template>

<script>
  export default {
    props:{
      duration: {
        type: Object,
        default: () => ({ enter: 300, leave: 100 })
      }
    },
    data() {
      return {
      }
    },
  }
</script>