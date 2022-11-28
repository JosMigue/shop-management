<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Presentaciones de mango
        </h2>
        <jet-primary-button :type="'button'" @click="openModal()">Agregar <i class="fa fa-plus"></i></jet-primary-button>
      </div>
    </template>
    <div>
      <div class="container mx-auto lg:px-40 mt-10">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="grid lg:grid-cols-3 grid-cols-1 gap-4">
            <div v-for="product in products.data" :key="product.id" class="w-full bg-white shadow-lg rounded-lg my-4">
              <div class="px-4 py-6">
                <div class="">
                  <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 -mt-1">{{product.name}}</h2>
                    <jet-primary-button :as="'button'" @click="editPresentation(product)">
                      <i class="fa fa-edit"></i> Editar
                    </jet-primary-button>
                  </div>
                  <div v-for="(unit_price, key) in product.unit_presentation_price" :key="key">
                    <p class="mt-3 text-gray-700 text-sm">
                      {{unit_price.name}} : ${{parseFloat(unit_price.pivot.current_price).toFixed(2)}}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-if="products.data.length == 0">
            <p class="text-center font-bold">No se encontraron registros...</p>
          </div>
          <div class="flex justify-center">
            <pagination class="mt-4" :links="products.links" />
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar producto
      </template>
      <template #content>
        <div class="grid grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text" autofocus></jet-input>
            <jet-input-error v-if="this.errors.name" :message="this.errors.name[0]"></jet-input-error>
          </div>
          <div class="grid grid-cols-3 gap-4" v-for="(unit_of_measurement, index) in unit_of_measurements" :key="index">
            <div class="col-span-1 flex items-center">
              {{unit_of_measurement.name}}
            </div>
            <div class="col-span-2">
              <jet-label :value="'Precio'"></jet-label>
              <div class="block relative">
                <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                  <i class="h-4 w-4 fill-current text-yellow-500 fa fa-dollar-sign"></i>
                </span>
                <jet-input  class="pl-6" :modelValue="form.unit[index].current_price" v-model="form.unit[index].current_price" type="number" step=".01" min="1"></jet-input>
              </div>
              <jet-input-error v-if="this.errors['unit.'+index+'.current_price']" :message="this.errors['unit.'+index+'.current_price'][0]"></jet-input-error>
            </div>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="savePresentation()">Agregar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <dialog-modal :show="triggermodaleditpresentation"  @close="triggermodaleditpresentation = false">
      <template #title>
        Actualizar presentación
      </template>
      <template #content>
        <div class="grid grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text"></jet-input>
            <jet-input-error :message="this.errors.name"></jet-input-error>
          </div>
          <div class="grid grid-cols-2 gap-4" v-for="(unit_of_measurement, index) in unit_of_measurements" :key="index">
            <div>
              {{unit_of_measurement.name}}
            </div>
            <div>
              <jet-label :value="'Precio'"></jet-label>
              <jet-input :modelValue="form.unit[index].current_price" v-model="form.unit[index].current_price" type="number" step=".01" min="1"></jet-input>
              <jet-input-error v-if="this.errors['unit.'+index+'.current_price']" :message="this.errors['unit.'+index+'.current_price'][0]"></jet-input-error>
            </div>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="updatePresentation()">Actualizar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
  </app-layout>
</template>
<script>
  import { toRefs} from 'vue'
  import { useToast } from "vue-toastification";
  import { Inertia } from '@inertiajs/inertia';
  import AppLayout from '@/Layouts/AppLayout';
  import Pagination from '@/Components/Pagination';
  import DialogModal from '@/Jetstream/DialogModal';
  import JetPrimaryButton from '@/Components/PrimaryButton';
  import JetSecondaryButton from '@/Jetstream/SecondaryButton';
  import JetInput from '@/Jetstream/Input';
  import JetLabel from '@/Jetstream/Label';
  import JetInputError from '@/Jetstream/InputError';
  import Dropdown from '@/Components/Dropdown';
  import JetDropdownLink from '@/Jetstream/DropdownLink';
  export default {
    components: {
      AppLayout,
      Pagination,
      DialogModal,
      JetPrimaryButton,
      JetSecondaryButton,
      JetInput,
      JetLabel,
      JetInputError,
      Dropdown,
      JetDropdownLink
    },
    setup() {
      const toast = useToast();
      return { toast }
    },
    props: {
      products: Object,
      unit_of_measurements: Object
    },
    mounted () {
      const units = toRefs(this.unit_of_measurements);
      units.forEach(unit => {
        this.form.unit.push({unit_of_measurement_id: unit.value.id, current_price: ''});
      });
    },
    data() {
      return {
        triggermodal: false,
        triggermodaleditpresentation: false,
        form: {
          name: null,
          unit: []
        },
        toastconfig: {
          position: "bottom-right",
          timeout: 3010,
          closeOnClick: true,
          pauseOnFocusLoss: true,
          pauseOnHover: true,
          draggable: true,
          draggablePercent: 0.6,
          showCloseButtonOnHover: false,
          hideProgressBar: true,
          closeButton: "button",
          icon: true,
          rtl: false
        },
        selectedPresentation: '',
        errors: {},
      }
    },
    methods: {
      closeModal:function() {
        this.triggermodal = false;
        this.triggermodaleditpresentation =  false;
        this.errors = {};
      },
      openModal: function(){
        this.triggermodal = true;
        this.resetData();
      },
      editPresentation:function(presentation){
        this.resetData();
        this.form.name = presentation.name;
        this.form.unit.forEach((element, index) => {
          presentation.unit_presentation_price.forEach((unit, index) => {
            if(unit){
              if(element.unit_of_measurement_id == unit.pivot.unit_of_measurement_id){
                element.current_price = unit.pivot.current_price;
              }
            }else{
              element.current_price = '';
            }
          })
        }); 
        this.selectedPresentation = presentation;
        this.triggermodaleditpresentation = true;
      },
      updatePresentation: function(){
        axios.patch(`/mango_presentations/${this.selectedPresentation.id}`, this.form)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/mango_presentations', {
              only: ['products'],
            })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
          this.triggermodal = false;
          this.errors = {};
        })
        .catch(error => {
          if(error.response.data){
            this.errors = error.response.data.errors
          }else{
            this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
          }
        });
      },
      savePresentation: function() {
        axios.post('/mango_presentations', this.form )
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/mango_presentations', {
              only: ['products'],
            })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
          this.triggermodal = false;
          this.errors = {};
          this.resetData();
        })
        .catch(error => {
          if(error.response.data){
            this.errors = error.response.data.errors;
          }else{
            this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
          }
        })
      },
      resetData:function(){
        this.form.name = null;
        this.form.unit.forEach(element => {
          element['current_price'] = '';
        });
      }
    },
  };
</script>