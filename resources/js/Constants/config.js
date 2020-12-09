import Dashboard from '../Pages/Dashboard/Index.vue'
import DataPegawai from '../Pages/DataPegawai/Index.vue'
import KelolaSurat from '../Pages/KelolaSurat/Index.vue'
import SimpanSurat from '../Pages/SimpanSurat/Index.vue'

import Sidebar from "../Components/Sidebar";
import Navbar from "../Components/Navbar";
import { Path } from './path';

export default {
    // baseUrl : "http://192.168.100.20:3000/"
    baseUrl: "http://arsiplldikti.test/",
    bgCustom: { error: "error", success: "success", warning: "warning" },
    componentsHome: {Dashboard, Sidebar, Navbar, DataPegawai, KelolaSurat, SimpanSurat},
    componentsTitleHome: {
        Dashboard: {
            title: "Dashboard",
        }, DataPegawai: {
            title: "Pengaturan Data Pegawai",
        }, KelolaSurat: {
            title: "Kelola Surat ",
        }, SimpanSurat
    },
    contentsHome: [{
        name: "Dashboard",
        icon: "ri-dashboard-fill",
        hasChildren: false,
        url: Path.contentsHome.home.index,
    },
    {
        name: "Kelola Surat",
        icon: "ri-mail-settings-fill",
        hasChildren: true,
        showChildren: false,
        children: [
            {
                name: "Surat Masuk",
                title: "Kelola Surat Masuk",
                icon: "ri-mail-download-fill",
                url: Path.contentsHome.manageinbox.index,
                hasChildren: false,
            },
            {
                name: "Surat Keluar",
                title: "Kelola Surat Keluar",
                icon: "ri-mail-send-fill",
                url: Path.contentsHome.managesent.index,
                hasChildren: false,
            },
        ],
    },
    {
        name: "Laporan Surat",
        icon: "ri-archive-fill",
        hasChildren: true,
        showChildren: false,
        children: [
            {
                name: "Surat Masuk",
                title: "Laporan Surat Masuk",
                icon: "ri-inbox-archive-fill",
                url: Path.contentsHome.reportinbox.index,
                hasChildren: false,
            },
            {
                name: "Surat Keluar",
                title: "Laporan Surat Keluar",
                icon: "ri-inbox-unarchive-fill",
                url: Path.contentsHome.reportsent.index,
                hasChildren: false,
            },
        ],
    },
    {
        name: "Tempat Sampah",
        title: "Tempat Sampah",
        icon: "ri-delete-bin-5-fill",
        hasChildren: false,
        url: Path.contentsHome.trash.index,
    },
    {
        name: "Pengaturan",
        icon: "ri-settings-5-fill",
        hasChildren: true,
        showChildren: false,
        children: [
            {
                name: "Data Pegawai",
                title: "Pengaturan Data Pegawai",
                icon: "ri-user-settings-fill",
                url: Path.contentsHome.employees.index,
                hasChildren: false,
            },
            {
                name: "Perijinan",
                title: "Pengaturan Perijinan",
                icon: "ri-git-repository-private-fill",
                url: Path.contentsHome.permissions.index,
                hasChildren: false,
            },
            {
                name: "Lanjutan",
                title: "Pengaturan Lanjutan",
                icon: "ri-settings-6-fill",
                url: Path.contentsHome.advanced.index,
                hasChildren: false,
            },
        ],
    },
    {
        name: "Logout",
        icon: "ri-logout-circle-r-fill",
        hasChildren: false,
        customColor: 'error',
        url: "/logout",
    },]
}
