<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("types.createDetectionType") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

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
                :error-messages="errors['name']"
                outlined
                dense
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.display_name"
                :error-messages="errors['display_name']"
                :label="$t('types.nameOnScreen')"
                outlined
                dense
              ></v-text-field>
            </v-col>
          </v-row>
          <div class="d-flex mt-10">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="createDetectionType()"
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
  name: "CreateDetectionType",
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
          text: this.$t("types.createDetectionType")
        }
      ],
      loading: false,
      form: {
        name: "",
        display_name: ""
      },
      errors: {}
    };
  },
  computed: {
    ...mapState("types", ["types"])
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("types.createDetectionType")
    });
  },
  methods: {
    ...mapActions("types", ["storeDetectionType"]),
    ...mapActions("app", ["setBreadCrumb"]),
    createDetectionType() {
      this.loading = true;
      this.errors = {};
      this.storeDetectionType(this.form)
        .then(response => {
          this.loading = false;
          makeToast("success", response.data.message);
          this.$router.push({ name: "settings-detection-types" });
        })
        .catch(error => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data ?? {};
            this.errors = errors ?? {};
          }
        });
    }
  }
};
</script>
