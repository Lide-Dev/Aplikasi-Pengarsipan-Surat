<template>
  <title-bar
    canBackNav
    :title="title"
    :reusableNamePage="dyProps.page"
  ></title-bar>
  <!-- eslint-disable-next-line vue/no-multiple-template-root -->
  <form @submit.prevent="" name="simpansurat">
    <div class="container form-container">
      <h5 class="text-primary">Informasi Penting Surat</h5>
      <div class="divider"></div>
      <p class="text-primary text-justify col-8 col-md-12">
        Form di bagian ini berisi data-data penting seperti sifat surat,
        perihal, nomor dari surat, asal surat dari perusahaan/instansi mana, dan
        tanggal surat tersebut. Dikarenakan penting maka form di bagian ini
        perlu di isi.
      </p>
      <div class="columns">
        <div class="column form-group col-6 col-md-12">
          <label class="text-primary form-label" for="input_perihal"
            >Perihal</label
          >
          <input
            class="form-input"
            type="text"
            v-model="perihal"
            @keyup="onUpdateInput('perihal')"
            :class="{ 'is-error': checkError('perihal') }"
            id="input_perihal"
            placeholder="Perihal"
          />
          <p class="form-input-hint">{{ errorForm.perihal || "" }}</p>
        </div>
        <div class="column form-group col-6 col-md-12">
          <label class="text-primary form-label" for="input_asalsurat"
            >Asal Surat</label
          >
          <input
            class="form-input"
            type="text"
            v-model="asalSurat"
            @keyup="onUpdateInput('asalSurat')"
            :class="{ 'is-error': checkError('asalSurat') }"
            id="input_asalsurat"
            placeholder="Nama Perusahaan/Instansi"
          />
          <p class="form-input-hint">{{ errorForm.asalSurat || "" }}</p>
        </div>
        <div class="column form-group col-6 col-md-12">
          <label class="text-primary form-label" for="input_tanggalsurat"
            >Tanggal Surat</label
          >
          <!-- <input class="form-input" type="date" id="input_tanggalsurat" name="tanggalsurat" /> -->
          <datepicker
            id="input_tanggalsurat"
            v-model="tanggalSurat"
            :min="min"
            :max="max"
          />
        </div>
        <div class="column form-group col-6 col-md-12">
          <label class="text-primary form-label" for="input_nosurat"
            >Nomor Surat</label
          >
          <input
            @keyup="onUpdateInput('noSurat')"
            :class="{ 'is-error': checkError('noSurat') }"
            class="form-input"
            type="text"
            id="input_nosurat"
            v-model="noSurat"
            placeholder="Nomor Surat yang tertera"
          />
          <p class="form-input-hint">{{ errorForm.noSurat || "" }}</p>
        </div>
        <div class="column form-group col-6 col-md-12">
          <label class="text-primary form-label" for="input_sifatsurat"
            >Sifat Surat</label
          >
          <select
            :class="{ 'is-error': checkError('idSifat') }"
            class="form-select"
            id="input_sifatsurat"
            v-model="idSifat"
            placeholder="Sifat dari surat tersebut"
          >
            <option :value="0">--Pilih salah satu sifat surat--</option>
            <option
              v-for="(val, key) in optionsSifat"
              :key="val.id"
              :value="key + 1"
            >
              {{ val.value }}
            </option>
          </select>
          <p class="form-input-hint">{{ errorForm.idSifat || "" }}</p>
        </div>
      </div>
    </div>
    <div class="container form-container">
      <h5 class="text-primary">Informasi Lainnya</h5>
      <div class="divider"></div>
      <p class="text-primary text-justify col-8 col-md-12">
        Form di bagian ini berisi data-data yang opsional untuk di isi. Terdapat
        tembusan surat dan isi ringkas dari surat.
      </p>
      <input-autocomplete
        :is-server-data="true"
        :url="urlCopy"
        :can-tag="true"
        :error-message="errorForm.tembusan || ''"
        :error="checkError('tembusan')"
        v-model="tembusan"
        name="tembusan"
        label="Tembusan"
        column="8"
        placeholder="Pihak yang akan diberi tembusan surat"
      />
      <div class="form-group col-8 col-md-12">
        <label class="text-primary form-label" for="input_isiringkas"
          >Isi Ringkas</label
        >
        <textarea
          class="form-input"
          v-model="isiRingkas"
          id="input_isiringkas"
          placeholder="Isi surat secara ringkas"
        />
      </div>
    </div>
    <!--eslint-disable-next-line vue/no-multiple-template-root-->
    <div class="container form-container">
      <h5 class="text-primary">Upload File</h5>
      <div class="divider"></div>
      <p class="text-primary text-justify col-8 col-md-12">
        Form di bagian ini digunakan untuk mengupload file-file berkaitan dengan
        surat tersebut seperti scan file ataupun gambar. File yang bisa di
        upload adalah file dokumen, gambar, dan file kompres. Maksimal file yang
        bisa diupload berjumlah 6 file.
      </p>
      <div class="form-group col-12 col-md-12">
        <input-file v-model="files"></input-file>
      </div>
    </div>
    <div class="container form-container">
      <button
        @click="onSubmit"
        class="btn btn-lg btn-success col-md-12 col-6 p-centered"
      >
        Buat Surat
      </button>
    </div>
  </form>
  <!-- eslint-disable-next-line vue/no-multiple-template-root -->
  <loading-screen
    @click:cancel="onCancelLoading"
    :show="lShow"
    :value="lValue"
  ></loading-screen>
</template>

<script>
import InputAutocomplete from "../../Components/InputAutoComplete";
import TitleBar from "../../Components/TitleBar.vue";
import { Path } from "../../constants/path";
import { reactive, readonly, ref, toRefs, watch } from "vue";
import { DateTime } from "luxon";
// import config from "../../constants/config";
import Datepicker from "../../Components/Datepicker.vue";
import { helper } from "../../Components/DatepickerFunction";
import InputFile from "@@/Components/InputFile.vue";
import { form, random, validation } from "@@/Components/UtilityFunction";
import { Inertia } from "@inertiajs/inertia";
import LoadingScreen from "@@/Components/LoadingScreen.vue";

export default {
  components: {
    TitleBar,
    InputAutocomplete,
    Datepicker,
    InputFile,
    LoadingScreen,
  },
  props: {
    dyProps: {
      type: Object,
      default: function () {
        return { page: "" };
      },
    },
    title: String,
  },
  setup(props) {
    const rules = [
      {
        name: "noSurat",
        label: "Nomor Surat",
        type: "text",
        rules: { required: true },
      },
      {
        name: "perihal",
        label: "Perihal",
        type: "text",
        rules: { required: true },
      },
      {
        name: "asalSurat",
        label: "Asal Surat",
        type: "text",
        rules: { required: true },
      },
      {
        name: "idSifat",
        label: "Sifat Surat",
        type: "select",
        rules: { required: true, max: 4, min: 1 },
      },
      {
        name: "tembusan",
        label: "Tembusan",
        type: "tags",
        rules: { max: 5 },
      },
    ];
    const optionsSifat = [
      { id: random.string(5), value: "Biasa" },
      { id: random.string(5), value: "Segera" },
      { id: random.string(5), value: "Penting" },
      { id: random.string(5), value: "Rahasia" },
    ];
    const urlCopy = Path.contentsHome[props.dyProps.page].getCopy;
    const canceledPost = ref("");
    const datepickerConfig = readonly({
      max: helper.today.endOf("day").toLocaleString(DateTime.DATE_SHORT),
      min: DateTime.local(1945, 1, 1, 0, 0, 0, 0).toLocaleString(
        DateTime.DATE_SHORT
      ),
    });
    const inputForm = reactive({
      noSurat: "",
      perihal: "",
      asalSurat: "",
      idSifat: 0,
      tanggalSurat: helper.today
        .endOf("day")
        .toLocaleString(DateTime.DATE_SHORT),
      isiRingkas: "",
      files: [],
      tembusan: [],
    });
    const errorForm = ref({});
    const loadingConfig = reactive({
      lShow: false,
      lValue: 0,
    });

    watch(
      () => inputForm.tembusan,
      () => {
        if (checkError("tembusan")) {
          errorForm.value.tembusan = "";
        }
      }
    );

    watch(
      () => inputForm.idSifat,
      () => {
        if (checkError("idSifat")) {
          errorForm.value.idSifat = "";
        }
      }
    );

    function onSubmit() {
      console.log(form.validateForm(rules, inputForm));
      let validate = form.validateForm(rules, inputForm);
      if (!validate.valid) {
        errorForm.value = validate.error;
        window.scrollTo(0, 0);
        return;
      }
      Inertia.post("/mails/inbox/create", inputForm, {
        onCancelToken: (cancelToken) => (canceledPost.value = cancelToken),
        onStart: () => {
          loadingConfig.lShow = true;
        },
        onProgress: (progress) => {
          console.log(progress);
          loadingConfig.lValue = progress.percentage;
        },
        onSuccess: (page) => {
          loadingConfig.lShow = false;
          console.log(page);
        },
        onFinish: () => {
          loadingConfig.lShow = false;
          loadingConfig.lValue = 0;
        },
      });
    }
    function onCancelLoading() {
      canceledPost.value.cancel();
    }
    function onUpdateInput(key) {
      if (checkError(key)) {
        errorForm.value[key] = "";
      }
    }
    function checkError(key) {
      //   console.log(
      //     errorForm.value[key],
      //     validation.isNotEmpty(errorForm.value[key])
      //   );
      if (typeof errorForm.value[key] == "undefined") return false;
      return validation.isNotEmpty(errorForm.value[key]);
    }

    return {
      ...toRefs(inputForm),
      ...toRefs(datepickerConfig),
      ...toRefs(loadingConfig),
      checkError,
      canceledPost,
      errorForm,
      optionsSifat,
      onSubmit,
      onCancelLoading,
      onUpdateInput,
      urlCopy,
      //   tembusanSearch,
    };
  },
};
</script>

<style scoped>
.form-container {
  margin-top: 2em;
}
/* div {
  min-height: 50vh;
  overflow-y: initial;
} */
</style>
