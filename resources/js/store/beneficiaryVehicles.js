export default {
  namespaced: true,
  actions: {
    async getBeneficiariesVehicles ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.beneficiary-vehicle.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getBeneficiaryVehicle ({ commit }, id) {
      try {
        const response = await axios.get(route('api.beneficiary-vehicle.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createBeneficiaryVehicle ({ commit }, beneficiaryVehicle) {
      try {
        const response = await axios.post(
          route('api.beneficiary-vehicle.store'),
          beneficiaryVehicle
        );
        response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateBeneficiaryVehicle ({ commit }, { beneficiary_id, vehicle_id, owner}) {
      try {
        const response = await axios.patch(
          route('api.beneficiary-vehicle.update'),
          {
            beneficiary_id, 
            vehicle_id,
            owner,
          }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteBeneficiaryVehicle ({ commit }, { beneficiary_id, vehicle_id}) {
      try {
        const response = await axios.delete(
          route('api.beneficiary-vehicle.delete'),
          {
            data: {
              beneficiary_id, 
              vehicle_id
            },
          }
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
