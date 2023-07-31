<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("types.editDetectionType") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn icon @click="refresh()">
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </div> -->

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <div class="title mb-2">
            {{ $t("types.name") }}
          </div>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.name"
                :label="$t('types.name')"
                outlined
                dense
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.display_name"
                :label="$t('types.nameOnScreen')"
                outlined
                dense
              ></v-text-field>
            </v-col>
          </v-row>
          <div class="d-flex mt-10">
            <v-btn
              @click="editDetectionType"
              :loading="loading"
              :disabled="loading"
              color="primary"
            >
              {{ $t("general.save") }}
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
  name: "EditDetectionType",
  components: {},
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("menu.detection_types"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("types.detectionTypesList"),
          to: "/settings/detection-types",
          exact: true
        },
        {
          text: this.$t("types.editDetectionType")
        }
      ],
      form: { name: "", display_name: "" },
      loading: false
    };
  },
  computed: { ...mapState("types", ["type"]) },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("types.editDetectionType")
    });
    this.refresh();
  },
  methods: {
    ...mapActions("types", ["getDetectionType", "updateDetectionType"]),
    ...mapActions("app", ["setBreadCrumb"]),
    refresh() {
      const { id } = this.$route.params;
      this.loading = true;
      this.getDetectionType(id)
        .then(() => {
          this.loading = false;
          const { name, display_name, id } = this.type ?? {};
          this.form = { name, display_name, id };
        })
        .catch(() => {
          this.loading = false;
        });
    },
    editDetectionType() {
      this.loading = true;
      this.updateDetectionType({ _method: "PUT", ...this.form })
        .then(response => {
          this.loading = false;
          console.log(response);
          makeToast("success", response.data.message);
          this.$router.push({ name: "settings-detection-types" });
        })
        .catch(error => {
          this.loading = false;
        });
    }
  }
};
</script>
