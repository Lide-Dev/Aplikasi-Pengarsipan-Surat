import Dashboard from '@@/Pages/Dashboard/Index.vue'
import DataPegawai from '@@/Pages/DataPegawai/Index.vue'
import KelolaSurat from '@@/Pages/KelolaSurat/Index.vue'
import BuatSurat from '@@/Pages/SimpanSurat/Index.vue'
import Popover from '@@/Components/Popover.vue'

import Sidebar from "@@/Components/Sidebar";
import Navbar from "@@/Components/Navbar";
import { Path } from './path';

// import dayjs from 'dayjs'

export default {
    baseUrl: "http://arsiplldikti.test/",
    // baseUrl : "http://192.168.100.20:3000/"
    // baseUrl: "https://arsiplldikti.sharedwithexpose.com",
    bgCustom: { error: "error", success: "success", warning: "warning" },
    componentsHome: { Popover, Dashboard, Sidebar, Navbar, DataPegawai, KelolaSurat, BuatSurat },
    componentsTitleHome: {
        Dashboard: {
            title: "Dashboard",
        }, DataPegawai: {
            title: "Pengaturan Data Pegawai",
        }, KelolaSurat: {
            title: "Kelola Surat",
        }, BuatSurat: {
            title: "Buat Surat"
        }
    },
    contentsHome: [{
        id: 0,
        name: "Dashboard",
        icon: "ri-dashboard-fill",
        hasChildren: false,
        url: Path.contentsHome.home.index,
    },
    {
        id: 1,
        name: "Kelola Surat",
        icon: "ri-mail-settings-fill",
        hasChildren: true,
        showChildren: false,
        children: [
            {
                id: 0,
                name: "Surat Masuk",
                title: "Kelola Surat Masuk",
                icon: "ri-mail-download-fill",
                url: Path.contentsHome.manageinbox.index,
                hasChildren: false,
            },
            {
                id: 1,
                name: "Surat Keluar",
                title: "Kelola Surat Keluar",
                icon: "ri-mail-send-fill",
                url: Path.contentsHome.managesent.index,
                hasChildren: false,
            },
        ],
    },
    {
        id: 2,
        name: "Laporan Surat",
        icon: "ri-archive-fill",
        hasChildren: true,
        showChildren: false,
        children: [
            {
                id: 0,
                name: "Surat Masuk",
                title: "Laporan Surat Masuk",
                icon: "ri-inbox-archive-fill",
                url: Path.contentsHome.reportinbox.index,
                hasChildren: false,
            },
            {
                id: 1,
                name: "Surat Keluar",
                title: "Laporan Surat Keluar",
                icon: "ri-inbox-unarchive-fill",
                url: Path.contentsHome.reportsent.index,
                hasChildren: false,
            },
        ],
    },
    {
        id: 3,
        name: "Tempat Sampah",
        title: "Tempat Sampah",
        icon: "ri-delete-bin-5-fill",
        hasChildren: false,
        url: Path.contentsHome.trash.index,
    },
    {
        id: 4,
        name: "Pengaturan",
        icon: "ri-settings-5-fill",
        hasChildren: true,
        showChildren: false,
        children: [
            {
                id: 0,
                name: "Data Pegawai",
                title: "Pengaturan Data Pegawai",
                icon: "ri-user-settings-fill",
                url: Path.contentsHome.employees.index,
                hasChildren: false,
            },
            {
                id: 1,
                name: "Perijinan",
                title: "Pengaturan Perijinan",
                icon: "ri-git-repository-private-fill",
                url: Path.contentsHome.permissions.index,
                hasChildren: false,
            },
            {
                id: 2,
                name: "Lanjutan",
                title: "Pengaturan Lanjutan",
                icon: "ri-settings-6-fill",
                url: Path.contentsHome.advanced.index,
                hasChildren: false,
            },
        ],
    },
    {
        id: 5,
        name: "Logout",
        icon: "ri-logout-circle-r-fill",
        hasChildren: false,
        customColor: 'error',
        url: "/logout",
    },],
}
