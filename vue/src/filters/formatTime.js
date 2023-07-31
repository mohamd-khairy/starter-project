import Vue from 'vue'
import moment from 'moment-timezone'

Vue.filter('formatTime', (value) => {

  if (value) {
    return moment(value).format('HH:MM A')
  }

  return ''
})
