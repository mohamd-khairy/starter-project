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

    <v-card class="model-card mb-2">
      <v-card-text class="pb-0">
        <div class="card-header custom-header border-bottom">
          <v-list-item-avatar tile size="60" color="grey"></v-list-item-avatar>

          <div class="content-cont">
            <div class="d-flex justify-space-between ">
              <h3 class="c-title">
                {{ $t("reports.detectionReport") }}
              </h3>
              <div class="action-cont">
                <v-btn-toggle dense v-model="filterType">
                  <v-btn value="15">
                    <span>{{ $t("general.lastMonth") }}</span>
                  </v-btn>

                  <v-btn value="16">
                    <span>{{ $t("general.thisMonth") }}</span>
                  </v-btn>

                  <v-btn value="13">
                    <span>{{ $t("general.lastWeek") }}</span>
                  </v-btn>

                  <v-btn value="14">
                    <span>{{ $t("general.thisWeek") }}</span>
                  </v-btn>

                  <v-btn value="today">
                    <span>{{ $t("general.today") }}</span>
                  </v-btn>
                </v-btn-toggle>

                <v-tooltip top>
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="grey lighten-3"
                      class="mx-2 "
                      elevation="0"
                      v-bind="attrs"
                      v-on="on"
                      @click.stop="draftDialog = true"
                    >
                      <v-icon>
                        mdi-pin
                      </v-icon>
                    </v-btn>
                  </template>
                  <span>{{ $t("reports.draftThisReport") }}</span>
                </v-tooltip>

                <v-btn
                  elevation="0"
                  color="grey lighten-3"
                  @click="reportFilterDrawer = !reportFilterDrawer"
                >
                  <v-icon>
                    mdi-filter
                  </v-icon>
                </v-btn>
              </div>
            </div>

            <!--            <div>-->
            <!--              <strong style="font-size: 14px" class="text-dark-90">-->
            <!--                تقرير محدد ل </strong-->
            <!--              >&nbsp; &nbsp;-->

            <!--              <span style="font-size: 14px">الهيئة العامة للتطوير الدفاعي</span>-->
            <!--            </div>-->
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

    <show-builder-cards
      v-if="builderData.cards != {}"
      :cards="builderData.cards"
    ></show-builder-cards>

    <v-row class="flex-grow-0 pb-2" dense>
      <v-col v-if="builderData.bar != {}" cols="12" lg="6">
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
                {{ config.bar.title }}
                <div class="modal-action" @click.stop="dialog = true">
                  <v-btn
                    icon
                    small
                    class="mx-1"
                    @click.prevent="editChart(config.bar)"
                  >
                    <v-icon>mdi-square-edit-outline</v-icon>
                  </v-btn>
                </div>
              </div>
              <!--              <v-btn-toggle dense v-model="barFilterType">-->
              <!--                  <v-btn value="year">-->
              <!--                    <span>{{ $t("general.thisYear") }}</span>-->
              <!--                  </v-btn>-->

              <!--                  <v-btn value="month">-->
              <!--                    <span>{{ $t("general.thisMonth") }}</span>-->
              <!--                  </v-btn>-->

              <!--                  <v-btn value="week">-->
              <!--                    <span>{{ $t("general.thisWeek") }}</span>-->
              <!--                  </v-btn>-->

              <!--                  <v-btn value="today">-->
              <!--                    <span>{{ $t("general.today") }}</span>-->
              <!--                  </v-btn>-->
              <!--                </v-btn-toggle>-->
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <column
                :labels="builderData.bar.sites"
                :series="builderData.bar.result"
              ></column>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col v-if="builderData.line != {}" cols="12" lg="6">
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
                {{ config.line.title }}
                <div class="modal-action" @click.stop="dialog = true">
                  <v-btn
                    icon
                    small
                    class="mx-1"
                    @click.prevent="editChart(config.line)"
                  >
                    <v-icon>mdi-square-edit-outline</v-icon>
                  </v-btn>
                </div>
              </div>
              <!--              <v-btn-toggle dense v-model="lineFilterType">-->
              <!--                  <v-btn value="year">-->
              <!--                    <span>{{ $t("general.thisYear") }}</span>-->
              <!--                  </v-btn>-->

              <!--                  <v-btn value="month">-->
              <!--                    <span>{{ $t("general.thisMonth") }}</span>-->
              <!--                  </v-btn>-->

              <!--                  <v-btn value="week">-->
              <!--                    <span>{{ $t("general.thisWeek") }}</span>-->
              <!--                  </v-btn>-->

              <!--                  <v-btn value="today">-->
              <!--                    <span>{{ $t("general.today") }}</span>-->
              <!--                  </v-btn>-->
              <!--                </v-btn-toggle>-->
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <line-chart
                :labels="builderData.line.sites"
                :series="builderData.line.result"
              ></line-chart>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col
        v-if="builderData.pie"
        cols="12"
        lg="6"
        v-for="(pie, key) in builderData.pie"
        :key="key"
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
                {{ config.pie.title }} {{ $t("general." + key) }}
                <div class="modal-action" @click.stop="dialog = true">
                  <v-btn
                    icon
                    small
                    class="mx-1"
                    @click.prevent="editChart(config.pie)"
                  >
                    <v-icon>mdi-square-edit-outline</v-icon>
                  </v-btn>
                </div>
              </div>
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <pie :labels="pie.name" :series="pie.value"></pie>
            </div>
          </div>
        </v-card>
      </v-col>

      <v-col v-if="builderData.table" cols="12" lg="6">
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
            <v-card-title>
              {{ config.table.title }}
              <div class="modal-action" @click.stop="dialog = true">
                <v-btn
                  icon
                  small
                  class="mx-1"
                  @click.prevent="editChart(config.table)"
                >
                  <v-icon>mdi-square-edit-outline</v-icon>
                </v-btn>
              </div>
            </v-card-title>
            <hr />
            <div class="d-flex flex-column flex-grow-1">
              <table-card :table="builderData.table" class="h-full" />
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <edit-chart v-model="dialog" :id="chartId" :chart="chartObj"></edit-chart>
    <filter-report-drawer
      :reportFilterDrawer="reportFilterDrawer"
      :isLoading="reportFilterLoading"
      @filterReport="filterReport"
      @update:filterDrawer="reportFilterDrawer = $event"
    ></filter-report-drawer>

    <v-row justify="center">
      <v-dialog v-model="draftDialog" max-width="500">
        <v-card>
          <v-card-title
            class="text-h5 d-flex justify-space-between align-center border-bottom"
          >
            {{ $t("reports.draftThisReport") }}
            <v-btn icon @click="draftDialog = false">
              <v-icon>
                mdi-close
              </v-icon>
            </v-btn>
          </v-card-title>
          <v-card-text class="mt-4">
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="title"
                  :label="$t('general.title')"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions class="py-2">
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              class="mx-2"
              elevation="0"
              :disabled="draftLoading"
              :loading="draftLoading"
              @click="storeDraft"
            >
              {{ $t("general.save") }}
            </v-btn>
            <v-btn
              color="grey lighten-3"
              elevation="0"
              @click="draftDialog = false"
            >
              {{ $t("general.cancel") }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>
  </div>
</template>

<script>
// DEMO Cards for dashboard
import bar from "../../../components/dashboard/bar";
import pie from "../../../components/dashboard/pie";
import pie2 from "../../../components/dashboard/pie2";
import Column from "../../../components/dashboard/column";
import lineChart from "../../../components/dashboard/lineChart";
// import radar from '../../components/dashboard/radar'
// import spline from '../../components/dashboard/spline'
import TrackCard from "../../../components/dashboard/TrackCard";
import TableCard from "../../../components/dashboard/TableCard";

import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
import ShowBuilderCards from "@/pages/reports/builder/ShowBuilderCards";
import { mapActions, mapState } from "vuex";
import EditChart from "@/pages/reports/builder/EditChart";
import { makeToast } from "@/helpers";
import FilterReportDrawer from "../../../components/reports/FilterReportDrawer.vue";

export default {
  name: "ShowBuilder",
  props: {
    data: Object,
    filter: Object
  },
  components: {
    EditChart,
    lineChart,
    ShowBuilderCards,
    Column,
    TrackCard,
    TableCard,
    bar,
    pie,
    pie2,
    DateRangePicker,
    FilterReportDrawer
    // radar
    // spline
  },
  filters: {},
  data() {
    return {
      filterType:
        this.filter.time_type === "specific" ? "" : this.filter.time_range,
      barFilterType: "",
      lineFilterType: "",
      builderData: this.data,
      filterData: this.filter,
      loadingInterval: null,
      loading: true,
      isLoading: false,
      dialog: false,
      draftDialog: false,
      draftLoading: false,
      title: "",
      chartObj: {},
      chartId: 0,
      breadcrumbs: [
        { text: this.$t("reports.reports"), disabled: true },
        { text: this.$t("reports.showReport") }
      ],
      reportFilterDrawer: false,
      reportFilterLoading: false
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("reports.showReport")
    });
    this.fetchConfig();

    if (typeof this.data === "undefined" && typeof this.filter === "undefined")
      window.location.replace("/reports/builder");
    // this.$router.push({name: "reports-builder"});

    this.$root.$on("chart_component", () => {
      this.fetchConfig();
    });
  },
  computed: {
    ...mapState("reports", ["config"])
  },
  watch: {
    filterType() {
      this.showReport();
    }
  },
  mounted() {
    this.loading = false;
    let count = 0;
    // DEMO delay for loading graphics
    this.loadingInterval = setInterval(() => {
      this[`isLoading${count++}`] = false;
      if (count === 4) this.clear();
    }, 400);
  },
  beforeDestroy() {
    this.clear();
  },
  methods: {
    ...mapActions("reports", ["builderConfigs", "buildReport", "saveDraft"]),
    ...mapActions("app", ["setBreadCrumb"]),
    fetchConfig() {
      this.isLoading = true;
      this.builderConfigs()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    storeDraft() {
      this.draftLoading = true;
      let data = {
        report_list: this.filterData.report_list,
        time_type: this.filterData.time_type,
        time_range: this.filterType,
        model_type: this.filterData.model_type,
        type: this.filterData.type,
        start: this.filterData.start,
        end: this.filterData.end,
        unit: this.filterData.columns.unit,
        columns: this.filterData.columns.data,
        chart_types: this.filterData.chart_type,
        site_ids: this.filterData.site_ids,
        title: this.title
      };
      this.saveDraft(data)
        .then(response => {
          this.draftDialog = false;
          this.draftLoading = false;
          this.title = "";
          makeToast("success", response.data.data.message);
        })
        .catch(error => {
          this.draftLoading = true;
          if (error?.response?.status == 422) {
            let { message } = error?.response?.data;
            this.errorMessages = message;
          }
        });
    },
    showReport() {
      this.isLoading = true;
      let data = {};
      data = {
        report_list: this.filterData.report_list,
        model_type: this.filterData.model_type,
        time_type: this.filterData.time_type,
        site_ids: this.filterData.site_ids,
        type: this.filterData.type,
        types: this.filterData.types,
        time_range: this.filterType
      };
      this.buildReport(data)
        .then(response => {
          this.isLoading = false;
          this.builderData = response.data.data;
          this.filterData = response.data.filter;
          // this.data = response.data.data
          // this.filter = response.data.filter
        })
        .catch(error => {
          this.isLoading = false;
          if (error?.response?.status == 422) {
            let { message } = error?.response?.data;
            this.errorMessages = message;
          }
        });
    },
    clear() {
      clearInterval(this.loadingInterval);
    },
    editChart(chart) {
      this.dialog = true;
      this.chartObj = chart;
      this.chartId = chart.id;
    },
    filterReport(data) {
      this.reportFilterLoading = true;
      this.buildReport(data)
        .then(response => {
          this.isLoading = false;
          this.builderData = response.data.data;
          this.filterData = response.data.filter;
          this.filterType =
            response.data.filter.time_type === "specific"
              ? ""
              : response.data.filter.time_range;
          this.reportFilterDrawer = false;
          this.reportFilterLoading = false;
        })
        .catch(error => {
          this.isLoading = false;
          this.reportFilterLoading = false;
          if (error?.response?.status == 422) {
            let { message } = error?.response?.data;
            this.errorMessages = message;
          }
        });
    }
  }
};
</script>
<style></style>
<style scoped>
.vue-daterange-picker {
  margin: 0;
}

hr {
  border: 0;
  height: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  /* margin-bottom: 20px; */
}
.v-card {
  height: 100%;
}
</style>
