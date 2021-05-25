export default {
  namespaced: true,
  actions: {
    async getEventQuestions ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.event-questions.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
