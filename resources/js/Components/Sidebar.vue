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
      v-for="content in contents"
      :key="content.id"
    >
      <div
        @click="sidebarClicked(content.id)"
        :class="contentActive(content.id)"
      >
        <!-- (content.id === !selectedContent && selfControlNav) = terpilih dan tidak bisa ngontrol navigasi -->
        <!-- (content.id !== selectedContent) = tidak terpilih -->
        <!-- Dashboard 0  -->
        <div
          class="sidebar-content-inner"
          v-if="
            content.hasChildren ||
            (content.id == selectedContent && !selfControlNav)
          "
        >
          <content-sidebar
            :content="content"
            :mouse-over="mouseOver"
          ></content-sidebar>
        </div>
        <inertia-link
          v-else
          :href="content.url"
          preserve-state
          replace
          class="columns sidebar-content-inner col-oneline"
        >
          <content-sidebar
            :content="content"
            :mouse-over="mouseOver"
          ></content-sidebar>
        </inertia-link>
      </div>
      <div
        class="children-content"
        v-if="content.hasChildren"
        v-show="content.showChildren"
      >
        <div class="divider"></div>
        <div
          v-for="children in content.children"
          :key="children.id"
          @click="sidebarClicked(children.id, content.id)"
          :class="contentActive(children.id, content.id)"
        >
          <div
            class="sidebar-content-inner"
            v-if="
              children.hasChildren ||
              (content.id * 10 + children.id == selectedContent &&
                !selfControlNav)
            "
          >
            <content-sidebar
              :content="children"
              :mouse-over="mouseOver"
            ></content-sidebar>
          </div>
          <inertia-link
            v-else
            :href="children.url"
            preserve-state
            replace
            class="columns sidebar-content-inner col-oneline"
          >
            <content-sidebar
              :content="children"
              :mouse-over="mouseOver"
            ></content-sidebar>
          </inertia-link>
        </div>
        <div class="divider"></div>
      </div>
    </div>
    <div class="sidebar-bottom"></div>
  </div>
</template>

<script>
import { Path } from "../constants/path";
import { ref, reactive, watch, onMounted } from "vue";
import ContentSidebar from "./ContentSidebar.vue";
import config from "../constants/config";

export default {
  components: {
    ContentSidebar,
  },
  props: {
    toggle: {
      type: Boolean,
    },
    selectedContent: String,
    selfControlNav: { type: Boolean, default: false },
  },
  emits: ["sidebarContentSelected"],
  setup(props, { emit }) {
    // console.log(config.contentsHome);
    const mouseOver = ref(false);
    const selectedContent = ref(props.selectedContent);
    const reactProps = reactive(props);
    const logo = Path.logo;
    const contents = ref(config.contentsHome);

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
      if (indexParent.toString() === "-1") {
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
      console.log(
        "Sidebar Clicked at index: ",
        index,
        " and indexParent: ",
        indexParent
      );
      let content = contents.value[index];
      if (indexParent === -1) {
        if (content.hasChildren) {
          content.showChildren = !content.showChildren;
        }
      }
      if (
        props.toggle &&
        ((indexParent === -1 && !content.hasChildren) || indexParent > -1)
      ) {
        mouseOver.value = false;
        emit("sidebarContentSelected", true);
        // let title = indexParent === -1 ? content.title : contents.value[indexParent].children[index].title
      } else {
        emit("sidebarContentSelected", false);
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
  /* border-bottom: 2px #eef0f4 solid; */
  /* border-top: 2px #eef0f4 solid; */
}

.divider {
  margin: 5px 15px;
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

.sidebar-bottom {
  position: sticky;
  height: 10%;
  width: 100%;
  background-image: linear-gradient(
    to bottom,
    rgba(24, 76, 143, 0),
    rgba(24, 76, 143, 1) 80%
  );
  pointer-events: none;
  bottom: 0;
}

.sidebar-container {
  position: fixed;
  transition-property: width;
  transition: ease-in-out;
  transition-duration: 300ms;
  top: 0;
  padding-top: 95px;
  height: 100vh;
  width: 70px;
  z-index: 6;
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
    overflow-x: hidden;
    left: -260px;
    width: 260px;
    height: 102%;
    /* overflow-y: scroll; */
  }
}
</style>
