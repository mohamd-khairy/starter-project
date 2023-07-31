<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("reports.editPinnedReports") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

    </div> -->

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <div class="title mb-2">
            {{ $t("reports.editPinned") }}
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
                :items="dateStatuses"
                variant="underlined"
                class=" mx-1"
                :label="$t('reports.dateStatus')"
                item-text="key"
                item-value="value"
                v-model="form.global_date"
              ></v-select>
            </v-col>

            <v-col cols="12" md="6" v-if="form.global_date === 1">
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
            <v-col
              cols="12"
              md="6"
              v-if="form.time_type === 'dynamic' && form.global_date === 1"
            >
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
            <v-col
              cols="12"
              md="6"
              v-if="form.time_type === 'specific' && form.global_date === 1"
            >
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
            <v-col
              cols="12"
              md="6"
              v-if="form.time_type === 'specific' && form.global_date === 1"
            >
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
              @click="updatePinned()"
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
  name: "EditPin",
  data() {
    return {
      dateStatuses: [
        { key: this.$t("reports.mixed"), value: 0 },
        { key: this.$t("reports.global"), value: 1 }
      ],
      form: {
        title: "",
        start: "",
        end: "",
        time_type: "",
        time_range: "",
        global_date: ""
      },
      errors: {},
      errorMessages: "",
      loading: false,
      menu: false,
      menu2: false,
      fromDate: "",
      toDate: "",
      breadcrumbs: [
        {
          text: this.$t("reports.reports"),
          disabled: true
        },
        {
          text: this.$t("reports.pinnedReports"),
          href: "/reports/pinned"
        },
        { text: this.$t("reports.editPinned") }
      ],
      rules: {
        required: value => (value && Boolean(value)) || "Required"
      }
    };
  },
  watch: {},
  computed: {
    ...mapState("reports", ["pin", "time_types"]),
    dates() {
      return this.$store.state.reports.time_types[1].dates;
    }
  },
  mounted() {
    this.open();
  },
  created() {
    const { id } = this.$route.params;
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("reports.editPinnedReports")
    });
    this.loading = true;
    this.getOnePinned(id)
      .then(() => {
        this.loading = false;
        const { id, title, start, end, time_type, time_range, global_date } =
          this.pin ?? {};
        this.form = {
          id,
          title,
          start,
          end,
          time_type,
          time_range,
          global_date
        };
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    ...mapActions("reports", [
      "getOnePinned",
      "editPinned",
      "getBuilderOptions"
    ]),
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
    updatePinned() {
      this.isLoading = true;
      const { id } = this.$route.params;

      let data = {
        id: id,
        title: this.form.title,
        global_date: this.form.global_date,
        time_type: this.form.time_type,
        time_range: this.form.time_range,
        start: this.form.start,
        end: this.form.end
      };

      this.editPinned(data)
        .then(response => {
          this.isLoading = false;
          makeToast("success", response.data.message);
          this.$router.push({ name: "reports-pinned" });
        })
        .catch(() => {
          this.isLoading = false;
        });
    }
  }
};
</script>

<style scoped></style>
