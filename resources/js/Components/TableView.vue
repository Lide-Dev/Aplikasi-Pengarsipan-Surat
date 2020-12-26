<template>
  <div class="table-container">
    <div
      v-if="tableScrollIndicator('right')"
      class="scroll-indicator scroll-indicator-right"
    ></div>
    <div
      v-if="tableScrollIndicator('left')"
      class="scroll-indicator scroll-indicator-left"
    ></div>

    <table
      v-if="Array.isArray(data) && data.length > 0"
      class="table table-hover"
      :class="{ 'table-scroll': canScrollTable }"
      @scroll="onScrollTable($event)"
    >
      <thead>
        <tr>
          <th
            @click="onClickSort(column)"
            class="tooltip text-center"
            :class="{ sortable: showSortIcon(column.id) }"
            v-for="column in columns"
            :key="column.id"
            data-tooltip="Menyortir"
          >
            <!-- v-show="showSortIcon(column, key)" -->
            <div class="centered-th">
              <div v-show="showSortIcon(column.id)" class="d-inline-block mr-2">
                <i
                  class="ri-arrow-drop-up-fill ri-sm d-block"
                  :class="{ 'text-gray': !configSortIcon(column.id).asc }"
                ></i>
                <i
                  class="ri-arrow-drop-down-fill ri-sm d-block"
                  :class="{ 'text-gray': !configSortIcon(column.id).desc }"
                ></i>
              </div>
              <div class="d-inline-block">{{ column.name }}</div>
            </div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          @click="onClickRow($event, value.id)"
          :class="{ 'selected-row': selectedRowClass(value.id) }"
          v-for="value in data"
          :key="value.id"
        >
          <td v-for="column in columns" :key="column.id">
            {{ dataValue(value, column) }}
          </td>
        </tr>
      </tbody>
    </table>
    <p v-else>Data yang anda minta kosong.</p>
  </div>
  <div
    class="columns pagination-container"
    v-if="Array.isArray(data) && data.length > 0"
  >
    <div class="column col-auto mr-2">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: paginate.currentPage === 1 }">
          <a
            v-if="paginate.currentPage !== 1"
            @click.prevent="onClickPaginate('prev')"
            href="#"
            ><i class="ri-arrow-left-circle-fill"></i>
          </a>
          <span v-else><i class="ri-arrow-left-circle-fill"></i></span>
        </li>
        <li
          v-for="value in arrayPages"
          :key="keyPaginate(value)"
          class="page-item"
          :class="{ active: paginate.currentPage == value }"
        >
          <a
            v-if="value != '...'"
            href="#"
            @click.prevent="onClickPaginate(value)"
            >{{ value }}</a
          >
          <span v-else>...</span>
        </li>
        <li
          class="page-item"
          :class="{ disabled: paginate.currentPage === paginate.lastPage }"
        >
          <a
            v-if="paginate.currentPage !== paginate.lastPage"
            @click.prevent="onClickPaginate('next')"
            href="#"
            ><i class="ri-arrow-right-circle-fill"></i
          ></a>
          <span v-else><i class="ri-arrow-right-circle-fill"></i></span>
        </li>
      </ul>
    </div>
    <div class="column col-1 col-md-3">
      <select v-model="itemPerPage" class="form-select select">
        <option>10</option>
        <option selected>15</option>
        <option>20</option>
        <option>25</option>
      </select>
    </div>
  </div>
</template>

<script>
import { computed, reactive, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { PATH } from "../constants/path";
import UseBreakpoint from "./UseBreakpoint";
import _ from "lodash";

export default {
  props: {
    canAction: Boolean,
    canScroll: Boolean,
    columns: { type: Array, required: true },
    data: { type: Array },
    paginate: {
      type: Object,
      default: { totalData: 0, currentPage: 1, lastPage: 1, itemPerPage: 15 },
    },
    popoverState: { type: Boolean, required: true },
    sortBy: { type: Object, required: true },
  },
  emits: ["updateItemPerPage", "updateSort", "updatePaginate", "clickRow"],
  setup(props, { emit }) {
    /*--------------------------------------------------------------
        START: Initial Variable
      --------------------------------------------------------------*/

    const selectedRowId = ref(0);

    const columnSorted = reactive({
      index: defaultSort(props),
      by:
        props.sortBy.by != undefined && props.sortBy.by != ""
          ? props.sortBy.by
          : "none",
    });

    const tableScrollState = reactive({ value: 0, max: 1 });

    const arrayPages = computed(() => {
      let paginate = props.paginate;
      let array = [];
      let firstdot = false;
      let lastdot = false;
      if (paginate.lastPage <= 5) {
        for (let p = 1; p <= paginate.lastPage; p++) {
          array.push(p);
        }
        return array;
      }
      if (paginate.lastPage === 6) {
        // 1 2 3 4 ... 6 //1 ... 3 4 5 6
        for (let a = 1; a <= paginate.lastPage; a++) {
          if (a <= 3 && paginate.currentPage <= 3) array.push(a);
          else if (a >= 4 && paginate.currentPage >= 4) array.push(a);
          else {
            if (!firstdot && paginate.currentPage <= 3) {
              array.push("...");
              firstdot = true;
            }
            if (!lastdot && paginate.currentPage >= 4) {
              array.push("...");
              lastdot = true;
            }
          }
        }
      }
      if (paginate.lastPage >= 7) {
        let valueComparison = 0;
        for (let a = 1; a <= paginate.lastPage; a++) {
          if (a === 1 || a === paginate.lastPage) {
            //FIRST AND END
            array.push(a);
          } else if (
            paginate.currentPage <= 2 ||
            paginate.currentPage >= paginate.lastPage - 1
          ) {
            // console.log("1 2 3 ... 7 || 1 ... 5 6 7 done");
            if (a <= 3 && paginate.currentPage <= 2) array.push(a);
            else if (
              a >= paginate.lastPage - 2 &&
              paginate.currentPage >= paginate.lastPage - 1
            )
              array.push(a);
            else {
              if (!lastdot && paginate.currentPage <= 2) {
                array.push("...");
                lastdot = true;
              }
              if (!firstdot && paginate.currentPage >= paginate.lastPage - 1) {
                array.push("...");
                firstdot = true;
              }
            }
          } else if (
            paginate.currentPage === 3 ||
            paginate.currentPage === paginate.lastPage - 2
          ) {
            // console.log(" 1 2 3 4 ... 7 || 1 ... 4 5 6 7");
            if (a <= 4 && paginate.currentPage === 3) array.push(a);
            else if (
              a >= paginate.lastPage - 3 &&
              paginate.currentPage === paginate.lastPage - 2
            )
              array.push(a);
            else {
              if (!lastdot && paginate.currentPage === 3) {
                array.push("...");
                lastdot = true;
              }
              if (!firstdot && paginate.currentPage === paginate.lastPage - 2) {
                array.push("...");
                firstdot = true;
              }
            }
          } else {
            // 1 ... 3 4 5 ... 7
            if (a >= paginate.currentPage - 1 && a <= paginate.currentPage + 1)
              array.push(a);
            else {
              if (!firstdot && a <= paginate.currentPage - 2) {
                array.push("...");
                firstdot = true;
              }
              if (!lastdot && a >= paginate.currentPage + 2) {
                array.push("...");
                lastdot = true;
              }
            }
          }
        }
      }
      return array;
    });

    const canScrollTable = computed(() => {
      if (props.canScroll) {
        return props.canScroll;
      } else {
        let type = UseBreakpoint().type;
        return type.value === "xs";
      }
    });

    const itemPerPage = computed({
      get: () => props.paginate.itemPerPage,
      set: (val) => {
        return emit("updateItemPerPage", { itemPerPage: val });
      },
    });

    const selectedRowClass = (key) => {
      // console.log()
      return props.popoverState && selectedRowId.value === key;
    };

    /*--------------------------------------------------------------
        END: Initial Variable
      --------------------------------------------------------------*/

    /*--------------------------------------------------------------
        START: Function
      --------------------------------------------------------------*/

    const configSortIcon = (key) => {
      if (columnSorted.index == key) {
        switch (columnSorted.by) {
          case "asc":
            return { asc: true, desc: false };
            break;
          case "desc":
            return { asc: false, desc: true };
            break;
          default:
            return { asc: false, desc: false };
            break;
        }
      } else {
        return { asc: false, desc: false };
      }
    };

    const dataValue = (value, column) => {
      let columnKey = column.column;
      if (columnKey in value) {
        return value[columnKey];
      } else {
        return "-";
      }
    };

    const keyPaginate = (value) => {
      if (value == "...") {
        return _.uniqueId();
      } else {
        return value;
      }
    };

    const onClickSort = (column) => {
      if (!column.canSort) {
        return;
      }
      if (columnSorted.index == column.id) {
        switch (columnSorted.by) {
          case "none":
            columnSorted.by = "asc";
            break;
          case "asc":
            columnSorted.by = "desc";
            break;
          default:
            columnSorted.index = -1;
            columnSorted.by = "none";
            break;
        }
      } else {
        columnSorted.by = "asc";
        columnSorted.index = column.id;
      }
      if (columnSorted.by != "none")
        emit("updateSort", {
          sortColumn: column.column,
          sortBy: columnSorted.by,
        });
      else emit("updateSort", { sortColumn: "", sortBy: "" });
    };

    const onClickPaginate = (key) => {
      if (key != "..." && typeof key === "string") {
        return;
      }
      if (key != "..." && key >= 1 && key <= props.paginate.lastPage) {
        emit("updatePaginate", { currentPage: key });
      }
      if (key === "next" || key === "prev") {
        emit("updatePaginate", {
          currentPage:
            key === "next"
              ? props.paginate.currentPage + 1
              : props.paginate.currentPage - 1,
        });
      }
    };

    const onClickRow = (event, key) => {
      if (props.canAction) {
        selectedRowId.value = key;
        emit("clickRow", { x: event.clientX, y: event.clientY }, key);
      }
    };

    const onScrollTable = (event) => {
      if (canScrollTable.value) {
        tableScrollState.value = event.target.scrollLeft;
        tableScrollState.max =
          event.target.scrollWidth - event.target.clientWidth;
      }
    };

    const showSortIcon = (key) => {
      return props.columns[key].canSort;
    };

    const tableScrollIndicator = (value) => {
      //   console.log(tableScrollState.value, tableScrollState.max);
      if (value === "right") {
        return tableScrollState.value < tableScrollState.max;
      } else {
        return tableScrollState.value > 0;
      }
    };

    /*--------------------------------------------------------------
        END: Function
      --------------------------------------------------------------*/

    return {
      arrayPages,
      canScrollTable,
      columnSorted,
      configSortIcon,
      dataValue,
      itemPerPage,
      keyPaginate,
      onClickPaginate,
      onClickRow,
      onClickSort,
      onScrollTable,
      tableScrollState,
      tableScrollIndicator,
      selectedRowClass,
      selectedRowId,
      showSortIcon,
    };
  },
};

/*--------------------------------------------------------------
    START: Function Helper
----------------------------------------------------------------*/

const defaultSort = (props) => {
  if (
    "column" in props.sortBy &&
    props.sortBy.column != undefined &&
    props.sortBy.column != ""
  ) {
    let i = 0;
    props.columns.some((element) => {
      i++;
      return element.column == props.sortBy.column;
    });
    return i - 1;
  } else {
    return -1;
  }
};

/*--------------------------------------------------------------
    END: Function Helper
----------------------------------------------------------------*/
</script>

<style scoped>
.page-item:not(.active) a {
  color: #184c8f !important;
}
/*
.page-item:not(.active):hover {
} */
.pagination-container {
  margin-top: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.page-item:not(.active) a:hover {
  color: #fff !important;
  border-radius: 0.1rem;
  background-color: #184c8f;
}

.page-item.disabled span {
  cursor: default;
  opacity: 0.5;
  pointer-events: none;
}

.table-container {
  position: relative;
}

.table tbody tr td {
  border-left: 1px #dadee4 solid;
  border-right: 1px #dadee4 solid;
  padding-right: 15px;
  padding-left: 15px;
}

.table tbody tr {
  cursor: pointer;
}

.table thead tr {
  border: 1px #dadee4 solid;
}

.table tbody tr:hover {
  color: #fff;
  background-color: #184c8f;
}

.selected-row {
  -webkit-box-shadow: 0px 11px 14px 0px rgba(0, 0, 0, 0.3);
  box-shadow: 0px 11px 14px 0px rgba(0, 0, 0, 0.3);
}

.sortable {
  cursor: pointer;
}
.centered-th {
  display: flex;
  align-items: center;
  justify-content: center;
}
.scroll-indicator {
  pointer-events: none;
}

@media only screen and (max-width: 840px) {
  .scroll-indicator-left {
    position: absolute;
    width: 10%;
    height: 100%;
    left: -10;
    z-index: 5;
    background-image: linear-gradient(
      to left,
      rgba(255, 255, 255, 0),
      rgba(255, 255, 255, 1) 50%
    );
  }
  .scroll-indicator-right {
    position: absolute;
    width: 10%;
    height: 100%;
    right: -10;
    z-index: 5;
    background-image: linear-gradient(
      to right,
      rgba(255, 255, 255, 0),
      rgba(255, 255, 255, 1) 50%
    );
  }
}
</style>
