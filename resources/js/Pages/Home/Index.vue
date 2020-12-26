<template>
  <navbar @toggle-clicked="toggleClicked" />

  <sidebar
    @sidebar-content-selected="sidebarSelected"
    :toggle="toggleSidebar"
    :selected-content="dyComponent.selectedContent.toString()"
    :self-control-nav="dyComponent.selfControlNavigation"
    class=""
  />
  <div class="content">
    <component
      :is="dyComponent.name"
      :dy-props="dyComponent.props"
      :title="title"
    ></component>
    <transition name="fade">
      <div
        v-if="toggleSidebar"
        @click="sidebarSelected"
        class="isolation"
      ></div>
    </transition>
  </div>
  <popover
    :visibility="popoverState"
    :coordinate="popoverCoordinate"
    :content="popoverContents"
  ></popover>
</template>

<script>
import { ref, reactive, computed, onUpdated, provide } from "vue";
import config from "../../constants/config";
import useBreakpoint from "../../Components/UseBreakpoint";

export default {
  components: config.componentsHome,
  props: {
    dyComponent: {
      type: Object,
    },
    errors: Object,
    toast: String,
    selfControlNavigation: { type: Boolean, default: false },
    user: Object,
  },
  setup(props, context) {
    // const propsDyComponent = reactive(props.dyComponent.props);
    const popoverState = ref(false);
    const popoverCoordinate = reactive({ x: 0, y: 0 });
    const popoverContents = ref([]);
    const toggleSidebar = ref(false);

    const getPopoverState = computed(() => popoverState.value);
    const title = computed(
      () => config.componentsTitleHome[props.dyComponent.name].title
    );

    function toggleClicked() {
      console.log("Toggle Sidebar Clicked.");
      setPopoverState(false);
      toggleSidebar.value = !toggleSidebar.value;
    }

    function sidebarSelected(isContent = false) {
      console.log("Emit of sidebar", isContent);
      setPopoverState(false);
      if (toggleSidebar.value && isContent) toggleSidebar.value = false;
    }

    function setPopoverCoordinate(coordinate = { x: 0, y: 0 }) {
      popoverCoordinate.x = coordinate.x;
      popoverCoordinate.y = coordinate.y;
    }

    function setPopoverState(value) {
      console.log("Popover being set to ", value);
      if (typeof value === "boolean") popoverState.value = value;
      else {
        popoverContents.value = value;
      }
    }

    provide("getPopoverState", getPopoverState);
    provide("setPopoverState", setPopoverState);
    provide("setPopoverCoordinate", setPopoverCoordinate);

    onUpdated(() => {
      console.log("UPDATED!");
    });

    return {
      popoverContents,
      popoverCoordinate,
      popoverState,
      sidebarSelected,
      title,
      toggleClicked,
      toggleSidebar,
    };
  },
};
</script>

<style>
.content {
  position: absolute;
  top: 65px;
  padding-top: 20px;
  padding-left: 75px;
  padding-right: 20px;
  width: 100vw;
  min-height: 80vh;
  z-index: 0;
  overflow: hidden;
}

@media only screen and (max-width: 840px) {
  .content {
    padding-left: 10px;
  }

  .isolation {
    top: 0;
    left: -10;
    position: fixed;
    opacity: 0.5;
    background: grey;
    z-index: 5;
    width: 110vw;
    min-height: 100vh;
    overflow: hidden;
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
}
</style>
