import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
  config: {}
}

// getters
export const getters = {
  user: config => state.config
}

// mutations
export const mutations = {
  [types.FETCH_CONFIG_SUCCESS] (state, { config }) {
    state.config = config
  }

}

// actions
export const actions = {

  async fetchConfig ({ commit }) {
    try {
      const { data } = await axios.get('/api/config')
      commit(types.FETCH_CONFIG_SUCCESS, { config: data })
    } catch (e) {
      commit(types.FETCH_CONFIG_FAILURE)
    }
  }

}
