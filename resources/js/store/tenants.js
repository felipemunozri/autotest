export default {
  namespaced: true,
  actions: {
    async getTenants ({ commit }, query) {
      try {
        const response = await axios.get(route('api.tenants.index', {
          ...query
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getTenant ({ commit }, id) {
      try {
        const include = [
          'country'
        ];
        const response = await axios.get(
          route('api.tenants.show', { id }),
          {params: { include }});
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createTenant ({ commit }, tenant) {
      try {
        const response = await axios.post(route('api.tenants.store'), tenant);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateTenant ({ commit }, tenant) {
      try {
        const response = await axios.patch(
          route('api.tenants.update', { tenant: tenant.id }),
          tenant
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteTenant ({ commit }, id) {
      try {
        const response = await axios.get(route('api.tenants.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async uploadPhoto({ commit }, { id, data }) {
      try {
        const response = await axios.post(
          route('api.tenants.upload-photo', { id }),
          data
        );
        return response.data;
      } catch (error) {
        return error.response.data;
      }
    },
  },
}
