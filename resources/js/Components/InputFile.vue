<template>
  <!-- eslint-disable-next-line vue/no-multiple-template-root -->
  <div class="upload-container container columns col-oneline">
    <template v-if="files.length > 0">
      <div v-for="file in files" :key="file.id" class="column card col-3 mr-2">
        <div class="card-image text-center">
          <i :class="file.type"></i>
        </div>
        <div class="card-body">
          <p class="text-bold text-primary text-break">{{ file.name }}</p>
          <p class="text-primary">{{ file.size }}</p>
        </div>
        <div class="card-footer">
          <button
            @click="onDeleteFiles(file.id)"
            class="text-light btn btn-error col-12"
          >
            <i class="ri-delete-bin-2-fill ri-xl"></i>
          </button>
        </div>
      </div>
    </template>
  </div>
  <label
    v-if="files.length > 0"
    for="input_upload"
    :class="{ disabled: files.length >= maxFiles }"
    class="upload-btn btn btn-primary p-centered col-4 mt-2"
    >Unggah</label
  >
  <div v-if="files.length <= 0" class="empty">
    <div class="empty-icon">
      <i class="ri-upload-2-fill ri-4x"></i>
    </div>
    <p class="empty-title h5">{{ language.validation.noUploadFile }}</p>
    <p class="empty-subtitle">{{ language.uploadAction }}</p>
    <div class="empty-action">
      <label for="input_upload" class="upload-btn btn btn-primary"
        >Unggah</label
      >
    </div>
  </div>
  <!-- eslint-disable-next-line vue/no-multiple-template-root -->
  <input
    class="form-input d-none"
    type="file"
    multiple
    :disabled="files.length >= maxFiles"
    @change="onUploadFiles($event)"
    id="input_upload"
  />
</template>

<script>
import { inject, ref } from "vue";
import { classIconFiles, filterFileOnly } from "./InputFileFunction";
import { convert, random, validation } from "./UtilityFunction";
import { useToast } from "vue-toastification";
import { lang } from "@@/Constants/lang";
export default {
  props: {
    modelValue: { type: Array, required: true },
    max: { type: Number, default: 2048 },
    min: { type: Number, default: 0 },
    maxFiles: { type: Number, default: 6 },
  },
  emits: ["update:modelValue"],
  setup(props, { emit }) {
    const files = ref([]);
    const language = lang(inject("lang"));
    const optionsToast = inject("optionsToast");

    function onDeleteFiles(id) {
      files.value = files.value.filter((val) => {
        return val.id !== id;
      });
      emit("update:modelValue", filterFileOnly(files.value));
    }

    function onUploadFiles(event) {
      let fileList = event.target.files;
      console.log(event, fileList);
      if (fileList.length <= 0) {
        return;
      }
      Object.entries(fileList).forEach(([key, val]) => {
        let arrName = files.value.map((val) => {
          return val.name;
        });
        // console.log(arrName);
        if (arrName.includes(val.name, 0))
          useToast().error(
            language.validation.fileIsSame(val.name),
            optionsToast
          );
        else if (
          !validation.isValidExtension(val.name) ||
          typeof val == "undefined"
        )
          useToast().error(language.validation.unknownExtension, optionsToast);
        else if (
          props.max > 0 &&
          val.size > convert.byteBinarySize(props.max)
        ) {
          useToast().error(language.validation.sizeTooLarge, optionsToast);
        } else if (
          props.min > 0 &&
          val.size < convert.byteBinarySize(props.min)
        ) {
          useToast().error(language.validation.sizeTooSmall, optionsToast);
        } else if (files.value.length >= props.maxFiles) {
          useToast().error(language.validation.maximalFiles, optionsToast);
        } else {
          files.value.push({
            id: random.string(6),
            name: val.name,
            type: classIconFiles(val.type),
            file: val,
            size: convert.humanFileSize(val.size),
          });
        }
      });
      emit("update:modelValue", filterFileOnly(files.value));
    }
    return { files, language, onUploadFiles, onDeleteFiles };
  },
};
</script>

<style>
.upload-btn {
  margin-bottom: 40px;
}
</style>
