<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('menu.mailServer') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div> -->

    <v-row>
      <v-col cols="12">
        <v-card class="pa-2">
          <div class="title mb-2">
            {{ $t("general.mailServerInformation") }}
          </div>
          <v-form>
            <v-row v-if="isLoading" class="mb-2">
              <v-col v-for="skeleton in 4" :key="skeleton" cols="12" md="6">
                <v-skeleton-loader
                  type="text"
                  width="100%"
                  height="40px"
                  class="input-skeleton"
                >
                </v-skeleton-loader>
              </v-col>
            </v-row>
            <v-row>
              <v-col
                cols="12"
                md="6"
                v-for="(setting, key) in settings"
                :key="setting.key"
                v-if="setting.group == 'mail_server'"
              >
                <v-text-field
                  outlined
                  dense
                  hide-details
                  v-model="settings[key]['value']"
                  :label="$t('general.' + setting.key)"
                ></v-text-field>
              </v-col>
            </v-row>
            <div class="d-flex mt-3">
              <v-btn
                color="primary"
                @click.prevent="updateSettingMethod()"
                :loading="loading"
                :disabled="loading"
                >{{ $t("general.save") }}</v-btn
              >
            </div>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  components: {},
  data() {
    return {
      isLoading: false,
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.settings"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.mailServer")
        }
      ]
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.mailServer")
    });
  },
  watch: {},
  computed: {
    ...mapState("settings", ["settings"]),
    settings: {
      get() {
        return this.$store.state.settings.settings;
      },
      set(val) {
        this.$store.commit("settings/SET_SETTINGS", val);
      }
    }
  },
  mounted() {
    this.open();
  },
  methods: {
    ...mapActions("settings", ["getSettings", "updateSettings"]),
    ...mapActions("app", ["setBreadCrumb"]),
    open() {
      this.isLoading = true;
      this.getSettings()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    updateSettingMethod() {
      let data = {
        settings: this.settings
      };
      this.loading = true;
      this.errors = {};
      this.updateSettings(data)
        .then(response => {
          this.loading = false;
          this.errors = {};
          const message = response?.data?.message || response?.data?.msg;
          if (message) {
            makeToast("success", message);
          }
        })
        .catch(error => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data;
            this.errors = errors ?? {};
          }
        });
    }
  }
};
</script>

<style>
.ck-editor__editable.ck-content {
  min-height: 200px;
}
.input-skeleton {
  height: 40px;
}
.area-skeleton {
  height: 140px;
}
.input-skeleton .v-skeleton-loader__text,
.area-skeleton .v-skeleton-loader__text {
  height: 100%;
}
</style>
