<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('stations.stationsSettings') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div> -->

    <v-row>
      <v-col cols="12" lg="4">
        <v-card>
          <v-card-title>{{ $t('stations.stationsDetails') }}</v-card-title>
          <v-card-text>
            <div class="list-group">
              <div class="list d-flex align-center justify-space-between mb-2">
                <div class="d-flex align-center">
                  <v-icon small color="purple">mdi-circle-medium</v-icon>
                  <div class="v-list-item__title mx-1">{{ $t('stations.totalStations') }}</div>
                </div>
                <span class="badge badge-light-primary font-weight-bold">{{ locations.length }}</span>
              </div>
              <div class="list d-flex align-center justify-space-between mb-2">
                <div class="d-flex align-center">
                  <v-icon small color="success">mdi-circle-medium</v-icon>
                  <div class="v-list-item__title  mx-1">{{ $t('stations.activeStations') }}</div>
                </div>
                <span class="badge badge-light-primary font-weight-bold">12</span>
              </div>
<!--              <div class="list d-flex align-center justify-space-between mb-2">-->
<!--                <div class="d-flex align-center">-->
<!--                  <v-icon small color="red">mdi-circle-medium</v-icon>-->
<!--                  <div class="v-list-item__title  mx-1">{{ $t('stations.activeLinks') }}</div>-->
<!--                </div>-->
<!--                <span class="badge badge-light-primary font-weight-bold">3</span>-->
<!--              </div>-->
              <div class="list d-flex align-center justify-space-between mb-2">
                <div class="d-flex align-center">
                  <v-icon small color="warning">mdi-circle-medium</v-icon>
                  <div class="v-list-item__title  mx-1">{{ $t('stations.createdAt') }}</div>
                </div>
                <span class="badge badge-light-primary font-weight-bold">{{ created | formatDate("lll")}}</span>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" lg="8">
        <v-card>
          <v-card-title>{{ $t('general.stations') }}</v-card-title>
          <v-card-text>
            <v-timeline
              align-center
              dense
            >
              <v-timeline-item
                color="teal lighten-3"
                small
                v-for="location in locations" :key="location.id"
              >
                <v-btn text
                       class="text--secondary text-link px-0"
                       :to="{ name: 'settings-stations-edit', params: { id: location.id } }" v-can="'update-location'">
                  <strong>{{ location.name }} </strong>
                </v-btn>
              </v-timeline-item>
            </v-timeline>

          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>

import {mapActions, mapState} from "vuex";
import {ask} from "@/helpers";

export default {
  components: {
  },
  data() {
    return {
      isLoading: false,
      created: '',
      breadcrumbs: [{
        text: this.$t('menu.settings'),
        disabled: false,
        href: '#'
      }, {
        text: this.$t('menu.stations')
      }]
    }
  },
  computed: {
    ...mapState('locations', ['locations'])
  },
  mounted() {
    this.open()
  },
  created(){
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t('stations.stationsSettings')
    });
  },
  methods: {
    ...mapActions('locations', ['getLocations']),
    ...mapActions("app", ["setBreadCrumb"]),
    open() {
      this.isLoading = true;
      this.getLocations()
        .then(() => {
          this.isLoading = false;
          this.created = this.locations[0].created_at
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
  }
}
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
