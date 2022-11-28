<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Transferencias
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
                          Usuario emisor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Usuario receptor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Cantidad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Acciones
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="transfer in transfers.data" :key="transfer.id" class="hover:bg-yellow-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{transfer.sender.name}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{transfer.receiver.name}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{currencyHelper(transfer.amount)}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{transfer.date}}</div>
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
                              <jet-dropdown-link :as="'button'" @click="confirmDeleteTransfer(transfer)">
                                <i class="fa fa-trash"></i> Eliminar
                              </jet-dropdown-link>
                              <div class="border-t border-gray-100"></div>
                            </template>
                          </dropdown>
                        </td>
                      </tr>
                      <tr v-if="transfers.data.length == 0">
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap">
                          <p class="w-full text-center text-sm text-gray-900">No se encontraron registros</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="flex justify-center">
              <pagination class="mt-4" :links="transfers.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar transferencia
      </template>
      <template #content>
        <div v-if="form.recentlySuccessful" class="p-3 mb-3 bg-green-100 rounded border border-green-300">
          Guardado correctamente
        </div>
        <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Usuario receptor'"></jet-label>
            <select v-model="form.receiver_id" class="border-gray-300 focus:yellow-indigo-300 focus:ring focus:border-yellow-400 focus:ring-yellow-200 focus:ring-opacity-50 rounded-md shadow-sm w-full">
              <option value="" disabled>Seleccione un usuario receptor</option>
              <option v-for="user in users" :key="user.id" :value="user.id">{{user.name}}</option>
            </select>
            <jet-input-error v-if="this.form.errors.receiver_id" :message="this.form.errors.receiver_id"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Monto'"></jet-label>
            <jet-input :modelValue="form.amount" v-model="form.amount" type="number" min="1" autofocus></jet-input>
            <jet-input-error :message="this.form.errors.amount"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Observación'"></jet-label>
            <jet-input :modelValue="form.observation" v-model="form.observation" type="text"></jet-input>
            <jet-input-error :message="this.form.errors.observation"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-4">
          <jet-secondary-button @click="closeModal()">Cerrar</jet-secondary-button>
          <jet-primary-button :loading="form.processing" :class="{ 'opacity-75': form.processing }" :disabled="form.processing" :type="'button'" @click="storeTransfer()">Agregar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <jet-confirmation-modal :show="triggerConfirmDeleteTransferAction"  @close="triggerConfirmDeleteTransferAction = false">
      <template #title>
        ¿Está seguro que desea eliminar esta transferencia? 
      </template>
      <template #content>
        Esta acción eliminará por completo el registro de la transferencia. Esta acción no se puede corregir.
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="deleteTransfer">Eliminar</jet-primary-button>
        </div>
      </template>
    </jet-confirmation-modal>
  </app-layout>
</template>

<script>
  import { useToast } from "vue-toastification";
  import { Inertia } from '@inertiajs/inertia';
  import { useForm } from '@inertiajs/inertia-vue3'
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
      JetConfirmationModal,
    },
    setup() {
      const toast = useToast();
      const form = useForm({
        receiver_id: '',
        amount: null,
        observations: null
      });
      return { toast, form}
    },
    props: {
      transfers: Object,
      users: Object
    },
    data() {
      return {
        triggerConfirmDeleteTransferAction: false,
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
        triggermodal: false,
        selectedTransfer: ''
      }
    },
    methods: {
      storeTransfer: function() {
        this.form.post(this.route('transfers.store'),{
          preserveScroll: true,
          onSuccess: () => {
            this.closeModal();
            this.toast.success('Agregado correctamente', this.toastconfig);
          }
        });
      },
      confirmDeleteTransfer: function(transfer){
        this.selectedTransfer = transfer;
        this.triggerConfirmDeleteTransferAction = true;
      },
      deleteTransfer: function(){
        axios.delete(this.route('transfers.destroy', this.selectedTransfer.id))
        .then(response => {
          if(response.data.code == 200){
            this.triggerConfirmDeleteExpenseAction = false;
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/transfers', {
              only: ['transfers','users'],
            })
          }else{
            this.triggerConfirmDeleteExpenseAction = false;
            this.toast.error(response.data.message, this.toastconfig);  
          }
        })
        .catch(error => {
          this.toast.error('Ocurrió un error desconocido, intente más tarde.', this.toastconfig);
        });
      },
      closeModal: function(){
        this.triggermodal =  false;
        this.triggerConfirmDeleteTransferAction =  false;
        this.form.reset();
        this.form.clearErrors();
      }
    },
  }
</script>

<style>

</style>