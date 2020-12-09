<template>
  <header class="navbar bg-secondary">
    <section class="navbar-section">
      <button
        v-show="hasSidebar"
        class="sidebar-trigger btn"
        @click="toggleClicked"
      >
        <i class="ri-menu-line ri-xl"></i>
      </button>

      <img :src="logo" alt="Logo LLDIKTI" class="hide-md" />
      <inertia-link href="/" class="navbar-brand mr-2 hide-md"
        ><h5>SIPAS</h5>
        <span>LLDIKTI Wilayah XIV</span></inertia-link
      >
    </section>
    <section class="navbar-section">
      <div class="badge-user bg-success text-small">Super Admin</div>
      <i class="ri-shield-user-fill ri-xl"></i>
    </section>
  </header>
</template>

<script>
import { computed, ref } from "vue";
import { Path } from "../constants/path";

export default {
  props: {
    hasSidebar: { type: Boolean, default: true },
  },
  setup(props, { emit }) {
    const logo = ref(Path.logo);

    const toggleClicked = () => {
      emit("toggleClicked");
    };

    return {
      logo,
      toggleClicked,
    };
  },
};
</script>

<style>
.navbar {
  z-index: 3;
  position: fixed;
  height: 65px;
  padding-left: 10px;
  width: 100%;
  padding-right: 15px;
  -webkit-box-shadow: 0px 0px 17px -2px #000000;
  box-shadow: 0px 0px 17px -2px #000000;
}

a,
.btn.btn-link,
a:visited {
  color: #fff;
  text-decoration: none;
}

.navbar-brand h5 {
  margin-bottom: 0;
}

.navbar-brand:hover,
.btn.btn-link:hover {
  color: #bcc3ce;
}

.badge-user {
  padding: 2px 10px;
  border-radius: 8px;
  margin-right: 15px;
}
.sidebar-trigger {
  margin-right: 10px;
  padding: 0 30px;
  width: 40px;
  height: 100%;
  display: none;
  border: 0;
  background-color: #134172;
  color: #fff;
}

.sidebar-trigger:active {
  color: grey;
}

img {
  display: block;
  margin-right: 10px;
  width: 55px;
}

@media only screen and (max-width: 840px) {
  .navbar {
    padding-left: 0px;
  }
  .sidebar-trigger {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
  }
}
</style>
