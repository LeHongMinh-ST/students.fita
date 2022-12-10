<template>
  <div class="upload-wrapper">
    <div>
      <div :class="['dropZone', dragging ? 'dropZone-over' : '']" @dragenter="dragging = true"
           @dragleave="dragging = false">
        <div class="dropZone-info" @drag="onChange">
          <span class="fa fa-cloud-upload dropZone-title"></span>
          <span class="dropZone-title">Thả hoặc chọn để tải tệp lên</span>
          <div class="dropZone-upload-limit-info">
            <div>{{ file.name }}</div>
          </div>
        </div>
        <input type="file" @input="onChange">
      </div>
    </div>
  </div>

</template>

<script lang="ts">
import {defineEmits , defineComponent, ref} from "vue"

export default defineComponent({
  name: "Upload",
  setup(props, { emit }) {
    const file = ref<object>({})
    const dragging = ref<boolean>(false)

    const onChange = e => {
      const files = e.target.files || e.dataTransfer.files;

      if (!files.length) {
        this.dragging = false;
        return
      }

      file.value = files[0]
      dragging.value = false

      emit('changeFile', {file: file.value})
    }


    return {onChange, file, dragging}
  }
})
</script>

<style scoped lang="scss">
.dropZone {
  height: 200px;
  position: relative;
  border: 2px dashed #eee;
}

.dropZone:hover {
  border: 2px solid #2e94c4;
}

.dropZone:hover .dropZone-title {
  color: #1975A0;
}

.dropZone-info {
  color: #A8A8A8;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translate(0, -50%);
  text-align: center;
}

.dropZone-title {
  color: #787878;
}

.dropZone input {
  position: absolute;
  cursor: pointer;
  top: 0px;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
}

.dropZone-upload-limit-info {
  display: flex;
  justify-content: flex-start;
  flex-direction: column;
}

.dropZone-over {
  background: #5C5C5C;
  opacity: 0.8;
}

.dropZone-uploaded {
  width: 80%;
  height: 200px;
  position: relative;
  border: 2px dashed #eee;
}

.dropZone-uploaded-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: #A8A8A8;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: translate(0, -50%);
  text-align: center;
}

.removeFile {
  width: 200px;
}
</style>