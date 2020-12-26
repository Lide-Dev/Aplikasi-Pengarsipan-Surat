<template>
  <div class="popover-container">
    <div v-if="visibility" @click="hidePopover" class="outside-popover"></div>
    <transition name="fade">
      <div v-if="visibility" class="popover" :style="coordinatePopover">
        <button
          v-for="content in contents"
          class="btn btn-primary d-block"
          @click="onClickPopoverContent(content)"
          :key="content.id"
        >
          <div class="columns">
            <div class="column col-3"><i :class="content.icon"></i></div>
            <div class="column col-9 text-left">
              <span>{{ content.name }}</span>
            </div>
          </div>
        </button>
      </div>
    </transition>
  </div>
</template>

<script>
import { computed, inject, reactive, ref, toRefs, watch } from "vue";
import UseBreakpoint from "./UseBreakpoint";
/**
 * Handle the popover at this component using Provide and Inject API.
 * The Provide function in this component, namely:
 *
 * - Get value of popover state
 * @inject `getPopoverState()` -
 *
 * - Setting popover for visibility and the content.
 * @inject `setPopoverState(val,obj)`
 * @param {Boolean} val - State of visibility popover.
 * @param {Object} obj - Content of popover.
 *
 * - Setting popover coordinate by this function.
 * @inject `setPopoverCoordinate(coordinate)`
 * @param {Object} coordinate - State of coordinate popover.
 */
export default {
  props: {
    visibility: { type: Boolean, default: false },
    coordinate: { type: Object, default: { x: 0, y: 0 } },
    content: Object,
    closeButton: Boolean,
  },
  setup(props) {
    const options = reactive({
      contents: props.content,
      closeButton: props.closeButton,
    });
    /**/

    const coordinatePopover = computed(() => {
      let coordinate = { top: props.coordinate.y - 30 };
      let maxWidth = UseBreakpoint().width.value;
      console.log(maxWidth, props.coordinate.x);
      if (props.coordinate.x <= maxWidth / 2) {
        coordinate.left = props.coordinate.x + 10;
      } else {
        coordinate.right = maxWidth - props.coordinate.x;
      }
      return coordinate;
    });

    const setPopoverState = inject("setPopoverState");

    const onClickPopoverContent = (content) => {
      console.log("Click content popover: ", content);
      setPopoverState(false);
      content.callback();
    };

    function hidePopover() {
      console.log("Hide popover");
      setPopoverState(false);
    }

    watch(props, (newProps, prevProps) => {
    //   setPopoverState(newProps.visibility);
      options.contents = newProps.content;
      options.closeButton = newProps.closeButton;
    });

    return {
      coordinatePopover,
      ...toRefs(options),
      setPopoverState,
      hidePopover,
      onClickPopoverContent,
    };
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.popover {
  position: fixed;
  min-width: 17vw;
  min-height: 10vh;
  z-index: 10;
  background-color: #184c8f;
  /* border: 1px #113a6e solid; */
}

.btn {
  width: 100%;
  min-height: 50px;
  padding-right: 25px;
  padding-left: 25px;
}

.outside-popover {
  position: fixed;
  width: 100vw;
  top: 0;
  min-height: 100vh;
  z-index: 2;
}

@media only screen and (max-width: 840px) {
  .popover {
    position: fixed;
    min-width: 45vw;
    min-height: 10vh;
    z-index: 10;
    background-color: #184c8f;
    /* border: 1px #113a6e solid; */
  }

  .btn {
    width: 100%;
    min-height: 60px;
  }
}
</style>
