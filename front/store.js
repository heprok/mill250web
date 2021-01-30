import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    barColor: 'rgba(0, 0, 0, .3), rgba(0, 0, 0, .7)',
    barImage: 'build/images/sidebar.jpg',
    drawer: null,
    isNextDay: true,
    timeForTheDay: '08:00:00',
    isAdmin: false,
  },
  mutations: {
    SET_BAR_IMAGE (state, payload) {
      state.barImage = payload
    },
    SET_DRAWER (state, payload) {
      state.drawer = payload
    },
    SET_ADMIN: (state, payload ) => {
      state.isAdmin = payload;
    },
  },
  getters: {
    IS_ADMIN: state => {
      return state.isAdmin;
    },
    timeForTheDay: state => (day = null) => {
      let date = !!day ? new Date(day) : new Date();
      let today = date.toISOString().substr(0, 10) + 'T' + state.timeForTheDay;
      date.setDate(date.getDate() + 1);
      let yestarday = date.toISOString().substr(0,10) + 'T' + state.timeForTheDay
      return {
        start: today,
        end: yestarday
      }
    },
    timeForDay: state => {
      return state.timeForTheDay;
    }
  },
  actions: {

  },
})
