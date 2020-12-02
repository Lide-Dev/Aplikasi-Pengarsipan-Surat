<template>
  <div
    class="column col-4 icon"
    :class="{ 'has-children': content.hasChildren }"
  >
    <i v-show="content.hasChildren" :class="iconToggleChildren"></i>
    <span :class="iconLarger"></span>
  </div>

  <div class="column col-8">
    <transition name="fade">
      <span v-show="mouseOver" class="text-left">{{ content.name }}</span>
    </transition>
  </div>
</template>

<script>
import { computed } from "vue";
export default {
  props: {
    content: {
      type: Object,
      required: true,
    },
    mouseOver: {
      type: Boolean,
      required: true,
    },
  },
  setup(props) {
    const iconLarger = computed(() => props.content.icon + " ri-xl");
    const iconToggleChildren = computed(()=>props.content.showChildren?"ri-arrow-down-s-fill":"ri-arrow-right-s-fill")
    return { iconLarger, iconToggleChildren };
  },
};
</script>

<style scoped>
.column {
  padding: 25px 0px 25px 0;
  height: 100%;
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.column {
  color: #fff
}


.column:not(.has-children) span {
  margin-left: 16px;
}

.column:not(.icon) {
  margin-right: 0px;
  justify-content: flex-start;
}
</style>