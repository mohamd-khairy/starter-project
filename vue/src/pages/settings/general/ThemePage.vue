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
          <v-card-text class="">
            <v-form>
              <div class="title mb-2">
                {{ $t("settings.theme") }}
              </div>

              <v-row>
                <v-col cols="12" lg="8">
                  <v-row>
                    <v-col cols="12" md="6">
                      <div class="font-weight-bold my-1">Global Theme</div>
                      <v-btn-toggle
                        v-model="theme"
                        color="primary"
                        mandatory
                        class="mb-2"
                      >
                        <v-btn>Light</v-btn>
                        <v-btn>Dark</v-btn>
                      </v-btn-toggle>
                    </v-col>
                    <v-col cols="12" md="6">
                      <div class="font-weight-bold my-1">Toolbar Theme</div>
                      <v-btn-toggle
                        v-model="toolbarColor"
                        color="primary"
                        mandatory
                        class="mb-2"
                      >
                        <v-btn>Global</v-btn>
                        <v-btn>Light</v-btn>
                        <v-btn>Dark</v-btn>
                      </v-btn-toggle>
                    </v-col>
                    <!-- <v-col cols="12" md="6">
                      <div class="font-weight-bold my-1">Toolbar Style</div>
                      <v-btn-toggle
                        v-model="toolbarStyle"
                        color="primary"
                        mandatory
                        class="mb-2"
                      >
                        <v-btn>Full</v-btn>
                        <v-btn>Solo</v-btn>
                      </v-btn-toggle>
                    </v-col> -->
                    <v-col cols="12" md="6">
                      <div class="font-weight-bold my-1">Content Layout</div>
                      <v-btn-toggle
                        v-model="contentBoxed"
                        color="primary"
                        mandatory
                        class="mb-2"
                      >
                        <v-btn>Fluid</v-btn>
                        <v-btn>Boxed</v-btn>
                      </v-btn-toggle>
                    </v-col>
                    <v-col cols="12" md="6">
                      <div class="font-weight-bold my-1">Menu Theme</div>
                      <v-btn-toggle
                        v-model="menuColor"
                        color="primary"
                        mandatory
                        class="mb-2"
                      >
                        <v-btn>Global</v-btn>
                        <v-btn>Light</v-btn>
                        <v-btn>Dark</v-btn>
                      </v-btn-toggle>
                    </v-col>
                    <v-col cols="12" md="6">
                      <div class="font-weight-bold my-1">RTL</div>
                      <v-switch
                        v-model="rtl"
                        inset
                        label="Right to Left"
                      ></v-switch>
                    </v-col>
                  </v-row>
                </v-col>
                <v-col cols="12" lg="3">
                  <v-row>
                    <v-col cols="12">
                      <div class="font-weight-bold my-1">Primary Color</div>

                      <v-color-picker
                        v-model="color"
                        mode="hexa"
                        :swatches="swatches"
                        show-swatches
                      ></v-color-picker>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>

              <div class="d-flex mt-3">
                <v-btn @click="saveConfigs" color="primary">{{
                  $t("general.save")
                }}</v-btn>
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
import { mapActions, mapMutations, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  components: {
    GeneralSettingsMenu
  },
  data() {
    return {
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
      right: false,

      // toolbarColor: 0,
      // toolbarStyle: 0,
      // contentBoxed: 0,
      // menuColor: 0,
      timeout: null,
      // color: '#0096c7',
      swatches: [
        ["#0096c7", "#31944f"],
        ["#EE4f12", "#46BBB1"],
        ["#ee44aa", "#55BB46"]
      ],

      // rtl: 0,

      // timezones
      availableTimezones: [
        "America/Los_Angeles",
        "America/New_York",
        "Europe/London",
        "Asia/Tokyo",
        "Australia/Sydney"
      ],

      // time formats
      availableFormats: [
        {
          label: "07/31/2020",
          format: "L"
        },
        {
          label: "Jul 31, 2020",
          format: "ll"
        },
        {
          label: "20200731",
          format: "YYYYMMDD"
        }
      ]
    };
  },
  computed: {
    ...mapState("app", [
      "time",
      "currency",
      "availableCurrencies",
      "primaryColor",
      "globalTheme",
      "menuTheme",
      "toolbarTheme",
      "isToolbarDetached",
      "isContentBoxed",
      "isRTL"
    ]),
    theme: {
      get() {
        return this.globalTheme === "light" ? 0 : 1;
      },
      set(val) {
        this.setGlobalTheme(val === 0 ? "light" : "dark");
      }
    },
    color: {
      get() {
        return this.primaryColor;
      },
      set(val) {
        this.setPrimaryColor(val);
      }
    },
    menuColor: {
      get() {
        return this.menuTheme === "global"
          ? 0
          : this.menuTheme === "light"
          ? 1
          : 2;
      },
      set(val) {
        const theme = val === 0 ? "global" : val === 1 ? "light" : "dark";
        this.setMenuTheme(theme);
      }
    },
    toolbarColor: {
      get() {
        return this.toolbarTheme === "global"
          ? 0
          : this.toolbarTheme === "light"
          ? 1
          : 2;
      },
      set(val) {
        const theme = val === 0 ? "global" : val === 1 ? "light" : "dark";
        this.setToolbarTheme(theme);
      }
    },
    contentBoxed: {
      get() {
        return this.isContentBoxed === "1" ? 1 : 0;
      },
      set(val) {
        this.setContentBoxed(val === 1);
      }
    },
    rtl: {
      get() {
        return this.isRTL === true ? 1 : 0;
      },
      set(val) {
        this.setRTL(val);
      }
    },
    toolbarStyle: {
      get() {
        return this.isToolbarDetached === true ? 1 : 0;
      },
      set(val) {
        // console.log('detatched', val);
        this.setToolbarDetached(val === 1);
      }
    }
  },
  created() {
    // this.theme = this.globalTheme === 'light' ? 0 : 1;
    // this.menuColor = this.menuTheme === 'global' ?  0  : (this.menuTheme === 'light' ? 1 : 2);
    // this.toolbarColor = this.toolbarTheme === 'global' ? 0 : (this.toolbarTheme === 'light' ? 1 : 2);
    // this.contentBoxed = this.isContentBoxed === true ? 1 : 0;
    // this.rtl = this.isRTL === true ? 1 : 0;
    // this.toolbarStyle = this.isToolbarDetached === true ? 1 : 0;
  },
  watch: {
    color(val) {
      const { isDark } = this.$vuetify.theme;

      this.$vuetify.theme.themes.dark.primary = val;
      this.$vuetify.theme.themes.light.primary = val;
    }
  },
  mounted() {
    this.animate();
  },
  beforeDestroy() {
    if (this.timeout) clearTimeout(this.timeout);
  },
  methods: {
    ...mapActions("app", ["saveThemeConfig"]),
    ...mapMutations("app", [
      "setMenuTheme",
      "setPrimaryColor",
      "setGlobalTheme",
      "setToolbarTheme",
      "setContentBoxed",
      "setTimeZone",
      "setTimeFormat",
      "setCurrency",
      "setRTL",
      "setToolbarDetached"
    ]),
    setTheme() {
      this.$vuetify.theme.dark = this.theme === "dark";
    },
    saveConfigs() {
      let config = {
        content_layout: this.contentBoxed === 1,
        global_theme: this.theme === 0 ? "light" : "dark",
        menu_theme:
          this.menuColor === 0
            ? "global"
            : this.menuColor === 1
            ? "light"
            : "dark",
        rtl: this.rtl,
        primary_color: this.color,
        toolbar_style: this.toolbarStyle === 1,
        toolbar_theme:
          this.toolbarColor === 0
            ? "global"
            : this.toolbarColor === 1
            ? "light"
            : "dark"
      };
      this.saveThemeConfig(config)
        .then(response => {
          makeToast("success", response.data.message);
          localStorage.setItem(
            "loaderBg",
            this.globalTheme === "light" ? "#fff" : "#161616"
          );
          localStorage.setItem("loaderColor", this.primaryColor);
        })
        .catch(() => {});
    },
    animate() {
      if (this.timeout) clearTimeout(this.timeout);

      const time = (Math.floor(Math.random() * 10 + 1) + 10) * 1000;

      this.timeout = setTimeout(() => {
        this.$animate(this.$refs.button.$el, "bounce");
        this.animate();
      }, time);
    }
  }
};
</script>
