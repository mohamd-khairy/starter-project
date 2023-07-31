<template>
  <v-menu offset-y left transition="slide-y-transition">
    <template v-slot:activator="{ on }">
      <v-btn @click="onMenuOpened" icon v-on="on">
        <v-icon>mdi-bell-outline</v-icon>
        <v-badge
          v-if="notifications.count"
          bordered
          :content="notifications.count"
          offset-x="10"
          offset-y="-2"
        >
        </v-badge>
      </v-btn>
    </template>

    <!-- dropdown card -->
    <v-card>
      <v-list three-line dense max-width="400">
        <v-subheader class="pa-3 text-h5 ">{{
          $t("settings.notifications")
        }}</v-subheader>
        <v-divider></v-divider>
        <div
          class="notifications-container"
          ref="notificationsCont"
          @scroll="scrollFn"
        >
          <div v-for="(notification, index) in notifications.data" :key="index">
            <v-divider
              v-if="index > 0 && index < notifications.data.length"
              inset
            ></v-divider>

            <v-list-item
              @click="goToNotification(notification)"
              :class="!notification.read_at ? 'unread-notification' : ''"
            >
              <v-list-item-avatar
                size="32"
                :color="notification.data.color || 'primary'"
              >
                <v-icon dark small>{{
                  notification.data.icon || "mdi-access-point"
                }}</v-icon>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>{{
                  notification.data.title || ""
                }}</v-list-item-title>
                <v-list-item-subtitle class="caption">{{
                  notification.data.subTitle || ""
                }}</v-list-item-subtitle>
                <v-list-item-subtitle class="date-cont">
                  <div class="date">
                    <v-icon small>
                      mdi-calendar-range
                    </v-icon>
                    {{ formatDateTime(notification.created_at).date }}
                  </div>
                  <div class="time">
                    <v-icon small>
                      mdi-clock
                    </v-icon>
                    {{ formatDateTime(notification.created_at).time }}
                  </div>
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </div>
          <div class="d-flex justify-center py-1">
            <v-progress-circular
              v-if="loading"
              :size="32"
              color="primary"
              indeterminate
            />
          </div>
        </div>
      </v-list>

      <div class="text-center py-2">
        <v-btn small>See all</v-btn>
      </div>
    </v-card>
  </v-menu>
</template>

<script>
import notificationSound from "@/assets/sounds/notification-sound.wav";
import moment from "moment";
import { mapActions, mapState } from "vuex";
/*
|---------------------------------------------------------------------
| Toolbar Notifications Component
|---------------------------------------------------------------------
|
| Quickmenu to check out notifications
|
*/
export default {
  data() {
    return {
      menuOpened: false,
      loading: false
    };
  },

  created() {
    this.getNotifications();
    this.initNotificationEcho();
  },
  computed: {
    ...mapState("app", ["notifications"]),
    ...mapState("auth", ["user"])
  },
  methods: {
    ...mapActions("app", [
      "getNotifications",
      "loadMoreNotifications",
      "notificationCounter",
      "addNotification",
      "readNotifications"
    ]),

    playSound() {
      let sound = new Audio(notificationSound);
      sound.play();
    },
    initNotificationEcho() {
      console.log(this.$echo);
      // this.$echo.connect();
      let { id } = this.user;

      if (!id) return;
      this.$echo.channel(`message.${id}`).listen(`.MessageEvent`, data => {
        const { data: notificationData } = data;
        console.log("Notification");
        console.log(notificationData);
        // const date = new Date();
        // const isoString = date.toISOString();
        // const formattedDate = isoString.slice(0, -1) + '000000Z';

        // let newNotification = {
        //   created_at: formattedDate,
        //   read_at: null,
        //   data: {
        //     title: notificationData.title,
        //   color: 'primary',
        //   icon: 'mdi-access-point',
        //   subtitle: notificationData.title,
        //   link: notificationData.link,
        //   eventId: notificationData.event_id
        //   }
        // }
        this.$toast.open({
          message: notificationData.data.subTitle,
          type: "warning",
          position: "bottom-right"
        });
        this.playSound();
        this.addNotification(notificationData);
      });
    },
    goToNotification(notification) {
      let link = notification.data.link;
      let id = notification.id;
      this.readNotifications([id]).then(_ => {
        if (link !== this.$route.fullPath) {
          this.$router.push(link);
        }
      });

      // console.log(notification);
    },

    scrollFn(ev) {
      if (
        !this.loading &&
        ev.target.scrollTop + ev.target.clientHeight + 10 >=
          ev.target.scrollHeight
      ) {
        if (this.notifications.moreItems) {
          let pageNum = this.notifications.current_page + 1;
          this.loading = true;
          this.loadMoreNotifications(pageNum)
            .then(_ => {})
            .catch(error => console.log(error))
            .finally(_ => (this.loading = false));
        }
      }
    },
    onMenuOpened() {
      // console.log("menu Opened");
      this.notificationCounter();
    },

    formatDateTime(data) {
      let date = moment(data).format("YYYY-MM-DD");
      let time = moment(data).format("hh:mm:ss A");
      return { date, time };
    }
  }
};
</script>
<style>
.notifications-container {
  max-height: 400px;
  overflow-y: auto;
  overscroll-behavior: contain;
}

.date-cont {
  display: flex !important;
  justify-content: space-between;
  align-items: center;
}
</style>
