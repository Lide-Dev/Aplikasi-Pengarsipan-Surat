<template>
  <navbar @toggle-clicked="toggleClicked" />

  <sidebar
    @sidebar-content-selected="sidebarSelected"
    :toggle="toggleSidebar"
    :selected-content="dyComponent.selectedContent.toString()"
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
  <div
    v-if="showPopover"
    @click="hidePopover"
    class="outside-popover"
  ></div>
  <div v-if="showPopover" class="popover" :style="realCoordinatePopover">
    Test
  </div>
</template>

<script>
import {
  ref,
  reactive,
  onMounted,
  watch,
  computed,
  onUpdated,
  provide,
  inject,
} from "vue";
import { provideConfig } from "../../components/ProvideHome";
import config from "../../constants/config";
import useBreakpoint from "../../Components/UseBreakpoint";

export default {
  components: config.componentsHome,
  props: {
    dyComponent: {
      type: Object,
    },
    errors: Object,
    // tableData: Object,
    // tableConfig: Object,
    user: Object,
  },
  setup(props, context) {
    // const propsDyComponent = reactive(props.dyComponent.props);
    const toggleSidebar = ref(false);
    const showPopover = ref(false);
    const coordinatePopover = reactive({
      top: 0,
      left: 0,
    });

    const realCoordinatePopover = computed(() => {
      let coordinate = { top: coordinatePopover.top };
      coordinatePopover.left === 0
        ? (coordinate.right = coordinatePopover.right)
        : (coordinate.left = coordinatePopover.left);
      return coordinate;
    });
    const title = computed(
      () => config.componentsTitleHome[props.dyComponent.name].title
    );

    const toggleClicked = () => {
      toggleSidebar.value = !toggleSidebar.value;
    };

    const sidebarSelected = () => {
      console.log("Emit of sidebar");
      showPopover.value = false;
      if (toggleSidebar.value) toggleSidebar.value = false;
    };

    const showPopoverSet = (val, obj) => {
      let coordinate = obj.coordinate;
      let maxWidth = useBreakpoint().width.value;
      coordinatePopover.top = coordinate.y;
      if (coordinate.x <= maxWidth / 2) {
        coordinatePopover.left = coordinate.x + 10;
      } else {
        coordinatePopover.left = coordinate.x - maxWidth / 2.83;
      }
      showPopover.value = val;
    };

    const hidePopover = () => {
      showPopover.value = false;
    };

    onUpdated(() => {
      console.log("UPDATED!");
    });

    provide("showPopover", showPopover);
    provide("showPopoverSet", showPopoverSet);

    return {
      toggleClicked,
      toggleSidebar,
      sidebarSelected,
      realCoordinatePopover,
      coordinatePopover,
      title,
      showPopover,
      hidePopover,
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
  min-height: 98vh;
  z-index: 0;
  overflow: hidden;
}

.popover {
  position: fixed;
  width: 35vw;
  height: 10vh;
  background-color: gray;
  z-index: 10;
}

.outside-popover {
  position: fixed;
  width: 100vw;
  top: 0;
  min-height: 100vh;
  /* background-color: blue; */
  /* pointer-events: none; */
  opacity: 0.4;
  z-index: 5;
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
    z-index: 1;
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
