<template>
  <div>
    <section id="login-section" class="hero is-fullheight">
      <div class="hero-body">
        <div class="container has-text-centered">
          <div class="column is-4 is-offset-4">
            <figure class="logo">
              <img src="../../../assets/logo.png" alt="logo" />
            </figure>
            <h3 class="title">Escuela del Arte Ecuestre de Chile</h3>
            <hr class="login-hr" />
            <p class="subtitle">Sistema de Gestión</p>
            <div v-if="showLoginBox" class="box">
              <ValidationObserver v-slot="{ handleSubmit, invalid }">
                <form @submit.prevent="handleSubmit(sendCredentials)">
                  <ValidationProvider name="correo" rules="required|email" v-slot="{ errors }">
                    <b-field
                        :type="errors.length > 0 ? 'is-danger':''"
                        :message="errors[0]">
                        <b-input type="email"
                            v-model="email"
                            size="is-large"
                            placeholder="Correo">
                        </b-input>
                    </b-field>
                  </ValidationProvider>

                  <ValidationProvider name="contraseña" rules="required" v-slot="{ errors }">
                    <b-field
                        :type="errors.length > 0 ? 'is-danger':''"
                        :message="errors[0]">
                        <b-input type="password"
                            v-model="password"
                            size="is-large"
                            password-reveal
                            placeholder="Contraseña">
                        </b-input>
                    </b-field>
                  </ValidationProvider>
                  <!-- <div class="field">
                    <label class="checkbox">
                      <b-checkbox>Recuerdame</b-checkbox>
                    </label>
                  </div> -->
                  <b-button type="is-primary" size="is-large"
                    native-type="submit"
                    :loading="isLoading"
                    :disabled="invalid"
                    expanded>
                      Ingresar
                  </b-button>
                </form>
              </ValidationObserver>
              <div class="has-text-centered" style="padding-top: 0.5rem">
                <a class="is-small is-primary" @click="showLoginBox = false">¿Olvidaste tu contraseña?</a>
              </div>
            </div>
            <div v-else class="box">
              <p style="padding-bottom: 0.5rem">Ingresa tu correo electrónio asociado a tu cuenta para solicitar el cambio de contraseña</p>
              <ValidationObserver v-slot="{ invalid }">
              <form @submit.prevent="passwordRequest">
                <ValidationProvider name="correo" rules="required|email" v-slot="{ errors }">
                  <b-field
                    :type="errors.length > 0 ? 'is-danger':''"
                    :message="errors[0]">
                    <b-input type="email"
                             v-model="email"
                             size="is-large"
                             placeholder="Correo">
                    </b-input>
                  </b-field>
                </ValidationProvider>
                <b-button type="is-primary" size="is-large"
                          @click.prevent="passwordRequest"
                          :loading="isLoading"
                          :disabled="invalid"
                          expanded>
                  Enviar
                </b-button>
              </form>
              </ValidationObserver>
              <div class="has-text-centered" style="padding-top: 0.5rem">
                <a class="is-small is-primary" @click="showLoginBox = true">Ingresar</a>
              </div>
              <pre>
                {{ errors }}
              </pre>
            </div>
            <!-- <p class="bottom-links">
              <a href="../">Contraseña Olvidada</a> &nbsp;·&nbsp;
              <a href="../">Necesitas ayuda?</a>
            </p> -->
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import '../../../sass/login.scss'

import BaseAuthLayout from '@/components/layouts/baseAuthLayout'

export default {
  name: 'Login',
  layout: BaseAuthLayout,
  data () {
    return {
      email: null,
      password: null,
      remember: false,
      isLoading: false,
      showLoginBox: true
    }
  },
  computed: {
  },
  methods: {
    async sendCredentials () {
      this.$inertia.post('/login', {
        'email': this.email,
        'password': this.password,
        'remember': this.remember
      })
      /* this.isLoading = true
      let result = await AuthService.login(this.email, this.password, this.remember)
      this.isLoading = false
      if (result.success && !result.disabled) {
        if (this.isOnlyJudge) {
          this.router.push('evaluaciones')
          return false
        }
        this.router.push('resumen')
      } else if (result.success && result.disabled) {
        this.showDialog(`No tienes los permisos necesarios para acceder`)
      } else {
        this.showDialog(`El correo o contraseña no son válidos`)
      } */
    },
    async passwordRequest () {
      /* this.isLoading = true
      let url = await window.location.origin + '/#/password-reset'
      let result = await AuthService.passwordRequest(this.email, url)
      this.isLoading = false
      if (result && result.status === 200) {
        console.log(result.status)
        this.showLoginBox = true
        this.showDialogSuccess('Te hemos enviado un correo para reactivar tu contraseña')
      } else {
        this.showDialog(`No se ha podido solicitar el cambio de contraseña`)
      } */
    },
    showDialog (text) {
      this.$buefy.toast.open({
        duration: 6000,
        message: text,
        position: 'is-bottom',
        type: 'is-danger'
      })
    },
    showDialogSuccess (text) {
      this.$buefy.toast.open({
        duration: 6000,
        message: text,
        position: 'is-bottom',
        type: 'is-success'
      })
    }
  }
}
</script>
