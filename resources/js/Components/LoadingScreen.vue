<template>
  <teleport to="body">
    <div v-if="show" class="loading-container container">
      <div class="loading-center columns">
        <div class="column col-8">
          <h6 class="text-primary">{{text}}</h6>
          <progress class="progress" :value=value max="100"></progress>
          <small v-if="time===0" class="text-primary mt-2 d-block">Mohon Menunggu</small>
          <small v-else class="text-primary mt-2 d-block">Waktu</small>
          <button @click="onClickCancel" class="mt-10 btn btn-error">
            Batalkan
          </button>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>
export default {
  props: {
    canCancel: { type: Boolean, default: false },
    value: { type: Number, default: 0 },
    text:{type:String,default:"Sedang di proses..."},
    time: { type: Number, default: 0 },
    show: { type: Boolean, default: false },
  },
  emits: ["click:cancel"],
  setup(props, { emit }) {
    function onClickCancel() {
      emit("click:cancel");
    }
    return { onClickCancel };
  },
};
</script>

<style>
.loading-container {
  background-color: #fff;
  position: fixed;
  z-index: 1000;
  width: 100vw;
  height: 100vh;
}
.loading-center {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
.mt-10 {
  margin-top: 2em;
}
</style>
