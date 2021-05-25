export default {
  namespaced: true,
  actions: {
    async getTasks ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.tasks.index', {
          ...query,
          include: [
            'answers',
            'action',
            'vehicle',
            'executor',
          ],
        }));
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async getTask ({ commit }, id) {
      try {
        const response = await axios.get(
          route('api.tasks.show', { id }), {
            include: [
              'answers',
              'action',
              'vehicle',
              'executor',
            ],
          });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createTask ({ commit }, task) {
      try {
        const response = await axios.post(route('api.tasks.store'), task);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateTask ({ commit }, task) {
      try {
        const response = await axios.patch(
          route('api.tasks.update', { task: task.id }),
          task
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteTask ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.tasks.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
