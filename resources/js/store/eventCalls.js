export default {
  namespaced: true,
  actions: {
    async getEventCalls({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.event-calls.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getEventCall ({ commit }, id) {
      try {
        const response = await axios.get(route('api.event-calls.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createEventCall ({ commit }, eventCall) {
      try {
        const response = await axios.post(route('api.event-calls.store'), eventCall);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateEventCall ({ commit }, eventCall) {
      try {
        const response = await axios.patch(
          route('api.event-calls.update', { eventCall: eventCall.id }),
          eventCall
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async finishEventCall ({ commit }, eventCall) {
      try {
        const response = await axios.patch(
          route('api.event-calls.finish', { eventCall: eventCall.id }),
          eventCall
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteEventCall ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.event-calls.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
