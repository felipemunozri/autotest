require("./bootstrap");

import { App, plugin } from '@inertiajs/inertia-vue';
import Vue from "vue";
import VueMeta from "vue-meta";
import store from './store';
import child from './child';

import IdleVue from 'idle-vue';

import Buefy from 'buefy';
import 'bulma/css/bulma.css';
import 'buefy/dist/buefy.css';

import '../sass/main.scss';
import '@mdi/font/css/materialdesignicons.css';
import moment from 'moment-timezone';
import { ValidationProvider, ValidationObserver, localize, extend } from 'vee-validate';
import { required, email, min, max, numeric, confirmed } from 'vee-validate/dist/rules';
import es from 'vee-validate/dist/locale/es';
import { CustomRules } from './mixins/custom_rules';
import * as GmapVue from 'gmap-vue';
import GmapCluster from 'gmap-vue/dist/components/cluster'
import { isMobile } from 'mobile-device-detect';
import AsideMenuList from './components/layouts/asideMenu/asideMenuList';
import NotificationMixin from './mixins/notifications';
import VueKindergarten from 'vue-kindergarten';
import FadeInTrasition from '@/components/common/transitions/fade_in';

Vue.use(plugin);
Vue.component('AsideMenuList', AsideMenuList);
Vue.component('FadeInTrasition', FadeInTrasition);

Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
extend('required', required);
extend('confirmed', confirmed);
extend('email', email);
extend('min', min);
extend('max', max);
extend('numeric', numeric);
localize('es', es);

extend('run', (run) => {
  const isValid = CustomRules().runValidator(run)
  return isValid || 'RUT Inválido'
});

moment.tz.setDefault('America/Santiago');
require('moment/locale/es');
moment.locale('es');
Vue.use(require('vue-moment'), {
  moment
});


Vue.use(Buefy);
Vue.use(VueMeta, {
  refreshOnceOnNavigation: true
});
Vue.use(GmapVue, {
  load: {
    key: process.env.MIX_GMAP_KEY,
    libraries: 'places,visualization',
  },
  installComponents: true
});
Vue.component('GmapCluster', GmapCluster) 

Vue.prototype.moment = moment;
Vue.prototype.route = route;

const eventsHub = new Vue();
Vue.use(IdleVue, {
  eventEmitter: eventsHub,
  store,
  idleTime: process.env.MIX_IDLE_TIME,
  startAtIdle: false,
});

Vue.mixin({
  methods: {
    moment,
    route
  }
});
Vue.mixin(NotificationMixin);

import BaseLayout from './components/layouts/baseLayout';

const app = document.getElementById("app");

Vue.use(VueKindergarten, {
  child,
});

new Vue({
  store,
  render: h =>
    h(App, {
      props: {
        initialPage: JSON.parse(app.dataset.page),
        resolveComponent: name => import(`./pages/${name}`)
        .then(({ default: page }) => {
          page.layout = page.layout === undefined ? BaseLayout : page.layout
          return page
        }),
      }
    }),
  beforeMount(){
    if (isMobile) {
      store.commit('asideMobileStateToggle', false)
    } else {
      store.commit('asideMobileStateToggle', true)
    }
  }
}).$mount(app);
