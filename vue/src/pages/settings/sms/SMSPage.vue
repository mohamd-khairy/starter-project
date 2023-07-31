<template>
  <div class="d-flex flex-column flex-grow-1">
    <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('general.smsSettings') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div>

    <v-row>
      <v-col cols="12">
        <v-card class="pa-2">
          <div class="title mb-2">
            {{ $t('general.smsInformation') }}
          </div>
          <v-form>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field value="" :label="$t('general.url')"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field value="" :label="$t('general.user')"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field value="" type="password" :label="$t('general.password')"></v-text-field>
              </v-col>
            </v-row>
            <div class="d-flex mt-3">
              <v-btn color="primary">{{ $t('general.save') }}</v-btn>
            </div>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>

import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  components: {
  },
  data() {
    return {
      isLoading: false,
      breadcrumbs: [{
        text: this.$t('menu.settings'),
        disabled: false,
        href: '#'
      }, {
        text: this.$t('menu.sms')
      }],
      editor: ClassicEditor,
      editorData: '<p>Hello User <br>[[body]]</p>',
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
    }
  },
  watch: {
    selectedRoles(val) {

    }
  },
  methods: {
    searchRole() {
    },
    open() {
    }
  }
}
</script>

<style >
.ck-editor__editable.ck-content{
  min-height: 200px;
}
</style>
