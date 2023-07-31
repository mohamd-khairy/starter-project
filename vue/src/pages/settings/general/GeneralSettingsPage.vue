<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('menu.general') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn icon >
        <v-icon>mdi-refresh</v-icon>
      </v-btn>
    </div> -->

    <div class="d-flex flex-grow-1 flex-row">
      <v-navigation-drawer
        v-model="drawer"
        :app="$vuetify.breakpoint.mdAndDown"
        :permanent="$vuetify.breakpoint.lgAndUp"
        floating
        class="elevation-1 rounded flex-shrink-0"
        :class="[$vuetify.rtl ? 'ml-3' : 'mr-3']"
        width="240"
      >
        <general-settings-menu class="pa-2"></general-settings-menu>
      </v-navigation-drawer>
      <!-- <v-tabs v-model="tab" align-with-title>
        <v-tab to="/settings/general/information">Information</v-tab>
        <v-tab to="/settings/general/theme">Theme</v-tab>
        <v-tab to="/settings/general/properties">Properties</v-tab>
      </v-tabs> -->
      <div class="d-flex flex-grow-1 flex-column">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

import GeneralSettingsMenu from '../../../components/settings/general/GeneralSettingsMenu'


export default {
  components: {
    GeneralSettingsMenu
  },
  data() {
    return {
      tab: 'information',
      items: ['Light Theme', 'Dark Theme'],
      drawer: null,
      loading:false,
      breadcrumbs: [{
        text: this.$t('menu.settings'),
        disabled: false,
        href: '#'
      }, {
        text: this.$t('menu.general'),
        to: '/settings/information',
        exact: true
      }]
    }
  },
  created(){
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t('menu.general')
    });
  },
  methods:{
    ...mapActions("app", ["setBreadCrumb"]),
  }



}
</script>
