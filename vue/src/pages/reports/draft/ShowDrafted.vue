<template>
  <div class="d-flex flex-grow-1 flex-column">
    <!-- <div class="d-flex align-center py-3">
      <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t("reports.showReport") }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
    </div> -->

    <v-card class="model-card mb-4">
      <v-card-text class="pb-0">
        <div class="card-header custom-header border-bottom">
          <v-list-item-avatar tile size="60" color="grey"></v-list-item-avatar>

          <div class="content-cont">
            <div class="d-flex justify-space-between ">
              <h3 class="c-title">
                {{ $t("reports.detectionReport") }}
              </h3>
            </div>
          </div>
        </div>
      </v-card-text>
      <div class="card-body multi-content">
        <div class="item-desc">
          <h5 class="i-title">{{ $t("reports.fromDate") }}</h5>
          <p class="num">{{ filterData.start }}</p>
        </div>
        <div class="item-desc">
          <h5 class="i-title">{{ $t("reports.toDate") }}</h5>
          <p class="num">{{ filterData.end }}</p>
        </div>
        <div class="item-desc">
          <h5 class="i-title">{{ $t("reports.timeType") }}</h5>
          <p class="num">{{ filterData.time_type }}</p>
        </div>
        <div class="item-desc">
          <h5 class="i-title">{{ $t("reports.menuType") }}</h5>
          <p class="num">{{ filterData.groupBy }}</p>
        </div>
      </div>
    </v-card>

    <v-row class="flex-grow-0 mb-2 draft-cont" dense>
      <v-col cols="12 mb-2">
        <v-card>
          <v-card-title class="d-flex justify-space-between">
            <div class="d-flex justify-between">
              {{ $t("general.moreCards") }}
            </div>
            <div class="action-cont">
              <v-btn
                small
                outlined
                color="primary"
                @click="addToPin(draftDetails.card)"
              >
                <v-icon>
                  mdi-plus
                </v-icon>
                {{ $t("reports.addToPinned") }}
              </v-btn>
            </div>
          </v-card-title>
          <hr class="mb-0" />
          <v-card-text> </v-card-text>
        </v-card>
      </v-col>
      <v-col class="mb-2" v-if="!loading && draftDetails.bar" cols="12" lg="6">
        <v-card>
          <div
            v-if="isLoading"
            class="d-flex flex-grow-1 align-center justify-center"
          >
            <v-progress-circular
              indeterminate
              color="primary"
            ></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title class="d-flex justify-space-between">
              <div class="d-flex justify-between">
                {{ draftDetails.bar.title }}
              </div>
              <div class="action-cont">
                <v-btn
                  small
                  outlined
                  color="primary"
                  @click="addToPin(draftDetails.bar)"
                >
                  <v-icon>
                    mdi-plus
                  </v-icon>
                  {{ $t("reports.addToPinned") }}
                </v-btn>
              </div>
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <column
                :labels="draftData.bar.sites"
                :series="draftData.bar.result"
              ></column>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col class="mb-2" v-if="!loading && draftDetails.line" cols="12" lg="6">
        <v-card>
          <div
            v-if="isLoading"
            class="d-flex flex-grow-1 align-center justify-center"
          >
            <v-progress-circular
              indeterminate
              color="primary"
            ></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title class="d-flex justify-space-between">
              <div class="d-flex">
                {{ draftDetails.line.title }}
              </div>
              <div class="action-cont">
                <v-btn
                  small
                  outlined
                  color="primary"
                  @click="addToPin(draftDetails.line)"
                >
                  <v-icon>
                    mdi-plus
                  </v-icon>
                  {{ $t("reports.addToPinned") }}
                </v-btn>
              </div>
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <line-chart
                :labels="draftData.line.sites"
                :series="draftData.line.result"
              ></line-chart>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col
        v-if="!loading && draftDetails.pie"
        cols="12"
        lg="6"
        v-for="(pie, key) in draftData.pie"
        :key="key"
        class="mb-2"
      >
        <v-card>
          <div
            v-if="isLoading"
            class="d-flex flex-grow-1 align-center justify-center"
          >
            <v-progress-circular
              indeterminate
              color="primary"
            ></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title class="d-flex justify-space-between">
              <div class="d-flex">
                {{ draftDetails.pie.title }} {{ $t("general." + key) }}
              </div>
              <div class="action-cont">
                <v-btn
                  small
                  outlined
                  color="primary"
                  @click="addToPin(draftDetails.pie)"
                >
                  <v-icon>
                    mdi-plus
                  </v-icon>
                  {{ $t("reports.addToPinned") }}
                </v-btn>
              </div>
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <pie :labels="pie.name" :series="pie.value"></pie>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col class="mb-2" v-if="!loading && draftData.table" cols="12" lg="6">
        <v-card>
          <div
            v-if="isLoading"
            class="d-flex flex-grow-1 align-center justify-center"
          >
            <v-progress-circular
              indeterminate
              color="primary"
            ></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title class="d-flex justify-space-between">
              {{ draftDetails.table.title }}
              <div class="action-cont">
                <v-btn
                  small
                  outlined
                  color="primary"
                  @click="addToPin(draftDetails.table)"
                >
                  <v-icon>
                    mdi-plus
                  </v-icon>
                  {{ $t("reports.addToPinned") }}
                </v-btn>
              </div>
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <table-card :table="draftData.table" class="h-full" />
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-dialog v-model="pinDialog" max-width="600">
      <v-card>
        <v-card-title
          class="text-h5 d-flex justify-space-between align-center border-bottom"
        >
          {{ $t("reports.chooseThePinnedReportToAdd") }}
          <v-btn icon @click="pinDialog = false">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text v-if="openDialogLoader" class="mt-4">
          <div class="text-center">
            <v-progress-circular
              :size="50"
              color="primary"
              indeterminate
            ></v-progress-circular>
          </div>
        </v-card-text>
        <v-card-text v-else class="mt-4">
          <v-row>
            <v-col cols="12">
              <v-select
                hide-details="auto"
                v-model="selectedPinnedReports"
                :items="pinned"
                item-text="title"
                item-value="id"
                chips
                :label="$t('reports.chhoseFromSavedPinnedReports')"
                multiple
                outlined
              ></v-select>
              <div
                v-show="validationError && selectedPinnedReports.length === 0"
                class="mt-1 red--text"
              >
                {{ $t("general.selectAtLeastOnePineed") }}
              </div>
            </v-col>

            <v-col cols="12">
              <div
                class="d-flex mb-2"
                v-for="(pin, index) in pins"
                :key="index"
              >
                <v-text-field
                  dense
                  outlined
                  :label="$t('reports.addNewTitle')"
                  hide-details="auto"
                  :rules="requiredRule"
                  v-model="pin.text"
                ></v-text-field>
                <v-btn
                  @click="removePin(index)"
                  color="error"
                  class="mr-1"
                  elevation="0"
                >
                  <v-icon>
                    mdi-close
                  </v-icon>
                </v-btn>
              </div>
            </v-col>
            <v-col cols="12">
              <v-btn color="primary" @click="addNewPin">
                <v-icon>
                  mdi-plus
                </v-icon>
                {{ $t("reports.newPin") }}
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>

        <v-divider></v-divider>
        <v-card-actions class="py-2">
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            class="mx-1"
            elevation="0"
            :disabled="pinLoading"
            :loading="pinLoading"
            @click="storePinned"
          >
            {{ $t("general.save") }}
          </v-btn>
          <v-btn
            color="grey lighten-3"
            elevation="0"
            @click="pinDialog = false"
          >
            {{ $t("general.cancel") }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

import column from "../../../components/dashboard/column";
import lineChart from "../../../components/dashboard/lineChart";
import pie from "../../../components/dashboard/pie";
import tableCard from "../../../components/dashboard/TableCard";
import showBuilderCards from "@/pages/reports/builder/ShowBuilderCards";

export default {
  components: {
    column,
    lineChart,
    pie,
    tableCard,
    showBuilderCards
  },
  created() {
    const { id } = this.$route.params;
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("reports.showReport")
    });
    if (!id) {
      window.location.replace("/reports/drafted");
    }
    this.fetchConfig();
    this.fetchDrafted(id);
  },
  data() {
    return {
      loading: true,
      isLoading: false,
      pinLoading: false,
      openDialogLoader: false,
      pinDialog: false,
      selectedPinnedReports: [],
      selectedChartId: null,
      pins: [],
      requiredRule: [v => !!v || this.$t("general.fieldRequired")],
      validationError: false,
      breadcrumbs: [
        { text: this.$t("menu.reports"), disabled: true },
        {
          text: this.$t("reports.draftedReports"),
          href: "/reports/drafted"
        },
        { text: this.$t("reports.showReport") }
      ]
    };
  },
  computed: {
    ...mapState("reports", {
      showDraft: state => state.showDraft,
      draftData: state => (state.showDraft.data ? state.showDraft.data : {}),
      draftDetails: state =>
        state.showDraft.details ? state.showDraft.details : {},
      filterData: state =>
        state.showDraft.filter ? state.showDraft.filter : {},
      config: state => state.config,
      pinned: state => state.pinnedReports
    })
  },
  methods: {
    ...mapActions("reports", [
      "getDraft",
      "builderConfigs",
      "getSavedPinned",
      "savePins",
      "getRelatedPinned"
    ]),
    ...mapActions("app", ["setBreadCrumb"]),
    fetchConfig() {
      this.isLoading = true;
      // this.getSavedPinned();
      this.builderConfigs()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchDrafted(id) {
      this.loading = true;
      this.getDraft(id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    addToPin(type) {
      let { id } = type;
      if (!id) return;
      this.pins = [];
      this.selectedPinnedReports = [];
      this.selectedChartId = id;
      this.openDialogLoader = true;
      (this.validationError = false), (this.pinDialog = true);
      this.getRelatedPinned(id).then(response => {
        const { chart_pinneds } = response?.data.data;
        this.selectedPinnedReports = [...chart_pinneds];
        this.openDialogLoader = false;
      });
    },
    addNewPin() {
      this.pins.push({ text: "" });
    },
    removePin(index) {
      this.pins.splice(index, 1);
    },
    storePinned() {
      let valid = true;
      this.pins.forEach(pin => {
        if (!pin.text) {
          valid = false;
        }
      });
      if (!valid) return;

      let titles = this.pins.map(pin => pin.text);

      if (!titles.length && !this.selectedPinnedReports.length) {
        // show error message
        this.validationError = true;
        return;
      }
      this.pinLoading = true;
      this.savePins({
        titles,
        pinned_ids: this.selectedPinnedReports,
        chart_id: this.selectedChartId
      })
        .then(_ => {
          this.pinLoading = false;
          this.pinDialog = false;
        })
        .catch(error => {
          console.log(error);
          this.pinLoading = true;
        });
    }
  }
};
</script>

<style>
.draft-cont .v-card {
  height: 100%;
}
</style>
