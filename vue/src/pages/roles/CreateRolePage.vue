<template>
  <div class="flex-grow-1">
    <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("users.createNewRole") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <!-- <v-btn icon>
        <v-icon>mdi-refresh</v-icon>
      </v-btn> -->
    </div>

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <div class="title mb-2">
            {{ $t("users.roleName") }}
          </div>
          <v-row>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.name"
                :label="$t('users.roleName')"
                :error-messages="errors['name']"
                outlined
                dense
              />
            </v-col>
            <v-col cols="12" md="6">
              <v-text-field
                v-model="form.display_name"
                :error-messages="errors['display_name']"
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
                  <tr v-for="(item, index) in permissions" :key="index">
                    <td class="text-primary text-center font-weight-bold">
                      {{ index }}
                    </td>
                    <td>
                      <div class="d-flex">
                        <v-checkbox
                          v-for="permission in item"
                          class="mr-2 mb-0"
                          :key="permission.name"
                          v-model="form.permissions"
                          :value="permission.name"
                          multiple
                          :error-messages="errors['permissions']"
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
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="createRole()"
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
          text: this.$t("users.createRole")
        }
      ],
      loading: false,
      form: {
        name: "",
        display_name: "",
        permissions: []
      },
      errors: {}
    };
  },
  computed: {
    ...mapState("roles", ["permissions"])
  },
  created() {
    this.loading = true;
    this.getPermissions()
      .then(() => {
        this.loading = false;
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    ...mapActions("roles", ["getPermissions", "storeRole"]),
    createRole() {
      this.loading = true;
      this.errors = {};
      this.storeRole(this.form)
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "roles-list" });
        })
        .catch(error => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data ?? {};
            this.errors = errors ?? {};
          }
        });
    }
  }
};
</script>
