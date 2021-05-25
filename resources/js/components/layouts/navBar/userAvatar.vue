<template>
  <div class="is-user-avatar">
    <img v-if="userAvatar" :src="`${userAvatar}`" :alt="userName">
    <img v-else src="../../../../assets/placeholder-person.jpg" :alt="userName">
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    avatar: {
      type: String,
      default: null
    }
  },
  computed: {
    newUserAvatar () {
      if (this.avatar) {
        return this.avatar
      }

      if (this.userAvatar) {
        return this.userAvatar
      }

      let name = 'somename'

      if (this.userName) {
        name = this.userName.replace(/[^a-z0-9]+/i, '')
      }

      return `https://avatars.dicebear.com/v2/human/${name}.svg?options[mood][]=happy`
    },
    ...mapState([
      'userAvatar',
      'userName',
      'appUrl',
    ]),
  }
}
</script>
