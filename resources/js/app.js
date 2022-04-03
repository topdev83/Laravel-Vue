// import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import moment from 'moment'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import '~/plugins'
import '~/components'

window.Vue = require('vue')

Vue.config.productionTip = false
Vue.prototype.moment = moment

Vue.use(Vuetify);

/* eslint-disable no-new */
const app = new Vue({
  vuetify: new Vuetify(),
  i18n,
  store,
  router,
  ...App
})

export default app;