export default {
  namespaced: true,
  actions: {
    async getCountries ({ commit }, query) {
      try {
        const response = await axios.get(route('api.countries.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getCountry ({ commit }, id) {
      try {
        const response = await axios.get(route('api.countries.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createCountry ({ commit }, country) {
      try {
        const response = await axios.post(route('api.countries.store'), country);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateCountry ({ commit }, country) {
      try {
        const response = await axios.patch(
          route('api.countries.update', { country: country.id }),
          country
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteCountry ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.countries.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
