<template>
  <table
    v-if="Array.isArray(data) && data.length > 0"
    class="table table-striped table-hover"
    :class="{ 'table-scroll': canScroll }"
  >
    <thead>
      <tr>
        <th
          @click="onClickSort(key)"
          class="text-center"
          :class="{ sortable: showSortIcon(key) }"
          v-for="(column, key) in columns"
          :key="key"
        >
          <!-- v-show="showSortIcon(column, key)" -->
          <div class="centered-th">
            <div v-show="showSortIcon(key)" class="d-inline-block mr-2">
              <i
                class="ri-arrow-drop-up-fill ri-sm d-block"
                :class="{ 'text-gray': !configSortIcon(key).asc }"
              ></i>
              <i
                class="ri-arrow-drop-down-fill ri-sm d-block"
                :class="{ 'text-gray': !configSortIcon(key).desc }"
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
        v-for="(value, key1) in data"
        :key="key1"
      >
        <td v-for="(column, key2) in columns" :key="key2">
          {{ dataValue(value, key2) }}
        </td>
      </tr>
    </tbody>
  </table>
  <p v-else>Data yang anda minta kosong.</p>

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
          v-for="(value, key) in arrayPages"
          :key="keyPaginate(value, key)"
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
export default {
  props: {
    sortBy: { type: Object, required: true },
    // search: { type: Object, required:true},
    canAction: Boolean,
    canScroll: Boolean,
    columns: { type: Array, required: true },
    data: { type: Array },
    paginate: {
      type: Object,
      default: { totalData: 0, currentPage: 1, lastPage: 1, itemPerPage: 15 },
    },
  },
  setup(props, { emit }) {
    /*--------------------------------------------------------------
        START: Initial Variable
      --------------------------------------------------------------*/
    const itemPerPage = computed({
      get: () => props.paginate.itemPerPage,
      set: (val) => {
        console.log(val);
        return emit("update:itemPerPage", { itemPerPage: val });
      },
    });
    const columnSorted = reactive({
      index: defaultSort(props),
      by:
        props.sortBy.by != undefined && props.sortBy.by != ""
          ? props.sortBy.by
          : "none",
    });

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

    const onClickSort = (key) => {
      if (!props.columns[key].canSort) {
        return;
      }
      if (columnSorted.index == key) {
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
        columnSorted.index = key;
      }
      if (columnSorted.by != "none")
        emit("update:sort", {
          sortColumn: props.columns[key].column,
          sortBy: columnSorted.by,
        });
      else emit("update:sort", { sortColumn: "", sortBy: "" });
    };

    const onClickPaginate = (key) => {
      if (key != "..." && key >= 1 && key <= props.paginate.lastPage) {
        emit("update:paginate", { currentPage: key });
      }
      if (key === "next" || key === "prev") {
        emit("update:paginate", {
          currentPage:
            key === "next"
              ? props.paginate.currentPage + 1
              : props.paginate.currentPage - 1,
        });
      }
    };

    const onClickRow = (event, key) => {
        // console.log(event);
      if (props.canAction) {
        emit("clickRow", { x: event.screenX, y: event.screenY-80 }, key);
      }
    };

    const showSortIcon = (key) => {
      return props.columns[key].canSort;
    };

    const dataValue = (value, keyColumn) => {
      let columnKey = props.columns[keyColumn].column;
      if (columnKey in value) {
        return value[columnKey];
      } else {
        return "-";
      }
    };

    const keyPaginate = (value, key) => {
      if (value == "...") {
        return "empty".key;
      } else {
        return value;
      }
    };

    /*--------------------------------------------------------------
        END: Function
      --------------------------------------------------------------*/

    return {
      dataValue,
      onClickSort,
      onClickPaginate,
      onClickRow,
      configSortIcon,
      showSortIcon,
      columnSorted,
      arrayPages,
      keyPaginate,
      itemPerPage,
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
// if (paginate.lastPage >= 6) {
//   if (paginate.currentPage > 0 && paginate.currentPage <= 2) {
//     return [1, 2, 3, "...", paginate.lastPage];
//   }
//   if (paginate.currentPage == 3) {
//     return [1, 2, 3, 4, "...", paginate.lastPage];
//   }
//   if (
//     paginate.currentPage > 3 &&
//     paginate.currentPage < paginate.lastPage - 3
//   ) {
//     return [
//       1,
//       "...",
//       paginate.currentPage - 1,
//       paginate.currentPage,
//       paginate.currentPage + 1,
//       "...",
//       paginate.lastPage,
//     ];
//   }
//   if (paginate.currentPage == paginate.lastPage - 3) {
//     return [
//       1,
//       "...",
//       paginate.currentPage - 1,
//       paginate.currentPage,
//       paginate.currentPage + 1,
//       paginate.lastPage,
//     ];
//   }
//   if (
//     paginate.currentPage < paginate.lastPage + 1 &&
//     paginate.currentPage <= paginate.lastPage - 2
//   ) {
//     return [1, 2, 3, "...", paginate.lastPage];
//   }
// }
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

.table tbody tr td {
  border-left: 1px #dadee4 solid;
  border-right: 1px #dadee4 solid;
  padding-right: 15px;
  padding-left: 15px;
}
.table thead tr {
  border: 1px #dadee4 solid;
}
.sortable {
  cursor: pointer;
}
.centered-th {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
