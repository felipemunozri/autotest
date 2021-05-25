export default {
  namespaced: true,
  actions: {
    async getDevices ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.devices.index', {
          ...query
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getDevice ({ commit }, { id, query }) {
      try {
        const response = await axios.get(route('api.devices.show', { id }), {
          params: query
        });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createDevice ({ commit }, device) {
      try {
        const response = await axios.post(route('api.devices.store'), device);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateDevice ({ commit }, device) {
      try {
        const response = await axios.patch(
          route('api.devices.update', { device: device.id }),
          device
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteDevice ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.devices.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async activateDevice ({ commit }, { plateNumber, serialNumber, deviceId }) {
      try {
        const response = await axios.post(route('api.devices.activation'), {
          plate_number: plateNumber,
          serial_number: serialNumber,
          device_id: deviceId,
        });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
