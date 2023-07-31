<template>
  <div class="mb-2">
    <v-row dense>
      <v-col cols="12">
        <v-card class="general-stats-card">
          <v-card-title>
            {{ $t("general.statistics") }}
          </v-card-title>
          <v-card-text>
            <v-row v-if="isLoading">
              <v-col v-for="num in 7" :key="num">
                <v-skeleton-loader type="list-item-avatar"></v-skeleton-loader>
              </v-col>
            </v-row>
            <v-row v-else>
              <v-col
                cols="6"
                md="2"
                lg="2"
                v-for="(card, key) in cards"
                :key="key"
              >
                <div class="d-flex " :id="key">
                  <v-avatar
                    size="42"
                    class="me-3 v-avatar--variant-tonal "
                    :class="getColor(key)"
                    variant="tonal"
                  >
                    <component
                      :is="key"
                      v-if="isComponentRegistered(key)"
                    ></component>
                  </v-avatar>
                  <div class="d-flex flex-column">
                    <span class="text-h5 font-weight-medium">{{ card }}</span>
                    <span class=" text-caption">
                      {{ $t("general." + key) }}
                    </span>
                  </div>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import moment from "moment";
import total from "@/assets/images/statsIcons/Total.svg";
import smoke from "@/assets/images/statsIcons/Smoke.svg";
import underProcess from "@/assets/images/statsIcons/UnderProcess.svg";
import fire from "@/assets/images/statsIcons/Fire.svg";
import people from "@/assets/images/statsIcons/People.svg";
import vehicles from "@/assets/images/statsIcons/Vehicles.svg";
import leakage from "@/assets/images/statsIcons/Leakage.svg";
import change from "@/assets/images/statsIcons/Change.svg";

export default {
  components: {
    total,
    smoke,
    underProcess,
    fire,
    people,
    vehicles,
    leakage,
    change
  },
  data() {
    return {
      isLoading: false
    };
  },
  created() {},
  mounted() {
    this.cardsData();
  },

  methods: {
    ...mapActions("events", ["getCards"]),
    isComponentRegistered(key) {
      return !!this.$options.components[key];
    },
    getColor(type) {
      const colors = {
        smoke: "grey--text text--darken-1",
        total: "primary--text",
        fire: "red--text",
        people: "indigo--text",
        vehicles: "cyan--text",
        leakage: "orange--text",
        change: "yellow--text text--darken-1"
      };
      return type in colors ? colors[type] : "primary--text";
    },
    cardsData(dateRange = {}) {
      this.isLoading = true;
      const { id } = this.$route.params;
      let data = {
        locationId: id,
        startDate: dateRange.startDate
          ? moment(dateRange.startDate).format("YYYY-MM-DD")
          : "",
        endDate: dateRange.endDate
          ? moment(dateRange.endDate).format("YYYY-MM-DD")
          : ""
      };
      this.getCards(data)
        .then(() => {
          this.isLoading = false;

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
          this.isLoading = false;
        });
    }
  },
  computed: {
    ...mapState("events", ["cards"])
  }
};
</script>

<style>
.general-stats-card .text-h6 {
  font-size: 1.25rem !important;
  line-height: 1.5rem;
  color: #4d4b55;
}

.v-application .text-caption {
  font-family: "Cairo", sans-serif !important;
}
</style>
