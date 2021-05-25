export default {
  namespaced: true,
  actions: {
    async getReports ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.metabase-reports.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
