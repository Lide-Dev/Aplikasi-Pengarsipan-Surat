<template>
  <div :class="'dropdown active col-md-12 col-' + column">
    <div class="form-group">
      <label class="text-primary form-label" :for="id">{{ label }}</label>
      <div class="input-group">
        <input
          :id="id"
          class="form-input"
          :class="{ 'is-error': error }"
          v-model="search"
          type="text"
          :name="name"
          :placeholder="placeholder"
          @keyup.enter="onTagNewAdded"
        />
        <button
          @click="onTagNewAdded"
          class="btn btn-primary input-group-btn tooltip tooltip-left"
          data-tooltip="Tambah Pihak Tembusan"
        >
          <i class="ri-add-fill ri-xl"></i>
        </button>
        <p class="form-input-hint">{{errorMessage||""}}</p>
      </div>
    </div>
    <ul v-if="showAutoComplete" class="menu" v-closable="onOutsideClick">
      <li
        @click="onTagAdded(value.id)"
        v-for="value in autoComplete"
        :key="value.id"
        class="menu-item"
      >
        <a @click.prevent="">{{ value.nama }}</a>
      </li>
    </ul>
  </div>

  <!-- eslint-disable-next-line vue/no-multiple-template-root -->
  <div v-if="canTag" class="columns mt-2">
    <template v-if="tags.length > 0">
      <span
        v-for="tag in tags"
        :key="tag.id"
        class="chip bg-primary text-light"
      >
        <i class="ri-asterisk mr-2" v-if="'new' in tag"></i>
        {{ tag.nama }}
        <a
          href="#"
          @click.prevent="onTagRemove(tag.id)"
          class="btn btn-clear"
          aria-label="Close"
          role="button"
        ></a>
      </span>
    </template>
  </div>
</template>

<script>
import { computed, ref, watch } from "vue";
import _ from "lodash";
// import { Inertia } from "@inertiajs/inertia";
// import { Path } from "../constants/path";
// import Axios from "axios";
import { onUpdateSearchBox } from "./InputAutoCompleteFunction";
export default {
  props: {
    canTag: { type: Boolean, default: false },
    clientData: { type: Array, default: () => [] },
    column: { type: String, default: "12" },
    id: { type: String, default: "autocomplete" },
    isServerData: { type: Boolean, default: false },
    label: { type: String, default: "" },
    modelValue: { type: Array, default: () => [] },
    name: { type: String, required: true },
    placeholder: { type: String, default: "" },
    url: { type: String, default: "" },
    error: { type: Boolean, default: false },
    errorMessage: { type: String, default: "" },
  },
  emits: ["click:tag", "update:modelValue"],
  setup(props, { emit }) {
    const tags = ref([]);
    const search = ref("");
    const autoComplete = ref([]);
    const cancelAutocomplete = ref("");
    const showAutoComplete = computed(() => autoComplete.value.length > 0);

    watch(search, (newSearch) => {
      console.log(newSearch);
      onUpdateSearchBox(
        newSearch,
        tags,
        autoComplete,
        props,
        cancelAutocomplete
      );
    });

    function onTagRemove(key) {
      tags.value = tags.value.filter((e) => {
        return e.id != key;
      });
      emit("click:tag", tags.value);
      emit("update:modelValue", tags.value);
    }
    function onTagAdded(key) {
      let add = autoComplete.value.filter((e) => {
        return e.id == key;
      });
      //   console.log("Tags has been selected:",key,add);
      autoComplete.value = [];
      search.value = "";
      tags.value.push(add[0]);
      emit("click:tag", tags.value);
      emit("update:modelValue", tags.value);
    }
    function onTagNewAdded() {
      if (search.value.length == 0) {
        return;
      }
      let filter = tags.value.filter((e) => {
        return "new" in e;
      });
      console.log(filter.length);
      tags.value.push({
        id: "new" + filter.length,
        nama: search.value.split(" ").map(_.capitalize).join(" "),
        new: true,
      });
      search.value = "";
      emit("update:modelValue", tags.value);
      //   Axios.post()
    }
    function onOutsideClick() {
      autoComplete.value = [];
    }
    return {
      autoComplete,
      onTagAdded,
      onOutsideClick,
      onTagRemove,
      onTagNewAdded,
      search,
      showAutoComplete,
      tags,
    };
  },
};
</script>

<style scoped>
.menu-item a {
  color: #134172 !important;
  cursor: pointer;
}

.menu-item a:hover {
  color: #fff !important ;
}
</style>
