export default [
  {
    path: "/settings/general",
    name: "",
    meta: {
      auth: true,
      permissions: "read-setting"
    },

    component: () =>
      import(
        /* webpackChunkName: "settings-general" */ "@/pages/settings/general/GeneralSettingsPage.vue"
      ),
    children: [
      {
        path: "/",
        redirect: "information"
      },
      {
        path: "information",
        name: "settings-information",
        component: () =>
          import(
            /* webpackChunkName: "settings-general" */ "@/pages/settings/general/InformationPage.vue"
          )
      },
      {
        path: "theme",
        name: "settings-theme",
        component: () =>
          import(
            /* webpackChunkName: "settings-colors */ "@/pages/settings/general/ThemePage.vue"
          )
      },
      {
        path: "properties",
        name: "settings-properties",
        component: () =>
          import(
            /* webpackChunkName: "settings-properties" */ "@/pages/settings/general/PropertiesPage.vue"
          )
      }
    ]
  },

  {
    path: "/settings/mail-template",
    name: "settings-mail-template",
    meta: {
      auth: true,
      permissions: "read-setting"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-mail-template */ "@/pages/settings/mail/MailTemplatePage.vue"
      )
  },
  {
    path: "/settings/mail-server",
    name: "settings-mail-server",
    meta: {
      auth: true,
      permissions: "read-setting"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-mail */ "@/pages/settings/mail/MailServerPage.vue"
      )
  },
  {
    path: "/settings/sms",
    name: "settings-sms",
    meta: {
      auth: true,
      permissions: "read-setting"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-sms */ "@/pages/settings/sms/SMSPage.vue"
      )
  },
  {
    path: "/settings/stations",
    name: "settings-stations",
    meta: {
      auth: true,
      permissions: "read-location"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-stations */ "@/pages/settings/stations/StationsPage.vue"
      )
  },
  {
    path: "/settings/stations/edit/:id",
    name: "settings-stations-edit",
    meta: {
      auth: true,
      permissions: "update-location"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-stations-edit */ "@/pages/settings/stations/EditPage.vue"
      )
  },
  {
    path: "/settings/detection-types",
    name: "settings-detection-types",
    meta: {
      auth: true,
      permissions: "read-type"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-types */ "@/pages/settings/detection-types/DetectionTypesPage"
      )
  },
  {
    path: "/settings/detection-types/create",
    name: "settings-detection-types-create",
    meta: {
      auth: true,
      permissions: "create-type"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-types-create" */ "@/pages/settings/detection-types/CreateDetectionType"
      )
  },
  {
    path: "/settings/detection-types/edit/:id",
    name: "settings-detection-types-edit",
    meta: {
      auth: true,
      permissions: "update-type"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-types-edit */ "@/pages/settings/detection-types/EditDetectionType"
      )
  },
  {
    path: "/settings/drones",
    name: "settings-drones",
    meta: {
      auth: true,
      permissions: "read-drone"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-drones */ "@/pages/settings/drones/DronesPage.vue"
      )
  },
  {
    path: "/settings/logs",
    name: "settings-logs",
    meta: {
      auth: true,
      permissions: "read-drone"
    },
    component: () =>
      import(
        /* webpackChunkName: "settings-drones */ "@/pages/settings/logs/index.vue"
      )
  }
];
