<template>
    <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Unidades de medidas
        </h2>
        <jet-primary-button :type="'button'" @click="this.triggermodal = true">Agregar <i class="fa fa-plus"></i></jet-primary-button>
      </div>
    </template>
        <div>
      <div class="container mx-auto lg:px-40 mt-10">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Acciones
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="unit in unit_of_measurements.data" :key="unit.id" class="hover:bg-yellow-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{unit.id}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{unit.name}}</div>
                        </td>
                        <td class="lg:pb-10 lg:pl-8 md:px-4 py-4 text-sm text-center">
                          <dropdown align="right" width="40">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                  <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                    <i class="fa fa-cog"></i>
                                  </button>
                                </span>
                            </template>
                            <template #content>
                              <div class="block px-4 py-2 text-xs text-gray-400">
                                Acciones
                              </div>
                              <jet-dropdown-link :as="'button'" @click="editUnit(unit)">
                                <i class="fa fa-edit"></i>Editar
                              </jet-dropdown-link>
                              <div class="border-t border-gray-100"></div>
                            </template>
                          </dropdown>
                        </td>
                      </tr>
                      <tr v-if="unit_of_measurements.data.length == 0">
                        <td class="px-6 py-4 whitespace-nowrap" colspan="6">
                          <p class="text-sm text-center">No hay registros</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="flex justify-center">
              <pagination class="mt-4" :links="unit_of_measurements.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar unidad de medida
      </template>
      <template #content>
        <div class="grid grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text" autofocus></jet-input>
            <jet-input-error  v-if="this.errors['name']" :message="this.errors['name'][0]"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-4">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="saveUnitMeasurements()">Agregar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <dialog-modal :show="triggerModalEditUnit"  @close="triggerModalEditUnit = false">
      <template #title>
        Editar unidad de medida
      </template>
      <template #content>
        <div class="grid grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text"></jet-input>
            <jet-input-error  v-if="this.errors['name']" :message="this.errors['name'][0]"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-4">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="updateUnitMeasurements()">Actualizar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
  </app-layout>
</template>

<script>
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
    props: {
      unit_of_measurements: Object
    },
    setup() {
      const toast = useToast();
      return { toast}
    },
    data() {
      return {
        triggermodal: false,
        triggerModalEditUnit: false,
        form: {
          name: ''
        },
        selectedUnitMeasurement: {},
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
        errors: {}
      }
    },
    methods: {
      closeModal: function() {
        this.triggermodal =  false;
        this.triggerModalEditUnit =  false;
        this.resetData();
      },
      saveUnitMeasurements: function(){
        axios.post(route('unit_of_measurement.store'), this.form)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.reload({ only: ['unit_of_measurements'] })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
          this.triggermodal = false;
          this.resetData();
          this.errors = {};
        })
        .catch(error => {
          if(error.response){
            this.errors = error.response.data.errors;
          }else{
            this.toast.error('Ocurrió un error desconocido al agregar la unidad de medida, intente más tarde o contacte al administrador del sistema.', this.toastconfig);
          }
          this.resetData();
        });
      },
      editUnit: function(unit){
        this.triggerModalEditUnit = true;
        this.selectedUnitMeasurement = unit;
        this.form.name = unit.name;
      },
      updateUnitMeasurements:function(){
        axios.patch(route('unit_of_measurement.update', {unit_of_measurement: this.selectedUnitMeasurement.id}),this.form)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.reload({ only: ['unit_of_measurements'] })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
          this.triggerModalEditUnit = false;
          this.resetData();
          this.errors = {};
        })
        .catch(error => {
          if(error.response){
            this.errors = error.response.data.errors;
          }else{
            this.toast.error('Ocurrió un error desconocido al agregar la unidad de medida, intente más tarde o contacte al administrador del sistema.', this.toastconfig);
          }
        });
      },
      resetData: function(){
        this.form.name = '';
        this.errors = {};
      }
    },
  }
</script>