<template>
  <div class="d-flex flex-column flex-grow-1">
    <div class="d-flex align-center pb-3">
      <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t('menu.pipesModel') }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

<!--      <div  class="h-38 d-flex">-->

<!--        <div class="date-picker position-relative mx-1">-->
<!--          <i aria-hidden="true" class="v-icon mdi mdi-calendar"></i>-->
<!--          <date-range-picker-->
<!--            v-model="dateRange"-->
<!--            direction='rtl'-->
<!--            @update="changeDatePicker()"-->
<!--          >-->
<!--          </date-range-picker>-->
<!--        </div>-->
<!--      </div>-->
    </div>

    <v-card>
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selectedEvents.length > 0" v-on="on">
                  {{ $t('general.actions') }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <v-list-item>
                <v-list-item-title>{{ $t('general.verify') }}</v-list-item-title>
              </v-list-item>
              <v-list-item>
                <v-list-item-title>{{ $t('general.disabled') }}</v-list-item-title>
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item>
                <v-list-item-title>{{ $t('general.delete') }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
        <v-col cols="6" class="d-flex text-right align-center">
          <v-text-field
            v-model="searchQuery"
            append-icon="mdi-magnify"
            class="flex-grow-1  mx-1"
            hide-details
            dense
            solo
            clearable
            :placeholder="$t('general.search')"
          ></v-text-field>
<!--          <v-btn-->
<!--            :disabled="items.length === 0"-->
<!--            class="primary&#45;&#45;text  v-btn&#45;&#45;active mx-1"-->
<!--            title="Notes"-->
<!--            @click=""-->
<!--          >-->
<!--            <v-icon>mdi-note-text-outline</v-icon>-->
<!--          </v-btn>-->
          <v-btn
            :loading="isLoading"
            icon
            small
            class="mx-1"
            @click.prevent="open()"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
          <v-btn
            icon
           small
           class="mx-1"
           :to="{ name: 'models-show' }">
            <v-icon>mdi-arrow-left</v-icon>
          </v-btn>
        </v-col>
      </v-row>

      <v-data-table
        v-model="selectedEvents"
        :headers="headers"
        :items="items"
        :options.sync="options"
        :search="searchQuery"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="total"
        :footer-props="{
          'items-per-page-options': [5, 10, 15]
        }"
      >
        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold" >{{ item.id }}</div>
        </template>

        <template v-slot:item.file="{ item }">
          <div class="font-weight-bold d-flex align-center text-no-wrap">
            <a href="#" @click.prevent="downloadFile(item.file)" download="true" target="_blank" :id="`file-${item.id}`" v-if="item.file">{{ $t('tables.openFile') }}</a>
          </div>
        </template>

        <template v-slot:no-data>
          <div class="text-center my-2 primary--text" color="primary">
            <emptyDataSvg></emptyDataSvg>
            <div class="dt-no_data">{{ $t("general.no_data_available") }}</div>
          </div>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import axios from '@/plugins/axios'
import moment from "moment";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  name: "Notes",
  components:{
    emptyDataSvg
  },
  data() {
    return {
      isLoading: false,
      page: 1,
      total: 0,
      numberOfPages: 0,
      options: {},
      breadcrumbs: [{
        text: this.$t('menu.pipesModel'),
        disabled: false,
        href: '#'
      }, {
        text: this.$t('menu.selectLocation'),
        disabled: false,
        href: '/models'
      },
        {
          text: '',
        }
      ],
      dialog: false,
      searchQuery: '',
      selectedEvents: [],
      headers: [
        { text: this.$t('tables.id'), align: 'start', value: 'id' },
        { text: this.$t('tables.notes'), sortable: false, value: 'notes' },
        { text: this.$t('tables.file'), sortable: false, value: 'file' },
      ],
      items: [],
      dateRange: {
        startDate: "",
        endDate: "",
      },
    }
  },
  watch: {
    options: {
      handler() {
        this.open();
      }
    },
    deep: true,
    searchQuery() {
      this.open()
    },
  },
  computed: {
    ...mapState('events', ['notes','locations']),
  },
  mounted() {
    this.fetchLocations()
    this.open();
  },
  methods:{
    ...mapActions('events', ['getNotes','getLocations']),
    downloadFile(file){
      window.open(file, '_blank');
    },
    open() {
      this.isLoading = true
      const { id } = this.$route.params;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? 'desc' : 'asc';
      let data = {
        search:this.searchQuery ?? '',
        pageSize:itemsPerPage,
        pageNumber:page,
        locationId: id,
        sortDirection:direction,
        sortColumn:this.options.sortBy[0] ?? '',
      }
      this.getNotes(data)
        .then(() => {
          this.isLoading = false
          if(itemsPerPage != -1) {
            this.items = this.notes.data
            this.total = this.notes.total
            this.numberOfPages = this.events.last_page
          }else{
            this.items = this.notes
            this.total = this.notes.length
            this.numberOfPages = 1
          }
        })
        .catch(() => {
          this.isLoading = false
        })
    },
    fetchLocations(){
      this.isLoading = true
      this.getLocations()
        .then(() => {
          this.isLoading = false
          let {id} = this.$route.params;
          // let selectedLocation = this.locations[id] || 'not_selected';
          let selectedLocation = this.locations.find((item) => item.id == id) || 'not_selected';
          this.breadcrumbs[2].text = this.$t(`breadcrumbs.${selectedLocation.name}`);
        })
        .catch(() => {
          this.isLoading = false
        })
    },
  }
}
</script>

<style scoped>

</style>
