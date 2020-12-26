<template>
  <navbar :has-sidebar="false" />
  <div class="error-container container text-center bg-gray">
    <div class="card p-centered col-8 col-md-12">
      <div class="card-image">
        <i class="text-error ri-error-warning-fill ri-5x"></i>
      </div>
      <div class="card-body">
        <h4 class="text-primary" :class="{ 'line-bottom': !errors.isServer }">
          {{ errors.message }}
        </h4>
        <p class="text-primary" v-show="errors.isServer">{{ contact }}</p>
        <inertia-link as="button" class="text-light btn btn-primary" href="/"
          >Kembali ke Home.</inertia-link
        >
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "../../Components/Navbar.vue";
import { ID_ID } from "../../Constants/lang";

export default {
  components: { Navbar },
  props: {
    user: Object,
    toast: String,
    errors: Object,
  },
  setup() {
    const contact = ID_ID.errors.contact;
    return { contact };
  },
};
</script>

<style scoped>
.error-container {
  /* position: absolute; */
  /* top: 65px; */
  padding-top: 35vh;
  margin: 0 auto;
  min-width: 100vw;
  min-height: 100vh;
  z-index: 0;
  overflow: hidden;
}

.line-bottom {
  margin-bottom: 40px;
}

</style>
