import Vue from 'vue';
import Vuex from 'vuex';
import actions from './actions';
import beneficiaries from './beneficiaries';
import beneficiaryVehicles from './beneficiaryVehicles';
import carBrands from './carBrands';
import carriers from './carriers';
import countries from './countries';
import deviceModels from './deviceModels';
import devices from './devices';
import eventQuestions from './eventQuestions';
import eventQuestionsApplied from './eventQuestionsApplied';
import events from './events';
import eventStates from './eventStates';
import eventResults from './eventResults';
import fuelTypes from './fuelTypes';
import simCards from './simCards';
import summary from './summary';
import tasks from './tasks';
import tenants from './tenants';
import users from './users';
import vehicles from './vehicles';
import deviceStatus from './deviceStatus';
import eventCalls from './eventCalls';
import simCardStatus from './simCardStatus';
import metabaseReports  from './metabaseReports';
import roles from './roles';

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    actions,
    beneficiaries,
    beneficiaryVehicles,
    carBrands,
    carriers,
    countries,
    deviceModels,
    deviceStatus,
    devices,
    eventQuestions,
    eventQuestionsApplied,
    events,
    eventStates,
    eventResults,
    fuelTypes,
    simCards,
    simCardStatus,
    summary,
    tasks,
    tenants,
    users,
    vehicles,
    roles,
    eventCalls,
    metabaseReports
  },
  state: {
    /* User */
    userName: null,
    userEmail: null,
    userProfile: null,
    userAvatar: null,
    user: {},
    currentTenant: {},
    appUrl: null,

    /* NavBar */
    isNavBarVisible: true,

    /* FooterBar */
    isFooterBarVisible: true,

    /* Aside */
    isAsideVisible: true,
    isAsideMobileExpanded: false
  },
  mutations: {
    /* A fit-them-all commit */
    basic (state, payload) {
      state[payload.key] = payload.value
    },

    /* User */
    user (state, payload) {
      if (payload.name) {
        state.userName = payload.name
      }
      if (payload.email) {
        state.userEmail = payload.email
      }
      if (payload.avatar) {
        state.userAvatar = payload.avatar
      }
      if (payload.role) {
        state.userProfile = payload.role
      }
      if (payload.fullUser) {
        state.user = payload.fullUser;
      }
    },

    setCurrentTenant (state, tenant) {
      state.currentTenant = tenant;
    },

    setAppUrl (state, url) {
      state.appUrl = url;
    },

    /* Aside Mobile */
    asideMobileStateToggle (state, payload = null) {
      const htmlClassName = 'has-aside-mobile-expanded'
      const htmlClassNameDesk = 'has-aside-expanded'

      let isShow

      if (payload !== null) {
        isShow = payload
      } else {
        isShow = !state.isAsideMobileExpanded
      }

      if (isShow) {
        document.documentElement.classList.add(htmlClassName)
        document.documentElement.classList.add(htmlClassNameDesk)
      } else {
        document.documentElement.classList.remove(htmlClassName)
        document.documentElement.classList.remove(htmlClassNameDesk)
      }

      state.isAsideMobileExpanded = isShow
    }
  },
  actions: {

  }
})
