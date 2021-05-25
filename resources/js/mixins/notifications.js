export default {
  methods: {
    notify({message, type, position = 'is-bottom-right', duration = 2000, hasIcon = true}) {
      this.$buefy.notification.open({
        duration: duration,
        message: message,
        position: position,
        type: type,
        hasIcon: hasIcon
      });
    },
  }
}