export default {
  namespaced: true,
  actions: {
    async getRoles ({ commit }, query) {
      try {
        const response = await axios.get(route('api.roles.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
