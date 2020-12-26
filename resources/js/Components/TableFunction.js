import { Inertia } from "@inertiajs/inertia";

export const columnsTable = {
    manageinbox: [
        { id: 0, name: "Tanggal Surat", column: "tanggalSurat", canSort: true },
        { id: 1, name: "Asal Surat", column: "asalSurat", canSort: true },
        { id: 2, name: "No. Surat", column: "noSurat", canSort: true },
    ],
    managesent: [
        { id: 0, name: "Tanggal Surat", column: "tanggalSurat", canSort: true },
        { id: 1, name: "Tujuan Surat", column: "tujuan", canSort: true },
        { id: 2, name: "No. Surat", column: "noSurat", canSort: true },
    ]
}

/**
 * Initialization of variable table.
 * - Make sure the isConfig value is TRUE if it's a Table config.
 * - Make sure the isConfig value is FALSE if it's a Query config for sending request table.
 * - Variable must be reactive by `reactive()` function.
 *
 * @param {Boolean} isConfig
 * @param {Array} columns
 */
export function initTable(isConfig = true, columns = []) {
    if (isConfig) {
        return {
            search: "",
            sort: { column: "", by: "none" },
            paginate: {
                totalData: 0,
                currentPage: 1,
                lastPage: 1,
                itemPerPage: 15,
            },
            filter: [{
                name: '',
                alias: '',
                options: {},
            }],
            data: [],
            columns: columns, // { name: "Test", column: "tanggalSurat", canSort: true },
        }
    }
    else {
        return {
            sortColumn: "", sortBy: "", search: "", filter: {},
            currentPage: 1,
            itemPerPage: 15,
        }
    }
}

/**
 * Updated the data table by using watch function.
 * - Make sure it watch ```props``` variable.
 *
 * @param {Object} tableConfig - Config that declared initTable function
 * @param {Object} newData - Data that update at `props.dyProps`
 */
export function onUpdateTable(tableConfig, newData) {
    console.log("Props being updated", newData);
    if ("tableData" in newData) {
        tableConfig.data = newData.tableData;
    } else {
        tableConfig.data = [];
    }
    // console.log(newData.tableConfig.paginate.currentPage);
    tableConfig.paginate = newData.tableConfig.paginate;
}

/**
 * Requested get of all data if search box value null.
 * - Make sure it watch ```nameOfTableConfigVariable``` variable
 * - ```nameOfTableConfigVariable``` option is declared by initTable function
 *
 * @param {String} path - URL which has been reserved for the component table data.
 * @param {Object} search - Search value that declared on table config.
 * @param {Object} query - Query that declared initTable function
 */
export function onNullSearchTable(path, search, query) {
    if (search.length == 0 && query.search.length > 0) {
        query.search = "";
        getTableData(path, query);
    }
}

/**
 * Initialization state of config on mounted.
 * - Make sure it declared at onMounted function
 * - Don't forget to initiate the data table config at server-side for `props`.
 *
 * @param {Object} tableConfig - Config that declared initTable function
 * @param {Object} query - Query that declared initTable function
 * @param {Object} props - Retrieves component props that have declared `TableView` component.
 */
export function onMountedTable(tableConfig, query, props) {
    if ("tableConfig" in props.dyProps) {
        let initTable = props.dyProps.tableConfig;
        if ("search" in initTable) {
            query.search = initTable.search;
            tableConfig.search = initTable.search;
        }
        if ("sort" in initTable) {
            tableConfig.sort = initTable.sort;
            query.sortColumn = initTable.sort.sortColumn;
            query.sortBy = initTable.sort.sortBy;
        }
        if ("paginate" in initTable) {
            tableConfig.paginate = initTable.paginate;
            query.currentPage = initTable.paginate.currentPage;
            query.itemPerPage = initTable.paginate.itemPerPage;
        }
    }
    if ("tableData" in props.dyProps) {
        tableConfig.data = props.dyProps.tableData;
    }
}

/**
 * Handle click/key up the enter at search box. It will send up a request to server-side.
 *
 * @param {String} path - URL which has been reserved for the component table data.
 * @param {Object} tableConfig - Config that declared `initTable` function
 * @param {Object} query - Query that declared `initTable` function
 */
export function onClickSearchTable(path, tableConfig, query) {
    if (tableConfig.search.length > 2) {
        query.search = tableConfig.search;
        getTableData(path, query);
    } else if (tableConfig.search.length == 0 && query.search.length > 0) {
        query.search = "";
        getTableData(path, query);
    }
}

/**
 * Handle the sort that was emitted by `TableView` component.
 * - Make sure to bind this function at `@update:sort` emit on `TableView` component.
 *
 * @param {String} path - URL which has been reserved for the component table data.
 * @param {Object} query - Query that declared `initTable` function
 * @param {Object} obj - Emit `@sorted` parameter variable.
 */
export function onSortTable(path, query, obj) {
    console.log("Sorting Table", obj);
    query.sortColumn = obj.sortColumn;
    console.log("Submitted Query Table", query);
    query.sortBy = obj.sortBy;
    getTableData(path, query);
}

/**
 * Handle the paginate that was emitted by `TableView` component. It was include for
 * - Make sure to bind this function at `@update:paginate` emit on `TableView` component.
 *
 * @param {String} path - URL which has been reserved for the component table data.
 * @param {Object} query - Query that declared `initTable` function
 * @param {Object} obj - Emit `@update:paginate` or `@update:item-per-page` parameter variable.
 */
export function onPaginateTable(path, query, obj) {
    console.log("Paginate Table", obj);
    if ('currentPage' in obj)
        query.currentPage = obj.currentPage;
    if ('itemPerPage' in obj) {
        query.currentPage = 1;
        query.itemPerPage = obj.itemPerPage;
    }
    console.log("Submitted Query Table", query);

    // query.itemPerPage = obj.sortBy;
    getTableData(path, query);
}

/**
 * Handle the request with `Inertia.get()`.
 *
 * @param {String} path - URL which has been reserved for the component table data.
 * @param {Object} query - Query that declared `initTable` function
 */
function getTableData(path, query) {
    let a = {};
    console.log("Checking query before send.", query);
    if (query.sortColumn !== "") {
        a.sortColumn = query.sortColumn;
        a.sortBy = query.sortBy;
    }
    if (query.search !== "") {
        a.search = query.search;
    }
    a.tableOnly = true;
    a.page = query.currentPage;
    a.itemPerPage = query.itemPerPage;
    Inertia.get(path, a, {
        preserveState: true,
    });
}


