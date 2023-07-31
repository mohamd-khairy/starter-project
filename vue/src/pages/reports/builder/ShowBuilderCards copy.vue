<template>
  <v-row v-if="isLoading" class="flex-grow-0 mb-1" dense>
    <v-col cols="12" lg="3" md="6" v-for="(card, key) in cards" :key="key">
      <v-card>
        <v-skeleton-loader
          :min-height="115"
          type="card-heading, list-item"
        ></v-skeleton-loader>
      </v-card>
    </v-col>
  </v-row>
  <v-row v-else class="flex-grow-0 mb-1" dense>
    <v-col cols="12" lg="3" md="6" v-for="(card, key) in cards" :key="key">
      <div class="d-flex flex-column flex-grow-1 h-full">
        <track-card
          :label="$t('general.' + key)"
          class="h-full"
          color="#8c9eff"
          :value="card"
          :outlined="outlined"
        ></track-card>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import CardsSkeleton from "../../../components/common/CardsSkeleton";
import TrackCard from "../../../components/dashboard/TrackCard";
import { mapActions, mapState } from "vuex";

export default {
  name: "ShowBuilderCards",
  props: {
    cards: Object,
    outlined: {
      type: Boolean,
      default: false
    }
  },
  components: {
    TrackCard,
    CardsSkeleton
  },
  data() {
    return {
      isLoading: false
    };
  }
};
</script>

<style scoped></style>
