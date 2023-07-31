<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-1">
      <div>
        <div class="display-1">{{ $t("users.editRole") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

    </div> -->

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <div class="title mb-2 d-flex justify-space-between">
            {{ $t("users.roleName") }}
            <v-btn icon @click="refresh()">
              <v-icon>mdi-refresh</v-icon>
            </v-btn>
          </div>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.name"
                outlined
                dense
                :label="$t('users.roleName')"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.display_name"
                :label="$t('users.nameOnScreen')"
                outlined
                dense
              ></v-text-field>
            </v-col>
          </v-row>
          <div>
            <div class="title mb-2 mt-2">{{ $t("users.roleDetails") }}</div>
            <div>
              <v-simple-table
                class="table align-middle table-row-dashed fs-6 gy-5"
              >
                <tbody>
                  <!--                <tr>-->
                  <!--                  <td class="text-primary text-center font-weight-bold">{{ $t('texts.administratorAccess') }}-->
                  <!--                    <i class="fas fa-exclamation-circle ms-1 fs-7"></i></td>-->
                  <!--                  <td>-->
                  <!--                    <v-checkbox-->
                  <!--                      class="mr-2"-->
                  <!--                      label="Select All"-->
                  <!--                    ></v-checkbox>-->
                  <!--                  </td>-->
                  <!--                </tr>-->
                  <tr v-for="(item, index) in permissions" :key="index">
                    <td class="text-primary text-center font-weight-bold">
                      {{ index }}
                    </td>
                    <td>
                      <div class="d-flex">
                        <v-checkbox
                          v-for="permission in item"
                          :key="permission.name"
                          class="mr-2 mb-0"
                          v-model="form.permissions"
                          :value="permission.name"
                          multiple
                          :label="permission.display_name"
                        ></v-checkbox>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </v-simple-table>
            </div>
          </div>
          <div class="d-flex">
            <v-btn
              @click="editRole"
              :loading="loading"
              :disabled="loading"
              color="primary"
            >
              {{ $t("general.save") }}
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "EditRolePage",
  components: {},
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("menu.usersManagement"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("users.rolesList"),
          to: "/roles/list",
          exact: true
        },
        {
          text: this.$t("users.editRole")
        }
      ],
      form: { name: "", display_name: "", permissions: [] },
      loading: false
    };
  },
  computed: { ...mapState("roles", ["role", "permissions"]) },
  created() {
    this.refresh();
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("users.rolesList")
    });
  },
  methods: {
    ...mapActions("roles", ["getRole", "updateRole"]),
    ...mapActions("app", ["setBreadCrumb"]),
    refresh() {
      const { id } = this.$route.params;
      this.loading = true;
      this.getRole(id)
        .then(() => {
          this.loading = false;
          const { name, display_name, permissions, id } = this.role ?? {};
          this.form = { name, display_name, permissions, id };
        })
        .catch(() => {
          this.loading = false;
        });
    },
    editRole() {
      this.loading = true;
      this.updateRole({ _method: "PUT", ...this.form })
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "roles-list" });
        })
        .catch(() => {
          this.loading = false;
        });
    }
  }
};
</script>
