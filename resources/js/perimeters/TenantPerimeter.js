import BasePerimeter from './BasePerimeter';

export default new BasePerimeter({
  purpose: 'tenants',

  can: {
      edit: function (context = this) {
        return context.hasPermission(`edit-${context.purpose}`)
      },
  },
});