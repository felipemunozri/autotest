import BasePerimeter from './BasePerimeter';

export default new BasePerimeter({
  purpose: 'devices',

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

  },
});