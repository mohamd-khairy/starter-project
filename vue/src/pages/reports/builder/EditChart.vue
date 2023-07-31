<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" max-width="500">
      <v-card>
        <v-card-title
          class="text-h5 d-flex justify-space-between align-center border-bottom"
        >
          {{ $t("general.editChart") }}
          <v-btn icon @click="dialog = false">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text class="mt-4">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="form.title"
                :label="$t('general.title')"
                solo
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-select
                :items="timeUnits"
                :label="$t('general.time_unit')"
                :item-text="item => item.text"
                :item-value="item => item.value"
                hide-details
                clearable
                v-model="form.time_unit"
                solo
              >
              </v-select>
            </v-col>
            <v-col cols="12">
              <v-textarea
                :label="$t('general.description')"
                value=""
                v-model="form.description"
                solo
              ></v-textarea>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="primary" class="mx-1" @click="updateChartMethod">
            {{ $t("general.save") }}
          </v-btn>

          <v-btn elevation="0" color="grey lighten-3" @click="dialog = false">
            {{ $t("general.cancel") }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import { mapActions } from "vuex";
import { makeToast } from "@/helpers";

export default {
  name: "EditChart",
  props: {
    value: Boolean,
    chart: Object,
    id: Number
  },
  data() {
    return {
      timeUnits: [
        { text: this.$t("general.minute"), value: "minute" },
        { text: this.$t("general.hour"), value: "hour" },
        { text: this.$t("general.day"), value: "day" }
      ],
      form: { title: "", description: "", time_unit: "", type: "" }
    };
  },
  computed: {
    dialog: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    },
    chartConfig: {
      get() {
        return this.$store.state.reports.chartConfig;
      },
      set(val) {
        this.$store.commit("reports/SET_CHART_CONFIG", val);
      }
    }
  },
  watch: {
    id() {
      this.refresh();
    }
  },
  methods: {
    ...mapActions("reports", ["updateConfig"]),
    refresh() {
      this.loading = true;
      this.chartConfig = this.chart;
      const { title, description, time_unit, type } = this.chart ?? {};
      this.form = { title, description, time_unit, type };
    },
    fetchData: function() {
      this.$root.$emit("chart_component");
    },
    updateChartMethod() {
      this.loading = true;
      this.errors = {};
      let data = {
        title: this.form.title,
        description: this.form.description,
        time_unit: this.form.time_unit,
        type: this.form.type
      };
      this.updateConfig(data)
        .then(response => {
          this.loading = false;
          this.dialog = false;
          this.fetchData();
          this.errors = {};
          makeToast("success", response.data.message);
        })
        .catch(error => {
          this.loading = false;
          // if (error.response.status == 422) {
          //   const { errors } = error?.response?.data;
          //   this.errors = errors ?? {};
          // }
        });
    }
  }
};
</script>
