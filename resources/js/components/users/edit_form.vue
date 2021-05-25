<template>
  <div>
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
            <b-input v-model="user.dni" placeholder="RUN" expanded> </b-input>
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
              <b-input v-model="user.name" placeholder="Nombre"> </b-input>
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
              <b-input v-model="user.secondName" placeholder="Segundo nombre">
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
              <b-input v-model="user.lastName" placeholder="Apellido">
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
              <b-input v-model="user.email" placeholder="Correo"> </b-input>
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
              <b-input v-model="user.phone" placeholder="Teléfono" expanded>
              </b-input>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-field grouped group-multiline>
          <ValidationProvider
            v-slot="{ errors }"
            name="Perfil"
            rules="required"
            class="column is-half is-full-mobile"
          >
            <b-field
              :type="errors.length > 0 ? 'is-danger' : ''"
              :message="errors[0]"
              label="Perfil"
            >
              <b-select placeholder="seleccione un perfil" v-model="user.role" expanded>
                <option :value="role.id" v-for="(role,index) in roles" :key="`role-${index}`" class="is-capitalized">{{ role.description }}</option>
            </b-select>
            </b-field>
          </ValidationProvider>
        </b-field>
        <b-collapse :open.sync="openChangePassword" position="is-bottom" aria-id="changePasswordForm">
          <template #trigger="props">
            <a aria-controls="changePasswordForm" class="column is-full-mobile">
              <b-icon :icon="!props.open ? 'menu-down' : 'menu-up'"></b-icon>
              {{ !props.open ? 'Cambiar contraseña' : 'Cancelar cambio de contraseña' }}
            </a>
          </template>
          <b-field grouped group-multiline v-if="openChangePassword">
            <ValidationProvider
              v-slot="{ errors }"
              name="Contraseña"
              rules="required|confirmed:confirmPassword"
              class="column is-full-mobile"
            >
              <b-field
                :type="errors.length > 0 ? 'is-danger' : ''"
                :message="errors[0]"
                label="Contraseña"
              >
                <b-input
                  type="password"
                  v-model="user.password"
                  placeholder="Contraseña"
                  password-reveal
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
                >
                </b-input>
              </b-field>
            </ValidationProvider>
          </b-field>
        </b-collapse>
        <b-field class="column is-full-mobile"
          ><!-- Label left empty for spacing -->
          <p class="control">
            <b-button type="is-light" @click="redirect"> Cancelar </b-button>
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
      roles: []
    };
  },
  computed: {
    isValidEmail () {
      return (this.validatedEmail && this.isNewEmail) || !this.validatedEmail || !this.user.email || (this.user.email === this.originalUser.email)
    },
    isValidDni () {
      return (this.validatedDni && this.isNewDni) || !this.validatedDni || !this.user.dni || (this.user.dni.replaceAll(".", "").toLowerCase() === this.originalUser.dni.replaceAll(".", "").toLowerCase())
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
          this.validateDni();
        }
      },
    },
    "user.email": {
      handler(val, previousVal) {
        if (val !== previousVal) {
          this.validateEmail();
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
  },
  async mounted() {
    await Promise.all([
      this.fetchData(),
      this.loadCountries(),
      this.loadRoles()
    ])
  },
  methods: {
    async fetchData () {
      if (this.userId) {
        const response = await this.$store.dispatch("users/getUser", {
          id: this.userId,
          query: {
            include: ['roles']
          }
        });
        let user = response.data;
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
            format: (roles) => (roles && roles.length > 0 ? roles[0].id : null)
          }
        })
        this.user = JSON.parse(JSON.stringify(user));
        this.originalUser = JSON.parse(JSON.stringify(user));
      }
    },
    validateEmail: _.debounce(async function () {
      this.validatedEmail = false;
      const email = this.user.email;
      if (email) {
        const response = await this.$store.dispatch(
          "users/getUsers",
          {
            query: {
              email: email,
            },
          }
        );
        const users = response.data;
        if (users.length && users[0]) {
          this.isNewEmail = false;
        } else {
          this.isNewEmail = true;
        }
        this.validatedEmail = true;
      }
    }, 700),
    validateDni: _.debounce(async function () {
      this.validatedDni = false;
      const dni = this.user.dni;
      if (dni) {
        const response = await this.$store.dispatch(
          "users/getUsers",
          {
            query: {
              dni: dni.replaceAll(".", "").toUpperCase(),
            },
          }
        );
        const users = response.data;
        if (users.length && users[0]) {
          this.isNewDni = false;
        } else {
          this.isNewDni = true;
        }
        this.validatedDni = true;
      }
    }, 700),
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
        {}
      );
      this.roles = response.data;
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
        'password': 'password',
        'role':{
          name: 'roles',
          format: (role) => role ? [role] : []
        }
      })
      if (!data.password) delete data.password;
      try {
        await this.$store.dispatch("users/updateUser", data);
        this.notify({
          message: 'Actualización Exitosa',
          type: 'is-success'
        });
        this.redirect();
      } catch (e) {
        console.log(e);
        this.notify({
          message: 'Actualización Fallida',
          type: 'is-danger'
        });
      }
    },
    redirect() {
      this.$inertia.visit(route("users.index"));
    },
  },
};
</script>

<style scoped>
.form-section {
  background-color: #f8f8f8;
  border-radius: 10px;
}
</style>