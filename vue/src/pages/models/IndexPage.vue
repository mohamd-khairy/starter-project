<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center pb-3">
      <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t("menu.pipesModel") }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
    </div> -->

    <v-row>
      <v-col v-for="location in locations" :key="location.id" cols="3">
        <v-hover v-slot="{ hover }">
          <v-card
            :class="{ 'on-hover': hover }"
            @click="openModels(location.id)"
            style="overflow: hidden;"
          >
            <v-card-title class=" ">
              <v-avatar
                rounded
                class="me-2 v-avatar--variant-tonal  primary--text"
                size="56"
                ><v-icon>mdi-map-marker </v-icon></v-avatar
              >
              <div class="title-cont ">
                <h6 class="text-caption">Location</h6>
                {{ location.name }}
              </div>
            </v-card-title>

            <v-sheet v-if="hover" class="overlay" color="rgba(0, 0, 0, 0.5)">
              <v-icon class="link-icon" size="48" color="white">
                <!-- mdi-link-variant -->
                mdi-open-in-new
              </v-icon>
            </v-sheet>
          </v-card>
        </v-hover>
      </v-col>
    </v-row>
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
          text: this.$t("menu.pipesModel"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.selectLocation")
        }
      ],
      x: 0,
      y: 0
    };
  },
  computed: {
    ...mapState("events", ["locations"])
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.pipesModel")
    });
  },
  mounted() {
    this.open();
    document.getElementsByClassName("path").forEach(path => {
      path.addEventListener("mouseenter", e =>
        this.mouseEnterFunction(e, path)
      );
    });

    document.getElementsByClassName("path").forEach(path => {
      path.addEventListener("mouseleave", this.mouseLeaveFunction);
    });
    let x;
    let y;

    document.getElementsByClassName("path").forEach(path => {
      path.addEventListener("mousemove", this.mouseMoveFunction);
    });
  },
  unmounted() {
    document.getElementsByClassName("path").forEach(path => {
      path.removeEventListener("mouseenter", e =>
        this.mouseEnterFunction(e, path)
      );
    });
    document.getElementsByClassName("path").forEach(path => {
      path.removeEventListener("mouseleave", this.mouseLeaveFunction);
    });
    document.getElementsByClassName("path").forEach(path => {
      path.removeEventListener("mousemove", this.mouseMoveFunction);
    });
  },
  methods: {
    ...mapActions("events", ["getLocations"]),
    ...mapActions("app", ["setBreadCrumb"]),
    searchUser() {},
    open() {
      this.isLoading = true;
      this.getLocations()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    openModels(id) {
      this.$router.push("/models/show/" + id);
    },
    mouseEnterFunction(e, path) {
      // console.log("mouseEnterEv");
      const rel = path.getAttribute("class");
      const title = path.getAttribute("title");

      document
        .getElementsByClassName("RegionsNameInMap")
        .forEach(elm => (elm.style.display = "block"));
      document.getElementsByClassName("RegionsNameInMap").innerHTML = title;
    },
    mouseLeaveFunction() {
      document.getElementsByClassName("RegionsNameInMap").forEach(elm => {
        elm.innerHTML = "";
        elm.style.display = "none";
      });
    },
    mouseMoveFunction(event) {
      this.x = event.clientX; // Get the horizontal coordinate
      this.y = event.clientY; // Get the vertical coordinate

      document.getElementsByClassName("RegionsNameInMap").forEach(element => {
        element.style.left = this.x + 10 + "px";
        element.style.top = this.y + 10 + "px";
      });
    }
  }
};
</script>

<style lang="scss" scoped>
.animated-card {
  background-color: #e0f2f8;
  transition: all 0.2s ease-in-out;
}
.animated-card:hover {
  transform: scale(1.1);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.reg-img path {
  cursor: pointer;
  fill: #e1e1e1;
  stroke: #898989;
}

.reg-img path:hover {
  fill: #777676;
  cursor: pointer;
}

.reg-img .pathDis {
  pointer-events: none;
  cursor: default;
}

.RegionsNameInMap {
  position: fixed;
  z-index: 12;
  color: #ffffff;
  background: #034d86;
  padding: 2px 5px;
  font-size: 12px;
  border-radius: 5px;
  display: none;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.link-icon {
  transition: transform 0.3s ease-in-out;
}

.overlay:hover .link-icon {
  transform: scale(1.1);
}

////
</style>
