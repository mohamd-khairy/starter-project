<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("reports.editDraftReport") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

    </div> -->

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <div class="title mb-2">
            {{ $t("reports.editDraftReport") }}
          </div>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.title"
                :label="$t('reports.title')"
                :rules="[rules.required]"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                :items="models"
                :rules="[rules.required]"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.model')"
                item-text="value"
                item-value="key"
                v-model="form.model_type"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                label="Select"
                :items="report_types"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.reportType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="form.report_list"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                label="Select"
                :items="show_types"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.showType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="form.report_type"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="form.report_type === 'specific'">
              <v-select
                label="Select"
                :items="locations"
                :rules="[rules.required]"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.specificLocation')"
                item-text="name"
                item-value="id"
                v-model="selectedSpecificLocation"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="form.report_type === 'comparison'">
              <v-select
                label="Select"
                :items="locations"
                :rules="[rules.required]"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.locationComparisons')"
                item-text="name"
                item-value="id"
                v-model="form.sites"
                return-object
                multiple
              ></v-select>
            </v-col>
            <v-col
              cols="12"
              md="6"
              v-if="
                form.report_list === 'types' && form.report_type === 'specific'
              "
            >
              <v-select
                label="Select"
                :items="eventTypes"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.specificType')"
                item-text="key"
                item-value="value"
                hide-details
                v-model="selectedSpecificType"
                return-object
              ></v-select>
            </v-col>
            <v-col
              cols="12"
              md="6"
              v-if="
                form.report_list === 'types' &&
                  form.report_type === 'comparison'
              "
            >
              <v-select
                label="Select"
                :items="eventTypes"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.typeComparisons')"
                item-text="key"
                item-value="value"
                hide-details
                v-model="form.types"
                return-object
                multiple
              ></v-select>
            </v-col>
            <v-col cols="12" md="6">
              <v-select
                label="Select"
                :items="time_types"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.timeType')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="form.time_type"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="form.time_type === 'dynamic'">
              <v-select
                label="Select"
                :items="dates"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.date')"
                item-text="value"
                item-value="key"
                hide-details
                v-model="form.time_range"
              ></v-select>
            </v-col>
            <v-col cols="12" md="6" v-if="form.time_type === 'specific'">
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
                    v-model="form.start"
                    :label="$t('reports.selectFromDate')"
                    prepend-icon="mdi mdi-calendar"
                    readonly
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.start"
                  @input="menu = false"
                ></v-date-picker>
              </v-menu>
            </v-col>
            <v-col cols="12" md="6" v-if="form.time_type === 'specific'">
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
                    v-model="form.end"
                    :label="$t('reports.selectToDate')"
                    prepend-icon="mdi mdi-calendar"
                    readonly
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.end"
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
              @click="updateDraft()"
            >
              {{ $t("reports.edit") }}
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  name: "EditDraft",
  data() {
    return {
      form: {
        title: "",
        start: "",
        end: "",
        time_type: "",
        report_type: "",
        time_range: "",
        model_type: "",
        report_list: "",
        sites: [],
        types: []
      },
      errors: {},
      errorMessages: "",
      loading: false,
      menu: false,
      menu2: false,
      selectedTime: {},
      selectedSpecificLocation: "",
      selectedSpecificType: {},
      selectedDate: "",
      fromDate: "",
      toDate: "",
      breadcrumbs: [
        {
          text: this.$t("reports.reports"),
          disabled: true
        },
        {
          text: this.$t("reports.draftedReports"),
          href: "/reports/drafted"
        },
        { text: this.$t("reports.editDraft") }
      ],
      rules: {
        required: value => (value && Boolean(value)) || "Required"
      }
    };
  },
  watch: {},
  computed: {
    ...mapState("reports", [
      "draft",
      "models",
      "report_types",
      "show_types",
      "time_types"
    ]),
    ...mapState("locations", ["locations"]),
    ...mapState("events", ["eventTypes"]),

    dates() {
      return this.$store.state.reports.time_types[1].dates;
    }
  },
  mounted() {
    this.open();
    this.fetchLocations();
    this.fetchTypes();
  },
  created() {
    const { id } = this.$route.params;
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("reports.editDraftReport")
    });
    this.loading = true;
    this.getOneDraft(id)
      .then(() => {
        this.loading = false;
        const {
          id,
          title,
          start,
          end,
          time_type,
          report_type,
          time_range,
          model_type,
          report_list,
          columns,
          site_id,
          sites,
          types
        } = this.draft ?? {};
        this.form = {
          id,
          title,
          start,
          end,
          time_type,
          report_type,
          time_range,
          model_type,
          report_list,
          sites,
          types
        };

        if (this.form.report_type === "specific") {
          this.selectedSpecificLocation = site_id[0];
          this.selectedSpecificType = types[0];
        }
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    ...mapActions("reports", ["getOneDraft", "editDraft", "getBuilderOptions"]),
    ...mapActions("locations", ["getLocations"]),
    ...mapActions("events", ["getTypes"]),
    ...mapActions("app", ["setBreadCrumb"]),
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
    updateDraft() {
      this.isLoading = true;
      const { id } = this.$route.params;
      let siteIds = [];
      let types = [];

      if (this.form.report_type === "comparison" && this.form.sites) {
        this.form.sites.forEach(location => {
          siteIds.push(location.id);
        });
      }
      if (this.form.report_type === "specific" && this.selectedSpecificLocation)
        siteIds.push(this.selectedSpecificLocation);

      if (this.form.report_type === "comparison" && this.form.types) {
        this.form.types.forEach(type => {
          types.push(type.key);
        });
      }
      if (
        this.form.report_type === "specific" &&
        JSON.stringify(this.selectedSpecificType) != "{}"
      ) {
        types.push(this.selectedSpecificType.key);
      }

      let data = {
        id: id,
        title: this.form.title,
        model_type: this.form.model_type,
        report_list: this.form.report_list,
        type: this.form.report_type,
        time_type: this.form.time_type,
        time_range: this.form.time_range,
        start: this.form.start,
        end: this.form.end,
        site_ids: siteIds,
        types: types
      };
      console.log("data-->", data);

      this.editDraft(data)
        .then(response => {
          this.isLoading = false;
          makeToast("success", response.data.message);
          this.$router.push({ name: "reports-drafted" });
        })
        .catch(() => {
          this.isLoading = false;
        });
    }
  }
};
</script>

<style scoped></style>
