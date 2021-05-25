export default {
  namespaced: true,
  actions: {
    async getEvents ({ commit }, { query, include }) {
      query = query || {};
      include = include || [
        'vehicle',
        'beneficiary',
        'status',
        'receiver',
        'tasks.answers',
        'tasks.action',
        'answers',
      ];
      try {
        const response = await axios.get(route('api.events.index', {
          ...query,
          include,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getEvent ({ commit }, id) {
      try {
        const include = [
          'vehicle',
          'beneficiary',
          'status',
          'receiver',
          'tasks.answers',
          'tasks.action',
          'answers.question',
          'answers.alternative',
        ];
        const response = await axios.get(
          route('api.events.show', { id }),
          { params: { include } }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createEvent ({ commit }, event) {
      try {
        const response = await axios.post(route('api.events.store'), event);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateEvent ({ commit }, event) {
      try {
        const response = await axios.patch(
          route('api.events.update', { id: event.id}),
          event
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteEvent ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.events.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async findInProgress ({ commit }, vehicleId) {
      try {
        const response = await axios.post(route('api.events.find-in-progress', {
          vehicle_id: vehicleId,
          include: [
            'tasks.answers',
            'tasks.action',
            'answers',
            'status',
          ],
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async validateCode({ commit }, { beneficiaryId, eventId, code }) {
      try {
        const response = await axios.post(route('api.events.validate'), {
          beneficiary_id: beneficiaryId,
          event_id: eventId,
          code,
        });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async leaveEvent({ commit }, id) {
      try {
        const response = await axios.post(route('api.events.leave', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async finishEvent({ commit }, id) {
      try {
        const response = await axios.post(route('api.events.finish', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
