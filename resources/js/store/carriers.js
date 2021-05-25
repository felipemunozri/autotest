export default {
  namespaced: true,
  actions: {
    async getCarriers ({ commit }, query) {
      try {
        const response = await axios.get(route('api.carriers.index', {
          ...query
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getCarrier ({ commit }, id) {
      try {
        const response = await axios.get(route('api.carriers.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createCarrier ({ commit }, carrier) {
      try {
        const response = await axios.post(route('api.carriers.store'), carrier);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateCarrier ({ commit }, carrier) {
      try {
        const response = await axios.patch(
          route('api.carriers.update', { carrier: carrier.id }),
          carrier
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteCarrier ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.carriers.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
