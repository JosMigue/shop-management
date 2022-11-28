<template>
  <jet-action-section>
    <template #title>
      Update logo
    </template>
    <template #description>
      Change current logo application
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600">
				Si necesita cambiar el logo de la empresa puede hacerlo desde esta sección.
			</div>
      <div class="mt-5">
        <jet-button @click="uploadFile = !uploadFile">
          Subir logo
        </jet-button>
      </div>
      <transition enter-to-class="opacity-100 translate-y-0" enter-from-class="opacity-0 -translate-y-6" enter-active-class="transition ease-in duration-200" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0 -translate-y-6">
        <div v-if="uploadFile">
          <transition name="list">
            <div v-if="form.setting_resource_path" class="flex justify-center">
              <div class="block p-1 h-auto lg:w-1/3 w-1/2">
                <article tabindex="0" class="group w-full h-full rounded-md focus:outline-none focus:shadow-outline elative bg-gray-100 cursor-pointer relative shadow-sm">
                  <section class="flex flex-col rounded-md text-xs break-words w-full h-full top-0 z-20 py-4 px-3">
                    <img :src="preview"  class="img-preview w-full h-full sticky object-cover rounded-md bg-fixed" alt="Preview upload image">
                    <div class="flex">
                      <span class="p-1 text-blue-800">
                        <i>
                          <svg class="fill-current w-4 h-4 ml-auto pt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M15 2v5h5v15h-16v-20h11zm1-2h-14v24h20v-18l-6-6z" />
                          </svg>
                        </i>
                      </span>
                      <p class="p-1 size text-xs text-gray-700">{{form.setting_resource_path.name}}</p>
                      <button class="bg-red-500 ml-auto focus:outline-none hover:bg-gray-300 p-1 rounded-md text-gray-800" @click="deleteFiles">
                        <svg class="pointer-events-none fill-current w-4 h-4 ml-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                          <path class="pointer-events-none" d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z" />
                        </svg>
                      </button>
                    </div>
                  </section>
                </article>
              </div>
            </div>
          </transition>
          <transition name="list">
            <div v-if="!form.setting_resource_path" class="grid grid-cols-1 space-y-2">
              <label-required>Adjunte un documento</label-required>
              <div class="flex items-center justify-center w-full" v-cloak @drop.prevent="addFile" @dragover.prevent="dragingfile=true"  @dragleave.prevent="dragingfile=false">
                <label class="flex flex-col rounded-lg border-4 border-dashed w-full h-56 p-10 group text-center" :class="{'border-indigo-500': dragingfile}">
                  <div class="h-full w-full text-center flex flex-col items-center justify-center items-center  ">
                    <div class="flex flex-auto justify-center items-center max-h-36 w-2/5 mx-auto -mt-10">
                    <i v-if="!dragingfile" class="text-6xl fa fa-cloud-upload-alt"></i>
                    <i v-else class="text-6xl fa fa-arrow-down animate-bounce"></i>
                    </div>
                    <p class="pointer-none text-gray-500 hidden sm:block"><span class="text-sm">Arrastre y suelte </span> los archivos aquí <br /> o <span class="text-blue-500 hover:underline cursor-pointer">seleccione un archivo desde su computadora</span></p>
                    <p class="pointer-none text-gray-500 block sm:hidden"><span class="text-sm">Seleccione archivos</span> al dar tap aquí desde su dispositivo movil</p>
                  </div>
                  <input type="file" class="hidden" v-on:change="addFile">
                </label>
              </div>
            </div>
          </transition>
          <input-error :message="form.errors.setting_resource_path"></input-error>
          <div class="flex justify-between">
            <p class="text-sm text-gray-400">
              <span>Archivos permitidos: png, jpg, jpeg</span>
            </p>
            <jet-button v-if="form.setting_resource_path" @click="sumbitFile()" :disabled="form.progress" :class="form.progress ? 'opacity-75' : ''" type="button" class="bg-primary-500 hover:bg-primary-400">Subir <i class="fa fa-upload mx-1"></i></jet-button>
          </div>
        </div>
      </transition>
      <jet-input-error :message="form.errors.file" ></jet-input-error>
      <progress v-if="form.progress" class="w-full bg-blue text-white" :value="form.progress.percentage" max="100">
        {{ form.progress.percentage }}%
      </progress>
      <div v-if="form.progress">
        <p class="text-blue-300 text-md">Procesando, por favor espere <i class="fa fa-spinner fa-spin"></i></p>
      </div>
    </template>
  </jet-action-section>
</template>
<script>
import JetActionSection from '@/Jetstream/ActionSection'
import { useForm } from '@inertiajs/inertia-vue3'
export default ({
  components: {
    JetActionSection
  },
  setup () {
    const form = useForm({
      type: 1,
      setting_resource_path: null,
    });
    return {form};
  },
  data() {
    return {
      uploadFile: true,
      dragingfile: false,
      preview: null
    }
  },
  methods: {
    addFile: function(event) {
      this.dragingfile = false;
      const files = event.target.files || event.dataTransfer.files;
      if (!files.length)
        return;
      this.form.setting_resource_path = files[0];
      this.preview = URL.createObjectURL(this.form.setting_resource_path);
    },
    deleteFiles: function(){
      this.preview = null;
      setTimeout(() => {
        this.form.reset();
      }, 500);
        this.toast.success('Archivo quitado correctamente',this.$data.toastConfig);
    },
    submitFile: function(){
      this.form.post(route('settings.store'),{
        preserveScroll: true,
        onSuccess: () => {
          this.toast.success('Agregado correctamente', this.toastconfig);
          this.form.reset();
          setTimeout(() => {
            this.uploadFile = false;
          }, 500);
        }
      })
    },
	}
})
</script>

<style>
	.list-enter,
	.list-leave-to {
		visibility: hidden;
		height: 0;
		margin: 0;
		padding: 0;
		opacity: 0;
	}
	.list-enter-active,
	.list-leave-active {
		transform-origin: top center;
		transition: all 0.3s;
	}
</style>