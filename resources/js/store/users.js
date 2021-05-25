export default {
  namespaced: true,
  actions: {
    async getUsers ({ commit }, { query }) {
      try {
        const response = await axios.get(route('api.users.index'), {
          params: query,
        });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getUser ({ commit }, { id, query }) {
      try {
        const response = await axios.get(route('api.users.show', { id }), {
          params: query,
        });
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async createUser ({ commit }, user) {
      try {
        const response = await axios.post(route('api.users.store'), user);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async updateUser ({ commit }, user) {
      try {
        const response = await axios.patch(
          route('api.users.update', { user: user.id }),
          user
        );
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteUser ({ commit }, id) {
      try {
        const response = await axios.delete(route('api.users.delete', { id }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getCurrentTenant ({ commit }) {
      try {
        const response = await axios.get(route('api.logged-user.current-tenant'));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async uploadPhoto({ commit }, { id, data }) {
      try {
        const response = await axios.post(
          route('api.users.upload-photo', { id }),
          data
        );
        return response.data;
      } catch (error) {
        return error.response.data;
      }
    },

    async getUnreadNotifications({ commit }, { page, perPage }) {
      try {
        const response = await axios.get(route('api.user.notifications.index', {
          ...page ? { page } : {},
          ...perPage ? { per_page: perPage } : {},
          unread_only: true,
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },

    async getNotifications({ commit }, { page, perPage }) {
      try {
        const response = await axios.get(route('api.user.notifications.index', {
          ...page ? { page } : {},
          ...perPage ? { per_page: perPage } : {},
        }));
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
    
    async readNotifications({ commit }, { notificationId, readAll }) {
      try {
        let payload = {};
        if (notificationId !== undefined) payload = { notification_id: notificationId };
        else payload = { read_all: readAll };

        const response = await axios.patch(route('api.user.notifications.read'), payload);
        return response.data;
      } catch (error) {
        console.log(error);
      }
    },
    
    async deleteNotifications({ commit }, { notificationId, deleteAll }) {
      try {
        let payload
        if (notificationId) payload = { notification_id: notificationId };
        else payload = { delete_all: deleteAll };

        const response = await axios.delete(route('api.user.notifications.delete'), payload);
        return response;
      } catch (error) {
        console.log(error);
      }
    },
  }
}
