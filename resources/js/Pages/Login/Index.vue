<template>
  <div class="login columns bg-primary">
    <div class="column col-7 col-md-12 first centered bg-white">
      <div class="columns centered col-gapless height-100">
        <div class="column col-md-4 show-md">
          <img :src="logo" alt="Logo LLDIKTI" />
        </div>
        <div class="column col-md-8 show-md">
          <h5 class="text-small text-primary col-8">
            Sistem Informasi Pengarsipan Surat LLDIKTI Wilayah XIV
          </h5>
        </div>
      </div>
      <div class="columns hide-md">
        <div class="column col-md-11 col-12">
          <figure>
            <img :src="logo" alt="Logo LLDIKTI" />
          </figure>
        </div>
        <div class="column col-md-1 col-12 centered">
          <h5 class="text-primary col-8 hide-md text-center">
            Sistem Informasi Pengarsipan Surat LLDIKTI Wilayah XIV
          </h5>
        </div>
      </div>
    </div>

    <div class="column col-5 col-md-12 second bg-primary centered form">
      <form @submit.prevent="login" class="columns">
        <div class="column col-12">
          <p class="text-bold hide-md">Login ke SIPAS</p>
          <p class="text-bold text-center show-md">Login ke SIPAS</p>
        </div>

        <div class="column col-10 col-md-12 my-2">
          <div class="form-group">
            <input
              class="form-input"
              type="text"
              id="idUsername"
              placeholder="Username"
              v-model="username"
            />
          </div>
        </div>
        <div class="column col-10 col-md-12 my-2">
          <div class="form-group">
            <input
              class="form-input"
              type="password"
              id="idPassword"
              placeholder="Password"
              v-model="password"
            />
          </div>
        </div>
        <div class="column mt-10">
          <button
            type="submit"
            v-if="!processXhr"
            class="btn btn-success col-10 col-md-12"
          >
            Login
          </button>
          <button v-else class="btn btn-success loading col-10 col-md-12">
            Login
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref, reactive, computed, toRefs, watch, watchEffect } from "vue";
import { PATH } from "../../constants/path";
import { useToast } from "vue-toastification";
import { ID_ID } from "../../Constants/lang";

export default {
  props: {
    success: {
      type: Boolean,
    },
    error: {
      type: String,
    },
    sessionExpired: Boolean,
    logout: Boolean,
  },
  setup(props) {
    // console.log(props);
    const propsEffect = reactive(props);
    const toast = useToast();
    const logo = ref(PATH.logo);
    const inputs = reactive({
      username: "jenifer.schuster",
      password: "password",
      remember: false,
    });
    const success = ref(props.success);
    const error = ref(props.error);
    const processXhr = ref(false);
    watch(props, (newProps, prevProps) => {
      success.value = newProps.success;
    });

    // const messages = reactive({ success: props.success, error: props.error });

    return { ...toRefs(inputs), success, error, logo, toast, processXhr };
  },
  methods: {
    async login() {
      this.processXhr = true;
      let param = { username: this.username, password: this.password };
      await this.$inertia.post("/login", param);
      this.processXhr = false;
      if (this.success) {
        this.$inertia.visit("/home", { replace: true });
      }
      console.log(this.$page);
    },
  },
};
</script>

<style scoped>
.bg-white {
  background-color: #fff;
}
.login {
  height: 100vh;
  overflow: hidden;
}
.height-100 {
  height: 100%;
}
.mt-10 {
  margin-top: 10%;
}

.centered {
  display: flex;
  justify-content: center;
  align-items: center;
}

img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 40%;
}
.column.second {
  padding-left: 5%;
  padding-right: 5%;
}

@media only screen and (max-width: 840px) {
  .login {
    height: 100vh;
    overflow: hidden;
  }
  .column.first {
    height: 30vh;
  }

  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
  }

  .column.second {
    height: 70vh;
    width: 100%;
    padding-left: 10%;
    padding-right: 10%;
  }

  .centered.form {
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    padding-top: 15%;
  }
}

@media only screen and (max-width: 660px) {
  img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 60%;
  }
}
</style>
