<template>
  <div class="columns">
    <div v-for="(item, key) in filterItems" class="column col-auto" :key="key">
      <select v-model="itemPerPage" class="form-select select">
        <option :value="key2" :key="key2" v-for="(value, key2) in item.options">
          {{ value }}
        </option>
      </select>
    </div>
  </div>
</template>

<script>
import { reactive } from "vue";
export default {
  props: {
    items: { type: Object, require: true },
  },
  setup(props, { emit }) {
    const filterItems = reactive(props.items);
  },
};
</script>

<style>
</style>
