<template>
  <b-modal
    v-model="modalShowing"
    has-modal-card
    trap-focus
    :destroy-on-hide="false"
    :can-cancel="false"
    aria-role="dialog"
    aria-label="Example Modal"
    aria-modal
    scroll="keep"
  >
    <div class="card p-3">
      <div class="card-content">
        <h4 class="title is-4">Redirección por inactividad</h4>
        <h6 class="is-6 mt-3">
          Dentro de {{ seconds }} segundos será redirigido a la página principal
          del sistema.
        </h6>
        <h6 class="is-6 mt-3">
          Si necesita seguir en la página, presione "{{ buttonText }}"
        </h6>
        <div class="columns mt-5">
          <div class="column is-12 button-container">
            <b-button type="is-link" @click="stay">
              {{ buttonText }}
            </b-button>
          </div>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
export default {
  props: {
    buttonText: {
      type: String,
      default() {
        return 'Seguir en operación';
      },
    },
    active: {
      type: Boolean,
      default() {
        return false;
      },
    },
    time: {
      type: Number,
      default() {
        return 15000;
      },
    },
  },
  data() {
    return {
      modalShowing: false,
      timer: 0,
    }
  },
  mounted() {
    this.timer = this.time;
  },
  computed: {
    isIdle() {
      return this.$store.state.idleVue.isIdle;
    },
    seconds() {
      return this.timer / 1000;
    },
  },
  watch: {
    isIdle: {
      handler(val, oldVal) {
        if (this.active) {
          if (val && !oldVal && !this.modalShowing) {
            this.initTimer();
          }
        }
      },
    },
  },
  methods: {
    initTimer() {
      if (!this.modalShowing) this.showModal();
      let timerId = setInterval(() => {
        this.timer -= 1000;
        if (!this.isIdle) clearInterval(timerId);
        if (this.timer < 1) {
          clearInterval(timerId);
          this.$emit('timer-end');
        }
      }, 1000);
    },
    stay() {
      this.timer = 15000;
      this.hideModal();
    },
    showModal() {
      this.modalShowing = true;
    },
    hideModal() {
      this.modalShowing = false;
    },
  },
}
</script>

<style scoped>
  .button-container {
    text-align: center;
  }
</style>
