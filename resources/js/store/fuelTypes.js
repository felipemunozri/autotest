export default {
  namespaced: true,
  actions: {
    async getFuelTypes ({ commit }, query) {
      try {
        const response = await axios.get(route('api.fuel-types.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getFuelType ({ commit }, id) {
      try {
        const response = await axios.get(route('api.fuel-types.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createFuelType ({ commit }, fuelType) {
      try {
        const response = await axios.post(route('api.fuel-types.store'), fuelType);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateFuelType ({ commit }, fuelType) {
      try {
        const response = await axios.patch(
          route('api.fuel-types.update', { fuelType: fuelType.id }),
          fuelType
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteFuelType ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.fuel-types.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
