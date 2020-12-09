<template>
  <div class="container">
    <div class="columns">
      <div class="column col-auto">
        <h4>{{ titleModified }}</h4>
      </div>
      <div class="column col-auto col-ml-auto">
        <button
          class="btn btn-success text-light tooltip tooltip-left show-md"
          data-tooltip="Buat Surat"
        >
          <i class="ri-mail-add-fill ri-1x"></i>
        </button>
        <button class="btn btn-success text-light float-right hide-md">
          <i class="ri-mail-add-fill mr-2"></i>
          <span class="hide-md">Buat Surat</span>
        </button>
      </div>
    </div>
    <div class="divider"></div>
    <label class="form-label" for="searchID">Pencarian</label>
    <div class="columns search-box">
      <div class="column col-8">
        <div class="form-group">
          <input
            @keyup.enter="onClickSearch"
            class="form-input"
            type="text"
            id="searchID"
            placeholder="Harus lebih dari 3 karakter..."
            v-model="search"
          />
        </div>
      </div>
      <div class="column col-2 col-md-auto">
        <button @click="onClickSearch" class="btn btn-primary text-light">
          Cari
        </button>
      </div>
      <div class="column col-2 col-md-2"></div>
    </div>

    <table-view
      :sort-by="sort"
      :columns="columns"
      :data="data"
      :paginate="paginate"
      @update:sort="onSortManage"
      @update:paginate="onPaginateManage"
      @update:item-per-page="onPaginateManage"
    ></table-view>
  </div>
</template>

<script>
import {
  computed,
  onMounted,
  onUpdated,
  reactive,
  ref,
  toRef,
  toRefs,
  watch,
} from "vue";
import TableView from "../../Components/TableView.vue";
import {
  onUpdateTable,
  onNullSearchTable,
  onPaginateTable,
  onMountedTable,
  onClickSearchTable,
  onSortTable,
  initTable,
  columnsTable,
} from "../../Components/TableFunction";
import { Inertia } from "@inertiajs/inertia";
import config from "../../constants/config";
import { Path } from "../../constants/path";

export default {
  components: { TableView },
  props: {
    title: String,
    dyProps: {
      type: Object,
      default: {},
    },
  },
  setup(props) {
    const url = ref(Path.contentsHome[props.dyProps.page].index);
    const page = ref(props.dyProps.page);
    const titleModified = computed(
      () =>
        props.title +
        (props.dyProps.page === "manageinbox" ? "Masuk" : "Keluar")
    );

    const tableConfig = reactive(
      initTable(true, columnsTable[props.dyProps.page])
    );
    const query = reactive(initTable(false));

    onMounted(() => {
      console.log(columnsTable[props.dyProps.page]);
      onMountedTable(tableConfig, query, props);
    });

    watch(props, (newProps, prevProps) => {
      onUpdateTable(tableConfig, newProps.dyProps);
      if (page.value !== props.dyProps.page) {
        console.log(
          "Page is change on manage page",
          page.value,
          props.dyProps.page
        );
        page.value = newProps.dyProps.page;
        url.value = Path.contentsHome[props.dyProps.page].index;
        tableConfig.columns = columnsTable[page.value];
        console.log(tableConfig.columns);
      }
    });
    watch(tableConfig, (newSearch, prevSearch) => {
      onNullSearchTable(url.value, tableConfig.search, query);
    });

    const onClickSearch = () =>
      onClickSearchTable(url.value, tableConfig, query);

    const onSortManage = (obj) => onSortTable(url.value, query, obj);

    const onPaginateManage = (obj) => onPaginateTable(url.value, query, obj);

    return {
      ...toRefs(tableConfig),
      onClickSearch,
      onSortManage,
      onPaginateManage,
      query,
      titleModified,
    };
  },
};
</script>


<style scoped>
.search-box {
  margin-bottom: 20px;
}
</style>
