export default {
  namespaced: true,
  actions: {
    async getDashboard ({ commit }, query) {
      try {
        const response = await axios.get(route('api.summary.dashboard', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async getVehicleRanking ({ commit }, query) {
      try {
        const response = await axios.get(route('api.summary.vehicle-ranking', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async downloadVehicleRanking ({ commit }, { tenant }) {
      try {
        const response = await axios.get(route('api.exports.vehicle-ranking.pdf', {
          tenant,
        }),{
          responseType: 'blob'
        });
        console.log(response)
        return response;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
