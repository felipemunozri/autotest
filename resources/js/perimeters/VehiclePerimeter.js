import BasePerimeter from './BasePerimeter';

export default new BasePerimeter({
  purpose: 'vehicles',

  can: {
      list: function (context = this) {
        return context.hasPermission(`list-${context.purpose}`)
      },

      create: function (context = this) {
        return context.hasPermission(`create-${context.purpose}`)
      },

      show: function (context = this) {
        return context.hasPermission(`show-${context.purpose}`)
      },

      edit: function (context = this) {
        return context.hasPermission(`edit-${context.purpose}`)
      },

      remove: function (context = this) {
        return context.hasPermission(`delete-${context.purpose}`)
      },

      assignBeneficiaries: function (context = this) {
        return context.hasPermission(`assign-beneficiaries-${context.purpose}`)
      },

      assignDevices: function (context = this) {
        return context.hasPermission(`assign-devices-${context.purpose}`)
      },

      changeOwner: function (context = this) {
        return context.hasPermission(`change-owner-${context.purpose}`)
      },

      
  },
});