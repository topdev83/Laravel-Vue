import axios from 'axios'
import * as types from '../mutation-types'
import eventBus from '../../plugins/eventBus'
// state
export const state = {
  user: null,
  users: []
}

// getters
export const getters = {
  users: state => state.users
}

// mutations
export const mutations = {

  [types.FETCH_USERS] (state, { users }) {
    state.users = users
  },

  [types.STORE_USER] (state, { user }) {
    state.users.push(user)
  },

  [types.UPDATE_USERS] (state, { updatedUser }) {
    for (let i = 0; i < state.users.length; i++) {
      if (state.users[i].id === updatedUser.id) {
        console.log('updated', state.users[i])
        state.users[i] = updatedUser
      }
    }
  }

}

// actions
export const actions = {

  async fetchUsers ({ commit }) {
    try {
      const { data } = await axios.get('/api/users')
      commit(types.FETCH_USERS, { users: data.users })
    } catch (e) {
      console.log(e)
    }
  },
  async fetchUser ({ commit }, id) {
    try {
      const { data } = await axios.get('/api/users/' + id)
      console.log("data user=", data)
      return data.user
    } catch (e) {
      return {}
    }
  },

  async storeUser ({ commit, dispatch }, formData) {
    try {
      const { data } = await axios.post('/api/users', formData)
      eventBus.$emit('create-user')
      dispatch('fetchUsers')
    } catch (e) {
      eventBus.$emit('errors-user', e.response.data)
    }
  },
  async updateUser ({ commit, dispatch, rootState }, formData) {
    try {
      const { data } = await axios.patch('/api/users/' + formData.id, formData)

      dispatch('fetchUsers')
      dispatch('auth/fetchUser', null, { root: true })
      eventBus.$emit('create-user')
    } catch (e) {
      console.log(e)
    }
  },
  async deleteUser ({ commit }, { id }) {
    try {
      await axios.delete('/api/users/' + id)
    } catch (e) {
      console.log(e)
    }
  },
  // async changeUserPassword({commit}, userid, currentPass, newPassword) {
  //   try {
  //     const {data} = await axios.post('/api/users/password/reset' + userid, currentPass, newPassword)
  //   }catch(e) {
  //     console.log(e)
  //   }
  // }
}
