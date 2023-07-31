<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('menu.general') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn icon @click>
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </div> -->

    <div class="d-flex flex-grow-1 flex-row">
      <!-- <v-navigation-drawer
        v-model="drawer"
        :app="$vuetify.breakpoint.mdAndDown"
        :permanent="$vuetify.breakpoint.lgAndUp"
        floating
        class="elevation-1 rounded flex-shrink-0"
        :class="[$vuetify.rtl ? 'ml-3' : 'mr-3']"
        width="240"
      >
        <general-settings-menu class="pa-2"></general-settings-menu>
      </v-navigation-drawer> -->

      <div class="d-flex flex-grow-1 flex-column">
        <!--Form Content-->
        <v-card class="pa-2">
          <v-card-text class="p-3 roles">
            <v-form>
              <div class="title mb-2">
                {{ $t("settings.siteInformation") }}
              </div>
              <v-row v-if="isLoading">
                <v-col v-for="skeleton in 4" :key="skeleton" cols="12" md="6">
                  <v-skeleton-loader
                    type="text"
                    width="100%"
                    height="40px"
                    class="input-skeleton"
                  >
                  </v-skeleton-loader>
                </v-col>
                <v-col v-for="skeleton in 2" :key="skeleton" cols="12" md="6">
                  <v-skeleton-loader
                    type="text"
                    width="100%"
                    height="140px"
                    class="area-skeleton"
                  >
                  </v-skeleton-loader>
                </v-col>
              </v-row>

              <v-row v-else>
                <v-col
                  cols="12"
                  md="6"
                  v-for="(setting, key) in settings"
                  :key="setting.key"
                  v-if="setting.group == 'site'"
                >
                  <v-textarea
                    outlined
                    hide-details
                    :label="$t('settings.' + setting.key)"
                    v-model="settings[key]['value']"
                    v-if="
                      setting.key == 'website_description' ||
                        setting.key == 'website_meta_description'
                    "
                  ></v-textarea>
                  <v-text-field
                    v-else
                    hide-details
                    outlined
                    v-model="settings[key]['value']"
                    :label="$t('settings.' + setting.key)"
                    dense
                  ></v-text-field>
                </v-col>
                <!--                <v-col cols="12" md="6">-->
                <!--                  <v-text-field value="" :label="$t('settings.rightsName')"></v-text-field>-->
                <!--                </v-col>-->
                <!--                <v-col cols="12" md="6">-->
                <!--                  <v-text-field value="" :label="$t('settings.websiteName')"></v-text-field>-->
                <!--                </v-col>-->
                <!--                <v-col cols="12" md="6">-->
                <!--                  <v-text-field value="" :label="$t('settings.websiteEmailAddress')"></v-text-field>-->
                <!--                </v-col>-->
                <!--                <v-col cols="12" md="6">-->
                <!--                  <v-textarea-->
                <!--                    :label="$t('settings.description')"-->
                <!--                    value=""-->
                <!--                  ></v-textarea>-->
                <!--                </v-col>-->
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
          </v-card-text>
        </v-card>
      </div>
    </div>
  </div>
</template>

<script>
import GeneralSettingsMenu from "../../../components/settings/general/GeneralSettingsMenu";
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  components: {
    GeneralSettingsMenu
  },
  data() {
    return {
      isLoading: false,
      items: ["Light Theme", "Dark Theme"],
      drawer: null,
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.settings"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.general"),
          to: "/settings/information",
          exact: true
        }
      ]
    };
  },
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
