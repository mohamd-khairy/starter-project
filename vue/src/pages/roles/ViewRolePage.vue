<template>
  <div class="d-flex flex-column flex-grow-1">
    <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("users.rolesList") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn color="primary" :to="{ name: 'roles-create' }">
        {{ $t("users.createRole") }}
      </v-btn>
    </div>

    <v-row v-if="role.id">
      <v-col cols="12" lg="3">
        <v-card class="mx-auto" :loading="isLoading">
          <v-card-text class="px-3 pt-3">
            <div class="title font-weight-bold text--primary">
              {{ role.display_name | capitalize }}
            </div>
            <p></p>
            <!-- <p>{{ $t("texts.totalUsersWithThisRole") }}: 5</p> -->
            <div class="d-flex flex-column">
              <div
                class="d-flex align-center mb-1 text--primary"
                :key="index"
                v-for="(item, index) in role.permissions"
              >
                <span class="bullet bg-primary mx-1"></span>
                {{ item.replace("-", " ") | uppercase }}
              </div>
            </div>
          </v-card-text>
          <v-card-actions class="px-3 pb-3">
            <v-btn
              color="dark"
              :to="{ name: 'roles-edit', params: { id: role.id } }"
            >
              {{ $t("users.editRole") }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" lg="9">
        <v-card :loading="isLoading">
          <v-row dense class="pa-2 align-center">
            <v-col cols="12">
              <div class="title">
                {{ $t("texts.usersAssigned") }} <small>(24)</small>
              </div>
            </v-col>
            <v-col cols="6">
              <v-menu offset-y left>
                <template v-slot:activator="{ on }">
                  <transition name="slide-fade" mode="out-in">
                    <v-btn v-show="selectedRoles.length > 0" v-on="on">
                      Actions
                      <v-icon right>mdi-menu-down</v-icon>
                    </v-btn>
                  </transition>
                </template>
                <v-list dense>
                  <v-list-item>
                    <v-list-item-title>Verify</v-list-item-title>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-title>Disable</v-list-item-title>
                  </v-list-item>
                  <v-divider></v-divider>
                  <v-list-item>
                    <v-list-item-title>Delete</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>
            <v-col cols="6" class="d-flex text-right align-center">
              <v-text-field
                v-model="searchQuery"
                append-icon="mdi-magnify"
                class="flex-grow-1 mr-md-2"
                solo
                hide-details
                dense
                clearable
                :placeholder="$t('general.search')"
                @keyup.enter="searchRole(searchQuery)"
              ></v-text-field>
              <v-btn :loading="isLoading" icon small class="ml-2">
                <v-icon>mdi-refresh</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-data-table
            v-model="selectedRoles"
            show-select
            :headers="headers"
            :items="roles"
            :search="searchQuery"
            class="flex-grow-1"
            :loading="isLoading"
          >
            <template v-slot:item.id="{ item }">
              <div class="font-weight-bold">
                #
                <copy-label :text="item.id + ''" />
              </div>
            </template>

            <template v-slot:item.email="{ item }">
              <div class="d-flex align-center py-1">
                <v-avatar size="32" class="elevation-1 grey lighten-3">
                  <v-img :src="item.avatar" />
                </v-avatar>
                <div class="ml-1 caption font-weight-bold">
                  <copy-label :text="item.email" />
                </div>
              </div>
            </template>

            <template v-slot:item.verified="{ item }">
              <v-icon v-if="item.verified" small color="success">
                mdi-check-circle
              </v-icon>
              <v-icon v-else small> mdi-circle-outline </v-icon>
            </template>

            <template v-slot:item.disabled="{ item }">
              <div>{{ item.disabled.toString() | capitalize }}</div>
            </template>

            <template v-slot:item.role="{ item }">
              <v-chip
                label
                small
                class="font-weight-bold"
                :color="item.role === 'ADMIN' ? 'primary' : undefined"
              >{{ item.role | capitalize }}
              </v-chip>
            </template>

            <template v-slot:item.created="{ item }">
              <div>{{ item.created | formatDate("ll") }}</div>
            </template>

            <template v-slot:item.lastSignIn="{ item }">
              <div>{{ item.lastSignIn | formatDate("lll") }}</div>
            </template>

            <template v-slot:item.action="{}">
              <div class="actions">
                <v-btn icon to="/users/edit">
                  <v-icon>mdi-open-in-new</v-icon>
                </v-btn>
              </div>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import roles from "./content/roles";
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
export default {
  components: {
    CopyLabel,
  },
  data() {
    return {
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.usersManagement"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("users.rolesList"),
          to: "/roles/list",
          exact: true,
        },
        {
          text: this.$t("users.viewRole"),
        },
      ],

      searchQuery: "",
      selectedRoles: [],
      headers: [
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("tables.email"), value: "email" },
        { text: this.$t("tables.verified"), value: "verified" },
        { text: this.$t("tables.name"), value: "name" },
        { text: this.$t("tables.role"), value: "role" },
        { text: this.$t("tables.created"), value: "created" },
        { text: this.$t("tables.lastSignIn"), value: "lastSignIn" },
        // { text: this.$t("tables.disabled"), value: "disabled" },
        // { text: "", sortable: false, align: "right", value: "action" },
      ],

      roles,
    };
  },
  computed: { ...mapState("roles", ["role"]) },
  watch: {
    selectedRoles(val) {},
  },
  created() {
    this.open();
  },
  methods: {
    searchRole() {},
    ...mapActions("roles", ["getRole"]),

    open() {
      this.isLoading = true;
      const { id } = this.$route.params;

      this.getRole(id)
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
