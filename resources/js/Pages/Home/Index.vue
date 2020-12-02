<template>
  <navbar @toggle-clicked="toggleClicked" />

  <sidebar
    @sidebar-content-selected="sidebarSelected"
    :toggle="toggleSidebar"
    :selected-content="dyComponent.selectedContent.toString()"
    class=""
  />
  <div class="content bg-gray">
    <component :is="dyComponent.name"></component>
  </div>
</template>

<script>
import {
  ref,
  reactive,
  onMounted,
  watch,
  toRefs,
  computed,
  onUpdated,
} from "vue";
import conf from "../../constants/config";

export default {
  components: conf.componentsHome,
  props: {
    dyComponent: {
      type: Object,
    },
  },
  setup(props, context) {
    const toggleSidebar = ref(false);

    const toggleClicked = () => {
      toggleSidebar.value = !toggleSidebar.value;
    };

    const sidebarSelected = () => {
      if (toggleSidebar.value) toggleSidebar.value = false;
    };

    onUpdated(() => {
      console.log("UPDATED!");
    });

    return { toggleClicked, toggleSidebar, sidebarSelected };
  },
};
</script>

<style>
.content {
  position: absolute;
  top: 65px;
  padding-top: 10px;
  padding-left: 65px;
  padding-right: 15px;
  width: 100vw;
  z-index: 0;
  overflow: hidden;
}

.isolation {
  display: fixed;
  opacity: 0.5;
  background: grey;
  z-index: 1;
  width: 100vh;
  height: 100vh;
  overflow: hidden;
}

@media only screen and (max-width: 840px) {
  .content {
    padding-left: 15px;
  }
}
</style>
