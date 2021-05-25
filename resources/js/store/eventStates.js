export default {
  namespaced: true,
  actions: {
    async getEventsStates ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.events-status.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
