<template>
  <!-- Filter Drawer-->
  <div
    id="all_captured_images"
    class="model-aside filter-report-drawer"
    :class="{ open: reportFilterDrawer }"
  >
    <v-card>
      <v-card-title
        class="d-flex justify-space-between align-center border-bottom"
      >
        {{ $t("general.filter") }}
        <v-btn color="primary" icon @click="closeReportFilterDrawer">
          <v-icon>
            mdi-close
          </v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text class="pa-2 roles">
        <v-form>
          <v-row>
            <v-col cols="12" md="6">
              <v-select
                :items="models"
                :rules="[rules.required]"
                class=" mx-1"
                :label="$t('reports.model')"
                item-text="value"
                item-value="key"
                v-model="selectedModel"
                outlined
                dense
                hide-details
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-select

                :items="report_types"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.reportType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="selectedReport"
                 outlined
                dense

              ></v-select>
            </v-col>
            <v-col cols="12" >
              <v-select

                :items="show_types"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.showType')"
                item-text="value"
                item-value="key"
                 outlined
                dense
                hide-details
                v-model="selectedShow"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedShow === 'specific'">
              <v-select

                :items="locations"
                :rules="[rules.required]"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.specificLocation')"
                item-text="name"
                item-value="id"
                 outlined
                dense
                hide-details
                v-model="selectedSpecificLocation"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedShow === 'comparison'">
              <v-select

                :items="locations"
                :rules="[rules.required]"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.locationComparisons')"
                item-text="name"
                item-value="id"
                v-model="selectedComparisonLocation"
                return-object
                multiple
                 outlined
                dense
                hide-details
              ></v-select>
            </v-col>
            <v-col
              cols="12"
              md="6"
              v-if="selectedReport === 'types' && selectedShow === 'specific'"
            >
              <v-select

                :items="eventTypes"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.specificType')"
                item-text="key"
                item-value="value"
                 outlined
                dense
                hide-details
                v-model="selectedSpecificType"
                return-object
              ></v-select>
            </v-col>
            <v-col
              cols="12"
              md="6"
              v-if="selectedReport === 'types' && selectedShow === 'comparison'"
            >
              <v-selectlabel="Select"
                :items="eventTypes"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.typeComparisons')"
                item-text="key"
                item-value="value"
                 outlined
                dense
                hide-details
                v-model="selectedComparisonTypes"
                return-object
                multiple
              ></v-selectlabel=>
            </v-col>
            <v-col cols="12" md="6">
              <v-select

                :items="time_types"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.timeType')"
                item-text="value"
                item-value="key"
                 outlined
                dense
                hide-details
                v-model="selectedTime"
                return-object
                @change="selectTimeType"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedTime.key === 'dynamic'">
              <v-select

                :items="dates"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.date')"
                item-text="value"
                item-value="key"
                 outlined
                dense
                hide-details
                v-model="selectedDate"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedTime.key === 'specific'">
              <v-menu
                v-model="menu"
                :close-on-content-click="false"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="fromDate"
                    :label="$t('reports.selectFromDate')"
                    prepend-icon="mdi mdi-calendar"
                    readonly
                     outlined
                dense
                hide-details
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="fromDate"
                  @input="menu = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedTime.key === 'specific'">
              <v-menu
                v-model="menu2"
                :close-on-content-click="false"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="toDate"
                    :label="$t('reports.selectToDate')"
                    prepend-icon="mdi mdi-calendar"
                    readonly
                    v-on="on"
                     outlined
                dense
                hide-details
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="toDate"
                  @input="menu2 = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <div class="card-footer">
        <div class="d-flex justify-end pa-2">
          <v-btn
            :loading="isLoading"
            :disabled="isLoading"
            color="primary"
            @click="filterReport()"
          >
            {{ $t("general.filter") }}
          </v-btn>
        </div>
      </div>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  props: {
    reportFilterDrawer: {
      type: Boolean,
      default: false
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      errors: {},
      errorMessages: "",
      loading: this.isLoading,
      menu: false,
      menu2: false,
      selectedModel: "",
      selectedReport: "",
      selectedShow: "",
      selectedTime: {},
      selectedSpecificLocation: "",
      selectedComparisonLocation: [],
      selectedSpecificType: {},
      selectedComparisonTypes: [],
      selectedDate: "",
      fromDate: "",
      toDate: "",
      dates: [],

      rules: {
        required: value => (value && Boolean(value)) || "Required"
      }
    };
  },
  watch: {},
  computed: {
    ...mapState("reports", [
      "models",
      "report_types",
      "show_types",
      "time_types",
      "chartData",
      "chartFilter"
    ]),
    ...mapState("locations", ["locations"]),
    ...mapState("events", ["eventTypes"])
  },
  mounted() {
    this.open();
    this.fetchLocations();
    this.fetchTypes();
  },
  methods: {
    ...mapActions("reports", ["getBuilderOptions", "buildReport"]),
    ...mapActions("locations", ["getLocations"]),
    ...mapActions("events", ["getTypes"]),
    closeReportFilterDrawer() {
      this.$emit("update:filterDrawer", false);
    },
    open() {
      this.loading = true;
      this.getBuilderOptions()
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    fetchLocations() {
      this.loading = true;
      this.getLocations()
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    fetchTypes() {
      this.loading = true;
      this.getTypes()
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    selectTimeType() {
      if (this.selectedTime.key === "dynamic")
        this.dates = this.selectedTime.dates;
    },
    filterReport() {
      this.loading = true;
      let data = {};
      let siteIds = [];
      let types = [];
      if (this.selectedComparisonLocation) {
        this.selectedComparisonLocation.forEach(location => {
          siteIds.push(location.id);
        });
      }
      if (this.selectedSpecificLocation)
        siteIds.push(this.selectedSpecificLocation);

      if (this.selectedComparisonTypes) {
        this.selectedComparisonTypes.forEach(type => {
          types.push(type.key);
        });
      }
      if (JSON.stringify(this.selectedSpecificType) != "{}") {
        types.push(this.selectedSpecificType.key);
      }

      data = {
        report_list: this.selectedReport,
        model_type: this.selectedModel,
        time_type: this.selectedTime.key,
        site_ids: siteIds,
        type: this.selectedShow,
        types: types,
        time_range: this.selectedDate,
        start: this.fromDate,
        end: this.toDate
      };
      this.$emit("filterReport", data);
    }
  }
};
</script>

<style></style>
