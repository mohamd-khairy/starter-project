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
        <v-card class="pa-2 properties">
          <v-card-text class="p-3 roles">
            <v-form>
              <div class="title mb-2">
                {{ $t("settings.properties") }}
              </div>
              <v-row v-if="isLoading">
                <v-col
                  v-for="skeleton in 4"
                  :key="skeleton"
                  cols="12"
                  md="6"
                  class="text-center"
                >
                  <v-skeleton-loader
                    type="avatar,text"
                    width="100%"
                    height="40px"
                    class="input-skeleton input-skeleton--file"
                  >
                  </v-skeleton-loader>
                  <v-skeleton-loader
                    type="image"
                    width="200px"
                    height="140px"
                    class="mx-auto mt-2"
                  >
                  </v-skeleton-loader>
                </v-col>
              </v-row>
              <v-row>
                <v-col
                  cols="12"
                  md="6"
                  class="text-center"
                  v-for="(setting, key) in settings"
                  :key="setting.key"
                  v-if="setting.group == 'properties'"
                >
                  <v-file-input
                    outlined
                    dense
                    :label="$t('settings.' + setting.key)"
                    v-model="settings[key]['value']"
                    prepend-icon="mdi-camera"
                    accept="image/png, image/jpeg, image/x-icon"
                    @change="
                      Preview_image(
                        settings[key]['value'],
                        setting.key,
                        setting.group,
                        key
                      )
                    "
                  >
                  </v-file-input>
                  <img
                    :src="setting.full_url"
                    :id="'url-' + setting.key"
                    width="200"
                    height="140"
                    alt=""
                    style="object-fit:contain"
                  />
                </v-col>
              </v-row>

              <!--              <div class="d-flex mt-3">-->
              <!--                <v-btn color="primary"-->
              <!--                       @click.prevent="updateSettingMethod()"-->
              <!--                       :loading="loading"-->
              <!--                       :disabled="loading">{{ $t('general.save') }}</v-btn>-->
              <!--              </div>-->
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
      ],
      drawer: null,
      loading: false,
      url: null,
      image: null,
      imagePath: null
    };
  },
  computed: {
    ...mapState("settings", ["settings"]),
    ...mapState("settings", {
      websitefavIcon: state => {
        const result = state.generalSettings.filter(
          setting => setting.key === "website_favorite_place_icon"
        );

        if (result.length > 0) {
          return result[0]["full_url"];
        }
        return "/images/swcc.png";
      },
      websiteName: state => {
        const result = state.generalSettings.filter(
          setting => setting.key === "website_name"
        );
        if (result.length > 0) {
          return result[0]["value"];
        }
        return "_";
      },
      settingLoading: state => state.generalSettingLoading
    })
  },
  mounted() {
    this.open();
  },
  methods: {
    ...mapActions("settings", ["getSettings", "getGeneralSettings"]),
    getImageUrl(image, url) {
      if (typeof image == "string") {
        return url + "/storage/" + image;
      }
    },
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
    Preview_image(image, key, group, index) {
      this.loading = true;
      // let img = document.getElementById("url-"+key)
      // img.src = URL.createObjectURL(image)

      // const fr = new FileReader()
      // fr.readAsDataURL(image)
      // fr.addEventListener('load', () => {
      //   this.image = fr.result
      // })
      let formData = new FormData();
      formData.append("value", image);
      formData.append("group", group);
      formData.append("key", key);
      this.$axios
        .post("set-setting", formData)
        .then(response => {
          if (response.data.data.id) {
            this.settings[index].full_url = response.data.data.full_url;
          }
          this.getGeneralSettings().then(response => {
            this.setThemeConfig(response);
            this.setFavicon(this.websitefavIcon);
            document.title = this.websiteName;
          });
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    }
  }
};
</script>
<style>
.v-responsive__content {
  width: fit-content !important;
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
.input-skeleton--file {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 10px;
}
.input-skeleton--file .v-skeleton-loader__avatar {
  width: 24px;
  height: 24px;
}
</style>
