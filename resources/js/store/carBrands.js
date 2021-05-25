export default {
  namespaced: true,
  actions: {
    async getCarBrands ({ commit }, query) {
      try {
        const response = await axios.get(route('api.car-brands.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async getCarBrand ({ commit }, id) {
      try {
        const response = await axios.get(route('api.car-brands.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async createCarBrand ({ commit }, carBrand) {
      try {
        const response = await axios.post(route('api.car-brands.store'), carBrand);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateCarBrand ({ commit }, carBrand) {
      try {
        const response = await axios.patch(
          route('api.car-brands.update', { carBrand: carBrand.id}),
          carBrand
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteCarBrand ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.car-brands-delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
