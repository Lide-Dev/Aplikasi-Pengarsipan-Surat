import Dashboard from '../Pages/Dashboard/Index.vue'
import DataPegawai from '../Pages/DataPegawai/Index.vue'
import KelolaSurat from '../Pages/KelolaSurat/Index.vue'
import SimpanSurat from '../Pages/SimpanSurat/Index.vue'

import Sidebar from "../Components/Sidebar";
import Navbar from "../Components/Navbar";

export default {
    // baseUrl : "http://192.168.100.20:3000/"
    baseUrl: "http://arsiplldikti.test/",
    bgCustom: { error: "error", success: "success", warning: "warning" },
    componentsHome: { Dashboard, Sidebar, Navbar, DataPegawai, KelolaSurat, SimpanSurat }
}
