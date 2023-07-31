<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("reports.buildReport") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

    </div> -->

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <div class="title mb-2">
            {{ $t("reports.showReport") }}
          </div>
          <v-row>
            <v-col cols="12" md="6">
              <v-select
                :items="models"
                :rules="[rules.required]"
                variant="underlined"
                class=""
                :label="$t('reports.model')"
                item-text="value"
                item-value="key"
                v-model="selectedModel"
                outlined
                hide-details
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                :items="report_types"
                variant="underlined"
                :label="$t('reports.reportType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="selectedReport"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12">
              <v-select
                :items="show_types"
                variant="underlined"
                :label="$t('reports.showType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="selectedShow"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedShow === 'specific'">
              <v-select
                :items="locations"
                :rules="[rules.required]"
                variant="underlined"
                :label="$t('reports.specificLocation')"
                item-text="name"
                item-value="id"
                v-model="selectedSpecificLocation"
                dense
                outlined
                hide-details
              ></v-select>
            </v-col>
            <v-col
              cols="12"
              v-if="selectedReport === 'types' && selectedShow === 'comparison'"
            >
              <v-select
                :items="eventTypes"
                variant="underlined"
                :label="$t('reports.typeComparisons')"
                item-text="key"
                item-value="value"
                hide-details
                v-model="selectedComparisonTypes"
                return-object
                multiple
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedShow === 'comparison'">
              <v-select
                :items="locations"
                :rules="[rules.required]"
                variant="underlined"
                :label="$t('reports.locationComparisons')"
                item-text="name"
                item-value="id"
                v-model="selectedComparisonLocation"
                return-object
                multiple
                outlined
                hide-details
                dense
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
                :label="$t('reports.specificType')"
                item-text="key"
                item-value="value"
                hide-details
                v-model="selectedSpecificType"
                return-object
                outlined
                dense
              ></v-select>
            </v-col>

            <v-col cols="12" md="6">
              <v-select
                :items="time_types"
                variant="underlined"
                :label="$t('reports.timeType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="selectedTime"
                return-object
                @change="selectTimeType"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="selectedTime.key === 'dynamic'">
              <v-select
                :items="dates"
                :label="$t('reports.date')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="selectedDate"
                outlined
                dense
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
                    v-on="on"
                    outlined
                    dense
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
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="toDate"
                  @input="menu2 = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <div class="d-flex mt-10">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="showReport()"
            >
              {{ $t("reports.show") }}
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "Builder",
  data() {
    return {
      errors: {},
      errorMessages: "",
      loading: false,
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
      breadcrumbs: [
        {
          text: this.$t("reports.reports"),
          disabled: true
        },
        { text: this.$t("reports.buildReport") }
        //   {
        //   text: this.$t('users.usersList'),
        //   to: '/users/list',
        //   exact: true
        // },
        //   {
        //     text: this.$t('users.createUser')
        //   }
      ],
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
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("reports.buildReport")
    });
  },
  mounted() {
    this.open();
    this.fetchLocations();
    this.fetchTypes();
  },
  methods: {
    ...mapActions("reports", ["getBuilderOptions", "buildReport"]),
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("locations", ["getLocations"]),
    ...mapActions("events", ["getTypes"]),
    open() {
      this.isLoading = true;
      this.getBuilderOptions()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchLocations() {
      this.isLoading = true;
      this.getLocations()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchTypes() {
      this.isLoading = true;
      this.getTypes()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    selectTimeType() {
      if (this.selectedTime.key === "dynamic")
        this.dates = this.selectedTime.dates;
    },
    showReport() {
      this.isLoading = true;
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
      this.buildReport(data)
        .then(response => {
          this.isLoading = false;
          this.$router.push({
            name: "reports-show-builder",
            params: { data: response.data.data, filter: response.data.filter }
          });
        })
        .catch(error => {
          this.isLoading = false;
          if (error?.response?.status == 422) {
            let { message } = error?.response?.data;
            this.errorMessages = message;
          }
        });
    }
  }
};
</script>

<style scoped></style>
