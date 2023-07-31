<template>
  <v-app :key="appComponent">
    <!-- Layout component -->
    <component :is="currentLayout" v-if="isRouterLoaded">
      <transition name="fade" mode="out-in">
        <router-view :key="$route.fullPath" />
      </transition>
    </component>

    <v-snackbar
      v-model="toast.show"
      :timeout="toast.timeout"
      :color="toast.color"
      bottom
    >
      {{ toast.message }}
      <v-btn
        v-if="toast.timeout === 0"
        color="white"
        text
        @click="toast.show = false"
        >{{ $t("common.close") }}</v-btn
      >
    </v-snackbar>

    <!-- Demo customization menu -->
    <!--    <customization-menu />-->
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

// Demo Menu

import config from "./configs";

// Layouts
import defaultLayout from "./layouts/DefaultLayout";
import landingLayout from "./layouts/LandingLayout";
import simpleLayout from "./layouts/SimpleLayout";
import authLayout from "./layouts/AuthLayout";
import errorLayout from "./layouts/ErrorLayout";

/*
|---------------------------------------------------------------------
| Main Application Component
|---------------------------------------------------------------------
|
| In charge of choosing the layout according to the router metadata
|
*/
export default {
  components: {
    defaultLayout,
    landingLayout,
    simpleLayout,
    authLayout,
    errorLayout
  },
  data() {
    return {
      appComponent: 0
    };
  },
  computed: {
    ...mapState("app", [
      "toast",
      "websiteName",
      "websiteFavoritePlaceIcon",
      "globalTheme",
      "primaryColor"
    ]),
    ...mapState("app", {
      websiteLogo: state => {
        if (!state.websiteLogoLarge) {
          return "/images/swcc.png";
        }
        return state.websiteLogoLarge;
        // const result = state.generalSettings.filter(
        //   setting => setting.key === "website_logo_large"
        // );
        // if (result.length > 0) {
        //   return result[0]["full_url"]
        //     ? result[0]["full_url"]
        //     : "/images/swcc.png";
        // }
        // return "";
      }
    }),
    // ...mapState("settings", {
    //   websitefavIcon: state => {
    //     const result = state.generalSettings.filter(
    //       setting => setting.key === "website_favorite_place_icon"
    //     );

    //     if (result.length > 0) {
    //       return result[0]["full_url"];
    //     }
    //     return "/images/swcc.png";
    //   },
    //   websiteName: state => {
    //     const result = state.generalSettings.filter(
    //       setting => setting.key === "website_name"
    //     );
    //     if (result.length > 0) {
    //       return result[0]["value"];
    //     }
    //     return "_";
    //   },
    //   settingLoading: state => state.generalSettingLoading
    // }),
    isRouterLoaded: function() {
      return this.$route.name !== null;
    },
    currentLayout: function() {
      const layout = this.$route.meta.layout || "default";

      return layout + "Layout";
    }
  },
  created() {
    // document.title = this.websiteName;
    this.setFavicon(this.websiteFavoritePlaceIcon);
    localStorage.setItem(
      "loaderBg",
      this.globalTheme === "light" ? "#fff" : "#161616"
    );
    localStorage.setItem("loaderColor", this.primaryColor);

    // this.getGeneralSettings().then(response => {
    //   this.setThemeConfig(response);
    //   this.setFavicon(this.websiteFavoritePlaceIcon);
    //   document.title = this.websiteName;
    // });
  },
  mounted() {
    this.$root.$on("LanguageChanged", () => {
      this.appComponent += 1;
    });
  },
  methods: {
    ...mapActions("app", ["setThemeConfig"]),
    ...mapActions("settings", ["getGeneralSettings"]),
    setFavicon(link) {
      const faviconLink = document.querySelector('link[rel="icon"]');
      if (faviconLink) {
        faviconLink.href = link;
      } else {
        const newFaviconLink = document.createElement("link");
        newFaviconLink.rel = "icon";
        newFaviconLink.href = link;
        document.head.appendChild(newFaviconLink);
      }
    }
  },
  head: {
    link: [
      // adds config/icons into the html head tag
      ...config.icons.map(href => ({ rel: "stylesheet", href }))
    ]
  }
};
</script>

<style scoped>
/**
 * Transition animation between pages
 */
.fade-enter-active,
.fade-leave-active {
  transition-duration: 0.2s;
  transition-property: opacity;
  transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>
