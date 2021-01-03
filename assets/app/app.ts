import 'babel-polyfill'
import Vue from 'vue'
import axios from 'axios'
import Buefy from 'buefy'

import 'buefy/dist/buefy.css'


import App from './App.vue'

Vue.use(Buefy)

axios.defaults.withCredentials = true

Vue.config.productionTip = false
new Vue({
  el: '#app',
  components: { App },
  template: '<App/>'
});
