export default {
  namespaced: true,
  actions: {
    async getVehicles ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.vehicles.index', {
          ...query,
          include: [
            'device.simCard.status',
            'device.status',
            'country',
            'carBrand',
            'beneficiaries'
          ],
        }));
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async getVehicle ({ commit }, id) {
      try {
        const response = await axios.get(
          route('api.vehicles.show', { id }), {
            include: [
              'device.simCard.carrier',
              'device.model',
              'beneficiaries',
              'carBrand',
              'fuelType',
            ],
          });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createVehicle ({ commit }, vehicle) {
      try {
        const response = await axios.post(route('api.vehicles.store'), vehicle);
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async updateVehicle ({ commit }, vehicle) {
      try {
        const response = await axios.patch(
          route('api.vehicles.update', { id: vehicle.id }),
          vehicle
        );
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },

    async deleteVehicle ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.vehicles.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error)
      }
    },
  },
}
