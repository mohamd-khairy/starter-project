<template>
    <v-row class="flex-grow-0 mb-1" dense>
      <v-col cols="12" lg="4" md="6" v-for="(item , key) in statisticsData" :key="key">
        <div class="d-flex flex-column flex-grow-1 h-full">
          <track-card
            :label="$t('general.'+ key)"
            class="h-full"
            color="#8c9eff"
            :value="item"
          ></track-card>
        </div>
      </v-col>
    </v-row>
</template>

<script>
import CardsSkeleton from '../../../components/common/CardsSkeleton'
import TrackCard from '../../../components/dashboard/TrackCard'

export default {
  name: "statistics",
  components:{
    TrackCard,CardsSkeleton
  },
  props:{
    statisticsData: Array,
  },
  data(){
    return {
      isLoading : false,
    }
  },
}
</script>

<style scoped>

</style>
