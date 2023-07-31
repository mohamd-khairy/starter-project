<template>
  <div class="d-flex flex-grow-1 flex-column">
    <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">رحلة رقم 153</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn icon @click>
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </div>
    <v-row class="flex-grow-0 mb-1" dense>
      <v-col cols="12">
        <v-card>
          <div class="d-flex justify-center">
            <img src="/images/Flight-Data-Screen.png" alt="" class="my-3">
          </div>
        </v-card>
      </v-col>
    </v-row>

  </div>
</template>

<script>

export default {
  components: {

  },
  filters: {
    dateCell (value) {
      const dt = new Date(value)

      return dt.getDate()
    },
    date (val) {
      return val ? val.toLocaleString() : 'filter'
    }
  },
  data() {
    return {
      loadingInterval: null,
      isLoading1: true,
      isLoading2: true,
      isLoading3: true,
      isLoading4: true,

      breadcrumbs: [{
        text: this.$t('menu.flights'),
        disabled: false,
        href: '/flights'
      }, {
        text: this.$t('menu.flightsShow')
      }],

      ordersSeries: [{
        name: 'Orders',
        data: [
          ['2020-02-02', 34],
          ['2020-02-03', 43],
          ['2020-02-04', 40],
          ['2020-02-05', 43]
        ]
      }],

      selectedUsers: [],
      headers: [
        { text: this.$t('tables.id'), align: 'start', value: 'id' },
        { text: this.$t('tables.date'), value: 'date' },
        { text: this.$t('tables.time'), value: 'time' },
        { text: this.$t('tables.location'), value: 'location' },
        { text: this.$t('tables.actions'), sortable: false, align: 'center', value: 'action' }
      ],
      items: [
        {
          id: '28347',
          date: '2020-05-10',
          time: '7:12 ص',
          location: 'موقع 1'
        },
        {
          id: '23838',
          date: '2020-05-11',
          time: '10:20 ص',
          location: 'موقع 2'
        },
        {
          id: '28139',
          date: '2020-05-11',
          time: '2:30 م',
          location: 'موقع 3'
        },
        {
          id: '28540',
          date: '2020-06-12',
          time: '5:00 م',
          location: 'موقع 4'
        },
        {
          id: '28841',
          date: '2020-06-13',
          time: '9:15 م',
          location: ' موقع 1'
        },
        {
          id: '20837',
          date: '2020-05-10',
          time: '7:12 ص',
          location: 'موقع 1'
        },
        {
          id: '28338',
          date: '2020-07-11',
          time: '10:20 ص',
          location: 'موقع 2'
        },
        {
          id: '28639',
          date: '2020-10-11',
          time: '2:30 م',
          location: 'موقع 3'
        },
        {
          id: '28840',
          date: '2020-05-12',
          time: '5:00 م',
          location: 'موقع 4'
        },
        {
          id: '2841',
          date: '2020-10-13',
          time: '9:15 م',
          location: 'fire'
        },
        {
          id: '2837',
          date: '2020-05-10',
          time: '7:12 ص',
          location: 'موقع 2'
        },
        {
          id: '28338',
          date: '2020-05-11',
          time: '10:20 ص',
          location: 'موقع 1'
        },
        {
          id: '28349',
          date: '2020-05-11',
          time: '2:30 م',
          location: 'موقع 1'
        },
        {
          id: '28540',
          date: '2020-05-12',
          time: '5:00 م',
          location: 'موقع 2'
        },
        {
          id: '25841',
          date: '2020-05-13',
          time: '9:15 م',
          location: 'موقع 5'
        },
        {
          id: '28357',
          date: '2020-05-10',
          time: '7:12 ص',
          location: 'موقع 1'
        },
        {
          id: '28348',
          date: '2020-05-11',
          time: '10:20 ص',
          location: 'work'
        },
        {
          id: '28339',
          date: '2020-05-11',
          time: '2:30 م',
          location: 'موقع 2'
        },
        {
          id: '28440',
          date: '2020-05-12',
          time: '5:00 م',
          location: 'موقع 3'
        },
        {
          id: '28641',
          date: '2020-05-13',
          time: '9:15 م',
          location: 'موقع 3'
        }
      ],

      filter:[this.$t('dashboard.thismonth'), this.$t('dashboard.lastweek'), this.$t('dashboard.lastmonth')],
      defaultSelected: this.$t('dashboard.thismonth'),

      filterLabel: true,
      dateRange: {},
      startDate:  new Date(),
      endDate:  new Date()

    }

  },
  watch: {
    selectedUsers(val) {

    },
    created() {
      this.endDate.setDate(this.endDate.getDate() + 6)
      this.dateRange = {
        startDate , endDate
      }
    }
  },
  computed: {
    theme() {
      return this.$vuetify.theme.isDark
        ? this.$vuetify.theme.defaults.dark
        : this.$vuetify.theme.defaults.light
    }
  },
  mounted() {
    let count = 0

    // DEMO delay for loading graphics
    this.loadingInterval = setInterval(() => {
      this[`isLoading${count++}`] = false
      if (count === 4) this.clear()
    }, 400)
  },
  beforeDestroy() {
    this.clear()
  },
  methods: {
    clear() {
      clearInterval(this.loadingInterval)
    },
    showFilterLabel() {
      this.filterLabel = false
    }
  }
}
</script>
<style>
.vue-daterange-picker{
  margin: 0;
}
</style>
