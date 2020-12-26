<template>
  <div class="columns centered">
    <div v-if="canBackNav" class="column col-auto">
      <button @click="onClickNav" class="btn-nav text-primary">
        <i class="ri-arrow-left-s-line ri-2x"></i>
      </button>
    </div>
    <div class="column col-auto">
      <h4 class="text-primary">{{ titleModified }}</h4>
    </div>
    <slot></slot>
  </div>
  <div class="divider"></div>
</template>

<script>
import { computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
export default {
  props: {
    title: String,
    reusableNamePage: { type: String, default: "" },
    canBackNav: { type: Boolean, default: false },
    urlBackNav: { type: String, default: "" },
    dataBackNav: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  setup(props) {
    const titleModified = computed(() => {
      let resolveName = "";
      if (!props.reusableNamePage == "") {
        resolveName =
          props.reusableNamePage === "manageinbox" ||
          props.reusableNamePage === "saveinbox" ||
          props.reusableNamePage === "archiveinbox"
            ? " Masuk"
            : " Keluar";
      }
      return props.title + resolveName;
    });

    const onClickNav = () => {
      if (props.urlBackNav == "") {
        window.history.back();
      } else {
        Inertia.get(props.urlBackNav, props.dataBackNav);
      }
    };

    return { titleModified, onClickNav };
  },
};
</script>

<style scoped>
.btn-nav {
  border: #fff 0px solid;
  background-color: #fff;
  cursor: pointer;
}
.btn-nav:hover i {
  color: #154173;
}
.centered .column {
  min-height: 50px;
  display: flex;
  justify-content: flex-start;
  align-items: center;
}
h4 {
  padding-top: 10px;
}
</style>
