<template>
  <div>

    <v-list dense nav class="mt-2 pa-0">
      <v-list-item
        v-for="(item, index) in menu"
        :key="index"
        :to="item.link"
        active-class="primary--text"

      >
        <v-list-item-icon>
          <v-icon small>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ $t(item.label) }}</v-list-item-title>
        </v-list-item-content>

        <v-list-item-action v-if="item.count > 0">
          <v-badge
            inline
            color="primary"
            class="font-weight-bold"
            :content="item.count"
          >
          </v-badge>
        </v-list-item-action>
      </v-list-item>
    </v-list>

  </div>
</template>

<script>

/*
|---------------------------------------------------------------------
| Email Menu Component
|---------------------------------------------------------------------
|
| Navigation for email boxes
|
*/
export default {
  components: {
  },
  data() {
    return {
      showCompose: false,
      menu: [{
        label: 'settings.siteInformation',
        icon: 'mdi-pencil-outline',
        link: '/settings/general/information'
      }, {
        label: 'settings.properties',
        icon: 'bx bxs-inbox',
        link: '/settings/general/properties'
      },
      //   {
      //   label: 'settings.notifications',
      //   icon: 'mdi-bell-outline',
      //   link: '/settings/notifications'
      // },
        {
        label: 'settings.theme',
        icon: 'mdi-view-dashboard-outline',
        link: '/settings/general/theme'
      }]
    }
  }
}
</script>
