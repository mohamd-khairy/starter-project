<template>
  <v-row justify="center">
    <v-dialog
      v-model="dialog"
      max-width="500"
    >
      <v-card>
        <v-card-title class="text-h5 d-flex justify-space-between align-center border-bottom">
          {{ $t('general.confirmationLog')}}
          <v-btn icon @click="dialog = false">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text class="mt-4">
          <v-row>

            <v-col cols="12">
              <v-select
                :items="translatedStatus"
                :label="$t('general.status')"
                :item-text="item => item.text"
                :item-value="item => item.value"
                hide-details
                solo
                v-model="form.status"
                return-object
              >
              </v-select>
            </v-col>
            <v-col cols="12">
              <v-file-input
              solo
                :label="$t('general.uploadFile')"
                v-model="form.file"
              ></v-file-input>
            </v-col>
            <v-col cols="12">
              <v-textarea
                :label="$t('general.note')"
                value=""
                v-model="form.notes"
                solo
              ></v-textarea>

            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="py-2">

          <v-spacer></v-spacer>

          <v-btn
            color="primary"
            class="mx-2"
            @click="updateEventMethod"
          >
            {{ $t('general.save') }}
          </v-btn>

          <v-btn
            color="grey lighten-3"

            @click="dialog = false"
          >
            {{ $t('general.cancel') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import {mapActions, mapState} from "vuex";
import {makeToast} from "@/helpers";

export default {
  props: {
    value: Boolean,
    eventItem: Object,
    id: Number,
    statuses: Array,
  },
  data() {
    return {
      status:[
        {key:'error', value:0},
        {key:'confirmed',value:1},
        {key:'pending', value:2}
      ],
      form: {status:null, notes: "", file:null },
    }
  },
  computed: {
    dialog: {
      get() {
        return this.value
      },
      set(value) {
        this.$emit('input', value)
      }
    },
    event: {
      get() {
        return this.$store.state.events.event;
      },
      set(val) {
        this.$store.commit("events/SET_EVENT", val);
      }
    },
    translatedStatus(){
      return this.statuses.map((option) => {
      return {
        key: option.key,
        text: this.$t('general.'+ option.key),
        value: option.value
      };
      });
    }
  },
  watch:{
    'id'(){
      this.refresh()
    }
  },
  methods:{
    ...mapActions('events', ['updateEvent']),
    refresh() {
      this.loading = true;
      this.event = this.eventItem
      const {status, notes, id } = this.eventItem ?? {};
      this.form = { status,notes, id };
    },
    fetchData: function(){
      this.$root.$emit('table_component')
    },
    updateEventMethod() {
      this.loading = true;
      this.errors = {};
      let formData = new FormData();
      formData.append("file", this.form.file ?? null);
      formData.append("notes", this.form.notes ?? '');
      formData.append("status", this.form.status.value);
      this.updateEvent(formData)
        .then((response) => {
          this.loading = false;
          this.dialog = false
          this.fetchData()
          this.errors = {};
          makeToast("success", response.data.message);
        })
        .catch(error => {
          this.loading = false;
          // if (error.response.status == 422) {
          //   const { errors } = error?.response?.data;
          //   this.errors = errors ?? {};
          // }
        });
    }
  }
}
</script>
