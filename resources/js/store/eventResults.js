export default {
  namespaced: true,
  actions: {
    async getEventResults ({ commit }, { query }) {
      try {
        const { data } = await axios.get(route('api.events-results.index', {
          ...query,
        }));
        return data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
