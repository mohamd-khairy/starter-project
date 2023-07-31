<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('menu.mailTemplate') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div> -->

    <v-row>
      <v-col cols="12">
        <v-card class="pa-2">
          <div class="title mb-2">
            {{ $t("general.mailTemplateContent") }}
          </div>
          <v-form>
            <div v-if="isLoading" class="mb-4">
              <v-skeleton-loader type="text" width="20%"> </v-skeleton-loader>
              <v-skeleton-loader
                type="text"
                width="100%"
                height="240px"
                class="template-skeleton"
              >
              </v-skeleton-loader>
            </div>
            <div
              class="mb-4"
              v-for="(setting, key) in settings"
              :key="setting.key"
              v-if="setting.group == 'mail_template'"
            >
              <label
                class="text--primary font-weight-medium text-darken mb-1 d-block"
                >{{ $t("email.generalMail") }}</label
              >
              <ckeditor
                :editor="editor"
                v-model="settings[key]['value']"
                :config="editorConfig"
              ></ckeditor>
            </div>
            <!--            <div class="mb-4">-->
            <!--              <label class="text&#45;&#45;primary font-weight-medium text-darken mb-1 d-block">{{ $t('email.newUserMail') }}</label>-->
            <!--              <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>-->
            <!--            </div>-->
            <!--            <div class="mb-4">-->
            <!--              <label class="text&#45;&#45;primary font-weight-medium text-darken mb-1 d-block">{{ $t('email.mailWhenUserUpdate') }}</label>-->
            <!--              <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>-->
            <!--            </div>-->
            <!--            <div class="mb-3">-->
            <!--              <label class="text&#45;&#45;primary font-weight-medium text-darken mb-1 d-block">{{ $t('email.mailNotificationForms') }}</label>-->
            <!--              <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>-->
            <!--            </div>-->
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
          text: this.$t("menu.mailTemplate")
        }
      ],
      editor: ClassicEditor,
      editorData: "<p>مرحبا بالمستخدم <br>[[المحتوى]]</p>",
      editorConfig: {
        // The configuration of the editor.
      }
      // breadcrumbs: [{
      //   text: 'Roles',
      //   to: '/roles/list',
      //   exact: true
      // }, {
      //   text: 'View Role'
      // }]
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
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.mailTemplate")
    });
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
.template-skeleton {
  height: 240px;
}
.template-skeleton .v-skeleton-loader__text {
  height: 100%;
}
</style>
