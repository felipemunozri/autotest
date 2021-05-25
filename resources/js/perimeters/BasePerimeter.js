import { Perimeter } from 'vue-kindergarten';

export default class BasePerimeter extends Perimeter {
  isAdmin() {
    return this.child && this.hasRole('admin');
  }

  isAdmin() {
    return this.child && this.hasRole('operator');
  }

  hasRole(role) {
    return this.child && this.child.roles.findIndex(x => x.name === role) >= 0;
  }

  hasPermission(permission) {
    return this.child && this.child.permissions.findIndex(x => x.name === permission) >= 0;
  }
}