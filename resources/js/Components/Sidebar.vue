<template>
  <div
    class="sidebar-container bg-primary"
    :class="{ active: toggle }"
    @mouseenter="sidebarHovered(true)"
    @mouseleave="sidebarHovered(false)"
  >
    <div class="columns brand-label">
      <img :src="logo" alt="Logo LLDIKTI" class="show-md" />
      <inertia-link href="/" class="navbar-brand mr-2 show-md"
        ><h5>SIPAS</h5>
        <span>LLDIKTI Wilayah XIV</span></inertia-link
      >
    </div>
    <div
      class="sidebar-content-outer"
      v-for="(content, index) in contents"
      :key="index"
    >
      <div @click="sidebarClicked(index)" :class="contentActive(index)">
        <inertia-link
          v-if="!content.hasChildren"
          :href="content.url"
          preserve-state
          class="columns sidebar-content-inner col-oneline"
        >
          <content-sidebar
            :content="content"
            :mouse-over="mouseOver"
          ></content-sidebar>
        </inertia-link>
        <div class="sidebar-content-inner" v-else>
          <content-sidebar
            :content="content"
            :mouse-over="mouseOver"
          ></content-sidebar>
        </div>
      </div>
      <div
        class="children-content"
        v-if="content.hasChildren"
        v-show="content.showChildren"
      >
        <div
          v-for="(children, index2) in content.children"
          :key="index2"
          @click="sidebarClicked(index2, index)"
          :class="contentActive(index2, index)"
        >
          <inertia-link
            v-if="!children.hasChildren"
            :href="children.url"
            preserve-state
            class="columns sidebar-content-inner col-oneline"
          >
            <content-sidebar
              :content="children"
              :mouse-over="mouseOver"
            ></content-sidebar>
          </inertia-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { PATH } from "../constants/path";
import Config from "../constants/config";
import { ref, reactive, watch } from "vue";
import ContentSidebar from "./ContentSidebar.vue";

export default {
  components: {
    ContentSidebar,
  },
  props: {
    toggle: {
      type: Boolean,
    },
    selectedContent: String,
  },
  setup(props, { emit }) {
    const mouseOver = ref(false);
    const selectedContent = ref(props.selectedContent);
    const reactProps = reactive(props);
    const logo = ref(PATH.logo);
    const contents = ref([
      {
        name: "Dashboard",
        icon: "ri-dashboard-fill",
        hasChildren: false,
        url: "/",
      },
      {
        name: "Simpan Arsip",
        icon: "ri-inbox-archive-fill",
        hasChildren: false,
        url: "/archives/create",
      },
      {
        name: "Kelola Surat",
        icon: "ri-mail-settings-fill",
        hasChildren: true,
        showChildren: false,
        children: [
          {
            name: "Surat Masuk",
            icon: "ri-mail-download-fill",
            url: "/archives/inbox",
            hasChildren: false,
          },
          {
            name: "Surat Keluar",
            icon: "ri-mail-send-fill",
            url: "/archives/sent",
            hasChildren: false,
          },
        ],
      },
      {
        name: "Data Pegawai",
        icon: "ri-account-circle-fill",
        hasChildren: false,
        url: "/employees",
      },
      {
        name: "Logout",
        icon: "ri-logout-circle-r-fill",
        hasChildren: false,
        customColor: Config.bgCustom.error,
        url: "/logout",
      },
    ]);

    watch(reactProps, (newProps, prevProps) => {
      if (newProps.toggle) {
        mouseOver.value = true;
      }
      if (newProps.selectedContent) {
        selectedContent.value = newProps.selectedContent;
      }
    });

    const contentActive = (index, indexParent = "-1") => {
      let content = contents.value[index];
      let icon = !content.hasChildren ? "btn " : "btn has-children ";
      if (content.customColor) {
        icon += "btn-" + content.customColor;
      } else {
        icon += "btn-primary";
      }
      if (indexParent.toString() ==="-1") {
        return index.toString() === selectedContent.value
          ? icon + " active"
          : icon;
      } else {
        return (indexParent * 10 + index).toString() === selectedContent.value
          ? icon + " active"
          : icon;
      }
    };

    const sidebarHovered = (status) => {
      if (!props.toggle) mouseOver.value = status;
      else mouseOver.value = true;
    };
    const sidebarClicked = (index, indexParent = -1) => {
      if (indexParent === -1) {
        let content = contents.value[index];
        if (content.hasChildren) {
          content.showChildren = !content.showChildren;
        }
      }
      if (props.toggle) {
        mouseOver.value = false;
        emit("SidebarContentSelected");
      }
    };

    return {
      contents,
      contentActive,
      sidebarHovered,
      sidebarClicked,
      mouseOver,
      logo,
    };
  },
};
</script>

<style scoped>
.fade-enter-active {
  transition: opacity 0.3s ease;
}
.fade-leave-active {
  transition: opacity 0.3s linear;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.children-content {
  width: 100%;
}

.btn {
  width: 100%;
  height: 60px;
  border: 0;
  padding: 0px;
  overflow-x: hidden;
}

.btn a {
  text-decoration: none;
}

.sidebar-container {
  position: fixed;
  transition-property: width;
  transition: ease-in-out;
  transition-duration: 300ms;
  top: 0;
  padding-top: 95px;
  height: 110vh;
  width: 70px;
  z-index: 2;
  overflow-y: auto;
}

.sidebar-container .sidebar-content-outer {
  display: flex;
  flex-direction: column;
  justify-items: center;
  align-items: center;
}

.btn .sidebar-content-inner {
  height: 100%;
}
.sidebar-content-inner {
  width: 100%;
  margin-left: 6px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow-x: hidden;
}

.btn:hover:not(.active) {
  background-color: #134172;
}

*:visited {
  opacity: 0;
}

.sidebar-container.active {
  left: 0px;
  width: 260px;
}

.sidebar-container::-webkit-scrollbar {
  width: 0.5em;
}

.sidebar-container::-webkit-scrollbar-thumb {
  background-color: #134172;
  outline: 1px solid #134172;
}

/* .sidebar-container::-webkit-scrollbar-track {
   box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
} */

@media only screen and (min-width: 840px) {
  .sidebar-container:hover {
    width: 260px;
  }
}

@media only screen and (max-width: 840px) {
  .brand-label {
    padding-left: 20px;
    margin-bottom: 20px;
  }
  .sidebar-container {
    left: -260px;
    width: 260px;
    /* overflow-y: scroll; */
  }
}
</style>
