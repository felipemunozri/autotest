export default {
  namespaced: true,
  actions: {
    async begin ({ commit }, { vehicleId }) {
      try {
        const response = await axios.post(
          route('api.operation-sms.send', { vehicle: vehicleId }),
          {
            action: 'begin',
          }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async check ({ commit }, { vehicleId }) {
      try {
        const response = await axios.post(
          route('api.operation-sms.send', { vehicle: vehicleId }),
          {
            action: 'check'
          }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async send ({ commit }, { code, vehicleId, eventId }) {
      try {
        const response = await axios.post(
          route('api.operation-sms.send', { vehicle: vehicleId }),
          {
            action: code,
            vehicle_id: vehicleId,
            event_id: eventId,
          }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async sendConfirmationCode ({ commit }, { beneficiaryId, eventId }) {
      try {
        const response = await axios.post(route('api.operation-sms.send-code'), {
          beneficiary_id: beneficiaryId,
          event_id: eventId,
        });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
