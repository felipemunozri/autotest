export default {
  namespaced: true,
  actions: {
    async getBeneficiaries ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.beneficiaries.index', {
          ...query,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getBeneficiary ({ commit }, id) {
      try {
        const response = await axios.get(route('api.beneficiaries.show', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createBeneficiary ({ commit }, beneficiary) {
      try {
        const response = await axios.post(route('api.beneficiaries.store'), beneficiary);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateBeneficiary ({ commit }, beneficiary) {
      try {
        const response = await axios.patch(
          route('api.beneficiaries.update', { id: beneficiary.id }),
          beneficiary
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteBeneficiary ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.beneficiaries.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
  },
}
