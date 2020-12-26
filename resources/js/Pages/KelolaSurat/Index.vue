<template>
  <div class="container">
    <title-bar :title="title" :reusable-name-page="dyProps.page">
      <div class="column col-auto col-ml-auto">
        <inertia-link
          :href="url + '/create'"
          class="btn btn-success text-light float-right"
        >
          <i class="ri-mail-add-fill"></i>
          <span class="ml-2 hide-md">Buat Surat</span>
        </inertia-link>
      </div>
    </title-bar>
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
      :popoverState="popoverState"
      :canAction="true"
      @click-row="onClickRowManageMail"
      @update-sort="onSortManage"
      @update-paginate="onPaginateManage"
      @update-item-per-page="onPaginateManage"
    ></table-view>
  </div>
</template>

<script>
import {
  computed,
  inject,
  onMounted,
  provide,
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
import TitleBar from "../../Components/TitleBar"

export default {
  components: { TableView, TitleBar },
  props: {
    title: String,
    dyProps: {
      type: Object,
      default: {},
    },
  },
  setup(props) {
    const url = computed(() => {
      //   console.log("URL", Path.contentsHome[props.dyProps.page]);
      return Path.contentsHome[props.dyProps.page].index;
    });

    const page = ref(props.dyProps.page);

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
        // url.value = Path.contentsHome[props.dyProps.page].index;
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

    const setPopoverCoordinate = inject("setPopoverCoordinate");
    const setPopoverState = inject("setPopoverState");
    const popoverState = inject("getPopoverState");

    const onClickRowManageMail = (coordinate, id) => {
      let content = [
        {
          id: 0,
          name: "Lihat Surat",
          icon: "ri-mail-open-fill",
          callback: () => {
            console.log("Lihat Surat Clicked" + id);
          },
        },
        {
          id: 1,
          name: "Hapus Surat",
          icon: "ri-delete-bin-2-fill",
          callback: () => {
            console.log("Hapus Surat Clicked" + id);
          },
        },
      ];
      //   console.log("Clicked Row");
      setPopoverState(true);
      setPopoverState(content);
      setPopoverCoordinate(coordinate);
      console.log("Clicked Row", popoverState.value);
    };

    return {
      url,
      ...toRefs(tableConfig),
      onClickSearch,
      onSortManage,
      onClickRowManageMail,
      onPaginateManage,
      query,
      popoverState,
      setPopoverState,
      setPopoverCoordinate,
    };
  },
};
</script>


<style scoped>
.search-box {
  margin-bottom: 20px;
}
</style>
