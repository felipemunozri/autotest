export default {
  namespaced: true,
  actions: {
    async getDeviceModels ({ commit }, query) {
      try {
        const response = await axios.get(route('api.device-models.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getDeviceModel ({ commit }, id) {
      try {
        const response = await axios.get(route('api.device-models.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createDeviceModel ({ commit }, deviceModel) {
      try {
        const response = await axios.post(route('api.device-models.store'), deviceModel);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateDeviceModel ({ commit }, deviceModel) {
      try {
        const response = await axios.patch(
          route('api.device-models.update', { deviceModel: deviceModel.id }),
          deviceModel
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteDeviceModels ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.device-models.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
