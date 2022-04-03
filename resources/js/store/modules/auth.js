import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  user: null,
  token: localStorage.getItem('token'),
  oauthConnections: []
}

// getters
export const getters = {
  user: state => state.user,
  token: state => state.token,
  check: state => state.user !== null,
  oauthConnections: state => state.oauthConnections
}

// mutations
export const mutations = {
  [types.SAVE_TOKEN] (state, { token, remember }) {
    state.token = token
    localStorage.setItem('token', token)
  },

  [types.FETCH_USER_SUCCESS] (state, { user, oauthData }) {
    state.user = user
    state.oauthConnections = oauthData
  },

  [types.FETCH_USER_FAILURE] (state) {
    state.token = null
    localStorage.removeItem('token')
  },

  [types.LOGOUT] (state) {
    state.user = null
    state.token = null

    localStorage.removeItem('token')
  },

  [types.UPDATE_USER] (state, { user }) {
    state.user = user
  }
}

// actions
export const actions = {
  saveToken ({ commit, dispatch }, payload) {
    commit(types.SAVE_TOKEN, payload)
  },

  async fetchUser ({ commit }) {
    try {
      const { data } = await axios.get('/api/user')

      const oauthData = await axios.get('/api/oauth/connected')

      commit(types.FETCH_USER_SUCCESS, { user: data, oauthData: oauthData.data })
    } catch (e) {
      commit(types.FETCH_USER_FAILURE)
    }
  },

  updateUser ({ commit }, payload) {
    commit(types.UPDATE_USER, payload)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }

    commit(types.LOGOUT)
  },

  async fetchOauthUrl ({ rootState, commit }, { provider, domain, token }) {
    let centralDomain = window.config.centralDomain
    let baseUrl = 'register.' + centralDomain
    const { data } = await axios.post(`//${baseUrl}/oauth/${provider}`, { domain, token })

    return data.url
  },

  async fetchAuthStateToken () {
    if (!window.config.isTenant) {
      return ''
    }

    const { data } = await axios.get(`/api/auth/state`)

    return data.token
  }
}
