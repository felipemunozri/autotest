<template>
  <div>
    <div class="columns is-vcentered">
      <div class="column is-4" style="text-align: left">
        <a class="box p-5" @click="toTenant()">
          <div class="columns mt-1 ml-2"><h4 class="title is-5 has-text-weight-light">ORGANIZACIÓN</h4></div>
          <div class="columns component has-icon is-vcentered mt-1 ml-2 mb-1">
            <b-icon icon="domain" />
            <span class="menu-item-label title is-4 ml-4">{{ currentTenant.name }}</span>
          </div>
        </a>
      </div>
      <div class="column" style="text-align: right">
        <h3 class="title is-3 has-text-weight-light">{{ $store.state.userName }}</h3>
        <h4 class="title is-4 is-capitalized" title-weight="weight-normal">{{ $store.state.userProfile }}</h4>
      </div>
      <div class="column is-narrow px-5">
        <div class="is-user-avatar">
          <img
            v-if="user.profilePhoto"
            :src="`${appUrl}${user.profilePhoto}?rand=${rand}`"
            :alt="$store.state.userName"
          >
          <img
            v-else
            src="../../../assets/placeholder-person.jpg"
            :alt="$store.state.userName"
          >
          <b-button class="photo-button" @click="showPhotoModal"><b-icon icon="camera-plus" /></b-button>
        </div>
      </div>
    </div>
    <b-modal v-model="isModalActive" has-modal-card>
      <div class="modal-card">
        <div class="modal-card-body">
          <div class="content" style="text-align: center">
            <div>
              <b-upload ref="file" v-model="newPhoto" accept="image/png, image/jpeg" drag-drop>
                <section class="section">
                  <div class="content has-text-centered">
                    <p>
                      <b-icon
                        icon="upload"
                        size="is-large">
                      </b-icon>
                    </p>
                    <p>Suelta tus archivos aquí o has click para subirlos.</p>
                  </div>
                </section>
              </b-upload>
              <p class="mt-4"><small>Tamaño máximo: 1024kb. Dimensiones máximas: 1080*1080</small></p>
              <b-loading v-if="isPhotoUploading" :is-full-page="true" v-model="isPhotoUploading" :can-cancel="true"></b-loading>
            </div>
          </div>
        </div>
      </div>
    </b-modal>
    <ValidationObserver v-slot="{ handleSubmit, invalid }">
      <form @submit.prevent="handleSubmit(save)" class="form-section p-3 mt-3">
        <ValidationProvider
          v-slot="{ errors }"
          name="RUN"
          rules="required|run"
          class="column is-6 is-full-mobile"
        >
          <b-field
            :type="{'is-danger': errors.length || !isValidDni}"
            :message="[
              errors.length ? errors[0] : '',
              !errors.length && !isValidDni ?  'El RUN ya existe' : ''
            ]"
            label="RUN"
          >
            <b-input v-model="user.dni" placeholder="RUN" :disabled="!canEdit" expanded> </b-input>
          </b-field>
        </ValidationProvider>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Nombre"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Nombre"
            >
              <b-input v-model="user.name" placeholder="Nombre" :disabled="!canEdit"> </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Segundo nombre"
            rules=""
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Segundo nombre"
            >
              <b-input v-model="user.secondName" placeholder="Segundo nombre" :disabled="!canEdit">
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Apellido"
            rules="required"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Apellido"
            >
              <b-input v-model="user.lastName" placeholder="Apellido" :disabled="!canEdit">
              </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Segundo apellido"
            rules=""
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Segundo apellido"
            >
              <b-input
                v-model="user.secondLastname"
                placeholder="Segundo Apellido"
                :disabled="!canEdit"
              >
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Correo"
            rules="required|email"
            class="column is-full-mobile"
          >
            <b-field
              :type="{'is-danger': errors.length || !isValidEmail}"
              :message="[
                errors.length ? errors[0] : '',
                !errors.length && !isValidEmail ?  'El correo ya existe' : ''
              ]"
              label="Correo"
            >
              <b-input v-model="user.email" placeholder="Correo" :disabled="!canEdit">
              </b-input>
            </b-field>
          </ValidationProvider>
          <ValidationProvider
            v-slot="{ errors }"
            name="Teléfono"
            rules="required|min:6"
            class="column is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Teléfono"
            >
              <p class="control">
                <span class="button is-static">+56 9</span>
              </p>
              <b-input v-model="user.phone" placeholder="Teléfono" :disabled="!canEdit" expanded>
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-collapse v-if="canEdit" :open.sync="openChangePassword" position="is-bottom" aria-id="changePasswordForm">
          <template #trigger="props">
            <a aria-controls="changePasswordForm" class="column is-full-mobile">
              <b-icon :icon="!props.open ? 'menu-down' : 'menu-up'"></b-icon>
              {{ !props.open ? 'Cambiar contraseña' : 'Cancelar cambio de contraseña' }}
            </a>
          </template>
          <b-field grouped group-multiline v-if="openChangePassword">
            <ValidationProvider
              v-slot="{ errors }"
              name="Contraseña Actual"
              rules="required|confirmed:confirmPassword"
              class="column is-6 is-full-mobile"
            >
              <b-field
                :type="errors.length > 0 ? 'is-danger' : ''"
                :message="errors[0]"
                label="Contraseña Actual"
              >
                <b-input
                  type="password"
                  v-model="user.currentPassword"
                  placeholder="Contraseña Actual"
                  password-reveal
                  :disabled="!canEdit"
                >
                </b-input>
              </b-field>
            </ValidationProvider>
          </b-field>
          <b-field grouped group-multiline v-if="openChangePassword">
            <ValidationProvider
              v-slot="{ errors }"
              name="Nueva Contraseña"
              rules="required|confirmed:confirmPassword"
              class="column is-full-mobile"
            >
              <b-field
                :type="errors.length > 0 ? 'is-danger' : ''"
                :message="errors[0]"
                label="Nueva Contraseña"
              >
                <b-input
                  type="password"
                  v-model="user.password"
                  placeholder="Nueva Contraseña"
                  password-reveal
                  :disabled="!canEdit"
                >
                </b-input>
              </b-field>
            </ValidationProvider>
            <ValidationProvider
              v-slot="{ errors }"
              name="Confirmar contraseña"
              rules="required"
              class="column is-full-mobile"
              vid="confirmPassword"
            >
              <b-field
                :type="errors.length > 0 ? 'is-danger' : ''"
                :message="errors[0]"
                label="Confirmar contraseña"
              >
                <b-input
                  type="password"
                  v-model="user.confirmPassword"
                  placeholder="Confirmar contraseña"
                  password-reveal
                  :disabled="!canEdit"
                >
                </b-input>
              </b-field>
            </ValidationProvider>
          </b-field>
        </b-collapse>
        <b-field class="column is-full-mobile" v-if="canEdit">
          <p class="control">
            <b-button type="is-light" @click="cancel"> Cancelar </b-button>
            <b-button type="is-link" native-type="submit" :disabled="invalid || !isValidEmail || !isValidDni">
              Guardar
            </b-button>
          </p>
        </b-field>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { Formatters } from "@/mixins/formatters";

export default {
  mixins: [Formatters()],
  props: {
    userId: {
      type: String,
      require: true,
    },
    canEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      searchDni: null,
      user: {
        dni: null,
        name: null,
        secondName: null,
        lastName: null,
        secondLastname: null,
        userName: null,
        email: null,
        phone: null,
        currentPassword: null,
        password: null,
        confirmPassword: null,
        role: null
      },
      openChangePassword: false,
      originalUser: null,
      isNewDni: false,
      validatedDni: false,
      validatedEmail: false,
      isNewEmail: false,
      countries: [],
      role: null,
      isModalActive: false,
      newPhoto: null,
      isPhotoUploading: false,
      rand: null,
    };
  },
  computed: {
    isValidEmail () {
      return (this.validatedEmail && this.isNewEmail) || !this.validatedEmail || !this.user.email || (this.user.email === this.originalUser.email)
    },
    isValidDni () {
      return (this.validatedDni && this.isNewDni) || !this.validatedDni || !this.user.dni || (this.user.dni.replaceAll(".", "").toLowerCase() === this.originalUser.dni.replaceAll(".", "").toLowerCase())
    },
    currentTenant() {
      return this.$store.state.currentTenant || { name: null };
    },
    appUrl() {
      return this.$store.state.appUrl || '';
    }
  },
  watch: {
    openChangePassword (val) {
      if(!val) {
        this.user.password = null;
        this.user.confirmPassword = null;
      }
    },
    "user.dni": {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.user.dni = this.runFormatting(val);
        }
      },
    },
    "user.phone": {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.user.phone = this.phoneFormatting(val);
        }
      },
    },
    newPhoto: {
      async handler(val) {
        if (val) {
          this.isPhotoUploading = true;
          const data = new FormData();
          data.append('photo', val);
          const response = await this.$store.dispatch('users/uploadPhoto', {
            id: this.user.id,
            data,
          });
          console.log(response);
          if (response.errors) {
            this.isPhotoUploading = false;
            this.$buefy.dialog.alert({
              icon: 'close-circle',
              hasIcon: true,
              message: response.errors.photo[0] === 'validation.dimensions' ? 'Las dimensiones de la imagen exceden los límites de 1080x1080 pixeles.' : 'El archivo debe pesar menos de 1024kb.',
              confirmText: 'Aceptar',
              type: 'is-danger',
            });
          } else {
            this.loadUser(response.data);
            this.rand = Date.now();
            this.$store.commit('user', { avatar: `${this.appUrl}${this.user.profilePhoto}?rand=${this.rand}` });
            this.isPhotoUploading = false;
            this.isModalActive = false;
          }
        }
      },
    },
  },
  async mounted() {
    this.loadUser();
    await Promise.all([
      this.loadCountries(),
      this.loadRoles()
    ])
  },
  methods: {
    cancel() {
      this.$emit('update:can-edit', false);
    },
    showPhotoModal() {
      this.isModalActive = true;
    },
    async loadUser (user) {
        if (!user) user = this.$store.state.user;
        user = this.renameKeysOfObject(user, {
          'id': 'id',
          'dni': 'dni',
          'name': 'name',
          'second_name': 'secondName',
          'lastname': 'lastName',
          'second_lastname': 'secondLastname',
          'username': 'userName',
          'email': 'email',
          'phone': {
            name: 'phone',
            format: (phone) => (phone ? phone.slice(4) : phone)
          },
          'password': 'password',
          'roles': {
            name: 'role',
            format: (roles) => (roles && roles.length > 0 ? roles[0] : null)
          },
          'profile_photo': 'profilePhoto',
        });
        this.user = JSON.parse(JSON.stringify(user));
        this.originalUser = JSON.parse(JSON.stringify(user));
    },
    async loadCountries() {
      const countriesData = await this.$store.dispatch(
        "countries/getCountries",
        {}
      );
      this.countries = countriesData.data;
    },
    async loadRoles() {
      const response = await this.$store.dispatch(
        "roles/getRoles",
         {
          name: this.user.role.name,
        },
      );
      this.user.role = response.data[0].id;
    },
    async save() {
      let data = this.renameKeysOfObject(this.user, {
        'id': 'id',
        'dni': {
          name: 'dni',
          format: (dni) => (dni.replaceAll(".", ""))
        },
        'name': 'name',
        'secondName': 'second_name',
        'lastName': 'lastname',
        'secondLastname': 'second_lastname',
        'userName': 'username',
        'email': 'email',
        'phone': {
          name: 'phone',
          format: (phone) => (`+569${phone.replaceAll(' ', '')}`)
        },
        'currentPassword': 'current_password',
        'password': 'password',
        'role': {
          name: 'roles',
          format: (role) => (role ? [role] : []),
        },
      });
      if (!data.password) delete data.password;
      try {
        const response = await this.$store.dispatch("users/updateUser", data);
        this.updateStore(response.data);
        this.notify({
          message: 'Actualización Exitosa',
          type: 'is-success'
        });
        this.$emit('update:can-edit', false);
      } catch (e) {
        console.log(e);
        this.notify({
          message: 'Actualización Fallida',
          type: 'is-danger'
        });
      }
    },
    updateStore(user) {
      const permissions = this.$store.state.user.permissions;
      const roles = this.$store.state.user.roles;
      user.roles = roles;
      user.permissions = permissions;
      this.$store.commit('user', {
        name: `${user.name} ${user.lastname ? user.lastname : ''}`,
        email: `${user.email}`,
        fullUser: user,
      });
    },
    redirect() {
      this.$inertia.visit(route("users.index"));
    },
    toTenant() {
      this.$inertia.visit(route('tenant.index'));
    },
  },
};
</script>

<style scoped>
  .is-user-avatar, img {
    height: 175px;
    width: 175px;
    position: relative;
    margin: auto;
  }

  .photo-button {
    position: absolute;
    bottom: 2px;
    right: 5px;
    border-radius: 50%;
    height: 30%;
    width: 30%;
    background-color: #0085CF;
    color: white;
    padding: 2px;
    text-align: center;
    justify-content: center;
    border: 7px solid white;
  }

  .photo-button > span {
    margin-top: 25%;
  }
</style>
