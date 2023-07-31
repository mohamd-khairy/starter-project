<template>
  <v-row class="flex-grow-0 mb-1 models-cards" dense>
    <v-col cols="12" lg="2" md="6" v-for="(card , key) in cards" :key="key">
      <div class="d-flex flex-column flex-grow-1 h-full" :id="key">
        <track-card
          :label="$t('general.'+ key)"
          class="h-full"
          color="#8c9eff"
          :value="card"
        ></track-card>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import CardsSkeleton from '../../../components/common/CardsSkeleton'
import TrackCard from '../../../components/dashboard/TrackCard'
import {mapActions, mapState} from "vuex";
import moment from "moment";

export default {
  name: "statistics",
  components: {
    TrackCard,
    CardsSkeleton
  },
  computed: {
    ...mapState('events', ['cards']),
  },
  data(){
    return {
      isLoading : false,
    }
  },
  mounted() {
    this.cardsData()
  },
  methods: {
    ...mapActions('events', ['getCards']),
    cardsData(dateRange = {}) {
      this.isLoading = true
      const { id } = this.$route.params;
      let data = {
        locationId: id,
        startDate:dateRange.startDate ? moment(dateRange.startDate).format('YYYY-MM-DD') : '',
        endDate: dateRange.endDate ? moment(dateRange.endDate).format('YYYY-MM-DD') : ''
      }
      this.getCards(data)
        .then(() => {
          this.isLoading = false

          // if(itemsPerPage != -1) {
          //   this.items = this.events.data
          //   this.total = this.events.total
          //   this.numberOfPages = this.events.last_page
          // }else{
          //   this.items = this.events
          //   this.total = this.events.length
          //   this.numberOfPages = 1
          // }
        })
        .catch(() => {
          this.isLoading = false
        })
    },
  },
}
</script>

<style scoped>

</style>
