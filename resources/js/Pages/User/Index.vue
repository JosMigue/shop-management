<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Usuarios
        </h2>
        <jet-primary-button :type="'button'" @click="this.triggermodal = true">Agregar <i class="fa fa-plus"></i></jet-primary-button>
      </div>
    </template>
    <div>
      <div class="container mx-auto lg:px-40 mt-10">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex flex-col">
            <div class="my-2 flex sm:flex-row flex-col">
              <div class="block relative">
                <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                  <i class="h-4 w-4 fill-current text-yellow-500 fa fa-search"></i>
                </span>
                <jet-input class="pl-8" :modelValue="dataTable.search" placeholder="búsqueda..." v-model="dataTable.search" type="search"></jet-input>
              </div>
            </div>
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Rol
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Fecha de registro
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Acciones
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="user in users.data" :key="user.id" class="hover:bg-yellow-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="flex items-center">
                            <div class="ml-4">
                              <div class="text-sm font-medium text-gray-900">
                                {{user.name}}
                              </div>
                              <div class="text-sm text-gray-500">
                                {{user.email}}
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{user.role.name}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span v-if="user.deleted_at" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            No activo
                          </span>
                          <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Activo
                          </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{user.created_at}}</div>
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
                              <jet-dropdown-link v-if="user.deleted_at" :as="'button'" @click="enableUser(user)">
                                <i class="fa fa-user-check"></i> Habilitar
                              </jet-dropdown-link>
                              <jet-dropdown-link v-else :as="'button'" @click="confirmUserDisable(user)">
                                <i class="fa fa-user-times"></i> Deshabilitar
                              </jet-dropdown-link>
                              <div class="border-t border-gray-100"></div>
                            </template>
                          </dropdown>
                        </td>
                      </tr>
                      <tr v-if="users.data.length == 0">
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
              <pagination class="mt-4" :links="users.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar usuario
      </template>
      <template #content>
        <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Nombre'"></jet-label>
            <jet-input :modelValue="form.name" v-model="form.name" type="text"></jet-input>
            <jet-input-error :message="this.form.errors.name"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Correo'"></jet-label>
            <jet-input :modelValue="form.email" v-model="form.email" type="email"></jet-input>
            <jet-input-error :message="this.form.errors.email"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Contraseña'"></jet-label>
            <jet-input :modelValue="form.password" v-model="form.password" type="password"></jet-input>
            <jet-input-error :message="this.form.errors.password"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Confirmación de contraseña'"></jet-label>
            <jet-input :modelValue="form.password_confirmation" v-model="form.password_confirmation" type="password"></jet-input>
            <jet-input-error :message="this.form.errors.password_confirmation"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Rol'"></jet-label>
            <select v-model="form.role_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full">
              <option value="" disabled>Seleccione un rol</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>
            </select>
            <jet-input-error :message="this.form.errors.role_id"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="submit()">Agregar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <jet-confirmation-modal :show="triggerConfirmDisableUserAction"  @close="triggerConfirmDisableUserAction = false">
      <template #title>
        ¿Está seguro que desea desactivar este usuario? 
      </template>
      <template #content>
        Esta acción solo desactivará al usuario, no lo eliminará, puede deshacer esta acción cuando usted lo deseé
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="disableUser()">Desactivar</jet-primary-button>
        </div>
      </template>
    </jet-confirmation-modal>
  </app-layout>
</template>
<script>
  import { useToast } from "vue-toastification";
  import { Inertia } from '@inertiajs/inertia';
  import { useForm } from '@inertiajs/inertia-vue3'
  import pickBy from 'lodash/pickBy';
  import throttle from 'lodash/throttle';
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
      const form = useForm({
          name: null,
          email: null,
          password: null,
          password_confirmation: null,
          role_id: ''
      });
      return { toast, form }
    },
    props: {
      users: Object,
      roles: Object,
      filters: Object
    },
    data() {
      return {
        triggermodal: false,
        triggerConfirmDisableUserAction: false,
        dataTable: {
          search: this.filters.search
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
        selectedUser: ''
      }
    },
    methods: {
      closeModal:function() {
        this.triggermodal = false;
        this.triggerConfirmDisableUserAction = false;
        this.form.reset();
      },
      submit: function() {
        this.form.post(this.route('users.store'),{
          preserveScroll: true,
          onSuccess: () => {
            this.toast.success('Registrado correctamente', this.toastconfig);
            this.closeModal();
          },
          onError: error => {
            this.toast.error('Vaya hemos encontrado algunos errores.', this.toastconfig);
          }
        });
      },
      enableUser: function(user){
        axios.patch(`/users/restore/${user.id}`)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/users', {
              only: ['users','roles'],
            })
          }else{
            this.toast.error(response.data.message, this.toastconfig);
          }
        })
        .catch(error => {
          this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
        })
      },
      confirmUserDisable: function(user){
        this.selectedUser = user;
        this.triggerConfirmDisableUserAction = true;
      },
      disableUser: function(){
        axios.delete(`/users/${this.selectedUser.id}`)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/users', {
              only: ['users','roles'],
            })
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
        })
        .catch(error => {
          this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
        })
      }
    },
    watch: {
      dataTable: {
        deep: true,
        handler: throttle(function(){
          this.$inertia.get('users', pickBy(this.dataTable), {preserveScroll: true, replace:true})
        },150)
      }
    },
  };
</script>