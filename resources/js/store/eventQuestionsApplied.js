export default {
  namespaced: true,
  actions: {
    async createList ({ commit }, answers) {
      try {
        const response = await axios.post(
          route('api.event-questions-applied.store-list'),
          { list: answers }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
    async updateOrCreate ({ commit }, answer) {
      try {
        const response = await axios.post(
          route('api.event-questions-applied.update-or-store'),
          answer
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
