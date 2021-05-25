export default {
  namespaced: true,
  actions: {
    async getSimCards ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.sim-cards.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getSimCard ({ commit }, id) {
      try {
        const include = [
          'country',
          'carrier'
        ]
        const response = await axios.get(route('api.sim-cards.show', { id }),
          { params: { include } }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createSimCard ({ commit }, simCard) {
      try {
        const response = await axios.post(route('api.sim-cards.store'), simCard);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateSimCard ({ commit }, simCard) {
      try {
        const response = await axios.patch(
          route('api.sim-cards.update', { id: simCard.id }),
          simCard
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteSimCard ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.sim-cards.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
