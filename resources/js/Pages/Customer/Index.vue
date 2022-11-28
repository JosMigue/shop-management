<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Clientes
        </h2>
        <jet-primary-button :type="'button'" @click="openModalAddCustomer()">Agregar <i class="fa fa-plus"></i></jet-primary-button>
      </div>
    </template>
    <div>
      <div class="container mx-auto lg:px-40 mt-10">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="my-2 flex sm:flex-row flex-col">
            <div class="block relative">
              <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                <i class="h-4 w-4 fill-current text-yellow-500 fa fa-search"></i>
              </span>
              <jet-input class="pl-8" :modelValue="dataTable.search" placeholder="búsqueda..." v-model="dataTable.search" type="search"></jet-input>
            </div>
          </div>
          <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 py-4 overflow-y-auto">
            <div class=" shadow border-b border-gray-200 inline-block min-w-full rounded-lg">
              <table class="min-w-full leading-normal">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Teléfono
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Estatus
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Fechade registro
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-blue-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{customer.name}}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{customer.email}}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900"><a class="hover:text-blue-500 hover:underline" :href="'tel:'+customer.phone" >{{customer.phone}}</a></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span v-if="customer.deleted_at" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        No activo
                      </span>
                      <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Activo
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">
                        {{customer.created_at}}
                      </div>
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
                          <jet-dropdown-link :as="'button'" @click="editCustomer(customer)">
                            <i class="fa fa-edit"></i> Editar
                          </jet-dropdown-link>
                          <jet-dropdown-link v-if="customer.deleted_at" :as="'button'" @click="enableCustomer(customer)">
                            <i class="fa fa-user-check"></i> Habilitar
                          </jet-dropdown-link>
                          <jet-dropdown-link v-else :as="'button'" @click="confirmCustomerDisable(customer)">
                            <i class="fa fa-user-times"></i> Deshabilitar
                          </jet-dropdown-link>
                          <div class="border-t border-gray-100"></div>
                        </template>
                    </dropdown>
                    </td>
                  </tr>
                  <tr v-if="customers.data.length == 0">
                    <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                      <p class="text-sm text-center">No hay registros</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="flex justify-center">
            <pagination class="mt-4" :links="customers.links" />
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar cliente
      </template>
      <template #content>
        <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text"></jet-input>
            <jet-input-error v-if="this.errors.name" :message="this.errors.name[0]"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Correo'"></jet-label>
            <jet-input :modelValue="form.email" v-model="form.email" type="email"></jet-input>
            <jet-input-error v-if="this.errors.email" :message="this.errors.email[0]"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Teléfono'"></jet-label>
            <jet-input :modelValue="form.phone" v-model="form.phone" type="text"></jet-input>
            <jet-input-error v-if="this.errors.phone" :message="this.errors.phone[0]"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="saveCustomer()">Guardar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <dialog-modal :show="triggermodalupdate"  @close="triggermodalupdate = false">
      <template #title>
        Actualizar cliente
      </template>
      <template #content>
        <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text"></jet-input>
            <jet-input-error v-if="this.errors.name" :message="this.errors.name[0]"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Correo'"></jet-label>
            <jet-input :modelValue="form.email" v-model="form.email" type="email"></jet-input>
            <jet-input-error v-if="this.errors.email" :message="this.errors.email[0]"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Teléfono'"></jet-label>
            <jet-input :modelValue="form.phone" v-model="form.phone" type="text"></jet-input>
            <jet-input-error v-if="this.errors.phone" :message="this.errors.phone[0]"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="updateCustomer()">Actualizar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <jet-confirmation-modal :show="triggerConfirmDisableCustomerAction"  @close="triggerConfirmDisableCustomerAction = false">
      <template #title>
        ¿Está seguro que desea desactivar este cliente? 
      </template>
      <template #content>
        Esta acción solo desactivará el cliente, no lo eliminará, puede corregir esta acción cuando usted lo deseé
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="disableCustomer(customer)">Desactivar</jet-primary-button>
        </div>
      </template>
    </jet-confirmation-modal>
  </app-layout>
</template>
<script>
  import { useToast } from "vue-toastification";
  import { Inertia } from '@inertiajs/inertia';
  import pickBy from 'lodash/pickBy';
  import throttle from 'lodash/throttle';
  import AppLayout from '@/Layouts/AppLayout';
  import Pagination from '@/Components/Pagination';
  import DialogModal from '@/Jetstream/DialogModal';
  import JetPrimaryButton from '@/Components/PrimaryButton';
  import JetSecondaryButton from '@/Jetstream/SecondaryButton';
  import JetInput from '@/Jetstream/Input';
  import JetLabel from '@/Jetstream/Label';
  import Dropdown from '@/Components/Dropdown';
  import JetDropdownLink from '@/Jetstream/DropdownLink';
  import JetInputError from '@/Jetstream/InputError';
  import JetConfirmationModal from '@/Jetstream/ConfirmationModal';
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
      JetDropdownLink,
      JetConfirmationModal
    },
    setup() {
      const toast = useToast();
      return { toast }
    },
    props: {
      customers: Object,
      filters: Object
    },
    data() {
      return {
        triggermodal: false,
        triggermodalupdate: false,
        form: {
          name: null,
          email: null,
          phone: null
        },
        dataTable: {
          search: this.filters.search,
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
        errors: {},
        selectedCustomer: '',
        triggerConfirmDisableCustomerAction: false
      }
    },
    methods: {
      editCustomer:function(customer) {
        this.errors = {};
        this.selectedCustomer = customer;
        this.form.name = customer.name;
        this.form.email = customer.email;
        this.form.phone = customer.phone;
        this.triggermodalupdate = true;
      },
      openModalAddCustomer:function() {
        this.triggermodal = true;
        this.resetData();
      },
      closeModal:function() {
        this.triggermodal = false;
        this.triggermodalupdate = false;
        this.triggerConfirmDisableCustomerAction = false;
        this.errors = {};
      },
      saveCustomer: function() {
        axios.post('/customers', this.form )
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/customers', {
              only: ['customers'],
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
        })
      },
      updateCustomer: function() {
        axios.patch(`/customers/${this.selectedCustomer.id}`, this.form )
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/customers', {
              only: ['customers'],
            })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
          this.triggermodalupdate = false;
          this.errors = {};
        })
        .catch(error => {
          if(error.response.data){
            this.errors = error.response.data.errors
          }else{
            this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
          }
        })
        this.resetData();
      },
      disableCustomer: function(){
        axios.delete(`/customers/${this.selectedCustomer.id}`)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/customers', {
              only: ['customers'],
            })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
        })
        .catch(error => {
          this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
        })
      },
      confirmCustomerDisable: function(customer){
        this.triggerConfirmDisableCustomerAction =  true;
        this.selectedCustomer = customer;
      },
      enableCustomer: function(customer){
        axios.patch(`/customers/restore/${customer.id}`)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/customers', {
              only: ['customers'],
            })
          }else{
            this.toast.error(response.data.message, this.toastconfig);
          }
        })
        .catch(error => {
          this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
        })
      },
      resetData:function(){
        this.form.name = null;
        this.form.email = null;
        this.form.phone = null;
      }
    },
    watch: {
      dataTable:{
        deep: true,
        handler: throttle(function(){
          this.$inertia.get('customers', pickBy(this.dataTable),{preserveState: true, replace:true})
        }, 150)
      }
    },
  };
</script>