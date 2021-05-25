export default {
  namespaced: true,
  actions: {
    async getAllStatus ({ commit }, query) {
      try {
        const response = await axios.get(route('api.simcard-status.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getStatus ({ commit }, id) {
      try {
        const response = await axios.get(route('api.simcard-status.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createStatus ({ commit }, status) {
      try {
        const response = await axios.post(route('api.simcard-status.store'), status);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateStatus ({ commit }, status) {
      try {
        const response = await axios.patch(
          route('api.simcard-status.update', { status: status.id }),
          status
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteStatus ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.simcard-status.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
