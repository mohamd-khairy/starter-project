<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('stations.editStation') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div> -->

    <v-row>
      <v-col cols="12">
        <v-card class="pa-2">
          <div class="title mb-2">
            {{ $t('stations.stationInformation') }}
          </div>
          <v-form>
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field v-model="form.name" :label="$t('stations.websiteName')"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field v-model="form.region" :label="$t('stations.region')"></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-select
                  :items="status"
                  :item-value="this.$t('stations.'+form.status)"
                  :label="$t('general.status')"
                  v-model="form.status"
                  return-object
                ></v-select>
              </v-col>
<!--              <v-col cols="12" md="6">-->
<!--                <v-select-->
<!--                  :items="maps"-->
<!--                  :label="$t('stations.map')"-->
<!--                  v-model="defaultSelectedMaps"-->
<!--                ></v-select>-->
<!--              </v-col>-->
            </v-row>
            <div class="d-flex mt-3">
              <v-btn
                @click="editLocation"
                :loading="loading"
                :disabled="loading"
                color="primary"
              >
                {{ $t("general.save") }}
              </v-btn>
            </div>
          </v-form>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>

import {mapActions} from "vuex";

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
        text: this.$t('menu.stations'),
        to:'/settings/stations',
        exact: true
      }, {
        text: this.$t('stations.editStation')
      }],
      status:['active', 'notactive'],
      maps: [this.$t('stations.yes'), this.$t('stations.no')],
      defaultSelectedMaps: this.$t('stations.no'),
      form: { name: "", region: "", status: 'active'},
    }
  },
  watch: {

  },
  computed: {
    location: {
      get() {
        return this.$store.state.locations.location;
      },
      set(val) {
        this.$store.commit("locations/SET_LOCATION", val);
      }
    }
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t('stations.editStation')
    });
    this.refresh()
  },
  methods: {
    ...mapActions("locations", ["getLocation", "updateLocation"]),
    ...mapActions("app", ["setBreadCrumb"]),
    refresh() {
      const { id } = this.$route.params;
      this.loading = true;
      this.getLocation(id)
        .then(() => {
          this.loading = false;
          const { id, name, region, status } = this.location ?? {};
          this.form = { name, region, id, status};
        })
        .catch(() => {
          this.loading = false;
        });
    },
    editLocation() {
      this.loading = true;
      this.updateLocation({ _method: "PUT", ...this.form })
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "settings-stations" });
        })
        .catch(() => {
          this.loading = false;
        });
    },
  }
}
</script>

<style >
.ck-editor__editable.ck-content{
  min-height: 200px;
}
</style>
