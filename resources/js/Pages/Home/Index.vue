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
    <div @click="sidebarSelected" :class="{isolation:toggleSidebar}"></div>
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
import { provideConfig } from "../../components/ProvideHome";
import config from "../../constants/config";

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
    const title = computed(()=>config.componentsTitleHome[props.dyComponent.name].title);
    const toggleClicked = () => {
      toggleSidebar.value = !toggleSidebar.value;
    };

    const sidebarSelected = () => {
      console.log("Emit of sidebar");
      if (toggleSidebar.value) toggleSidebar.value = false;
    };

    onUpdated(() => {
      console.log("UPDATED!");
    });

    return { toggleClicked, toggleSidebar, sidebarSelected, title };
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
}
</style>
