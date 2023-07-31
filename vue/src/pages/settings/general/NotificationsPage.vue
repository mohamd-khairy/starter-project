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
                {{ $t('settings.notifications') }}
              </div>

              <v-row>
                <v-col cols="12" md="6">
                  <v-select
                    :label="$t('settings.userNotification')"
                    :items="userNotifications"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    :label="$t('settings.modelsNotifications')"
                    :items="modelsNotifications"
                  ></v-select>
                </v-col>
              </v-row>

              <div class="d-flex mt-3">
                <v-btn color="primary">{{ $t('general.save') }}</v-btn>
              </div>
            </v-form>
          </v-card-text>
        </v-card>
      </div>
    </div>
  </div>
</template>

<script>

import GeneralSettingsMenu from '../../../components/settings/general/GeneralSettingsMenu'

export default {
  components: {
    GeneralSettingsMenu
  },
  data() {
    return {
      drawer: null,
      userNotifications: ['Notifications', 'Emails', 'Notifications & Emails'],
      modelsNotifications: ['Notifications', 'Emails', 'Notifications & Emails'],
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
  }
}
</script>
