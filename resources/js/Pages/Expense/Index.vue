<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Gastos
        </h2>
        <jet-primary-button :type="'button'" @click="this.triggermodal = true">Agregar <i class="fa fa-plus"></i></jet-primary-button>
      </div>
    </template>
    <div>
      <div class="container mx-auto lg:px-40 mt-10">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="grid grid-cols-1 m-4 text-center"  v-if="$page.props.user.role_id != 4">
            <div class="p-4 w-full">
              <h2 class="title-font font-medium sm:text-4xl text-3xl text-gray-900">{{currencyHelper(this.currentTotalExpenses)}}</h2>
              <p class="leading-relaxed">Gastos del año actual</p>
            </div>
          </div>
          <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 m-4 text-center" v-else>
            <div class="p-4">
              <h2 class="title-font font-medium sm:text-4xl text-3xl text-gray-900">{{currencyHelper(this.currentTotalExpenses)}}</h2>
              <p class="leading-relaxed">Gastos realizados</p>
            </div>
            <div class="p-4">
              <h2 class="title-font font-medium sm:text-4xl text-3xl text-gray-900">{{currencyHelper($page.props.user.sum_transfers)}}</h2>
              <p class="leading-relaxed">Saldo transferido</p>
            </div>
            <div class="p-4">
              <h2 class="title-font font-medium sm:text-4xl text-3xl text-gray-900">{{currencyHelper(($page.props.user.sum_transfers + $page.props.user.sum_payments) - this.currentTotalExpenses)}}</h2>
              <p class="leading-relaxed">Saldo actual</p>
            </div>
          </div>
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Folio
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Cantidad
                        </th>
                        <th v-if="$page.props.user.role_id == 1 || $page.props.user.role_id == 2" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Usuario
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                          Concepto
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
                      <tr v-for="expense in expenses.data" :key="expense.id" class="hover:bg-blue-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{expense.ref}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{currencyHelper(expense.amount)}}</div>
                        </td>
                        <td v-if="$page.props.user.role_id == 1 || $page.props.user.role_id == 2" class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{expense.user.name}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{expense.concept}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <div class="text-sm text-gray-900">{{expense.created_at}}</div>
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
                              <jet-dropdown-link :as="'button'" @click="confirmDeleteExpense(expense)">
                                <i class="fa fa-trash"></i> Borrar
                              </jet-dropdown-link>
                              <div class="border-t border-gray-100"></div>
                            </template>
                          </dropdown>
                        </td>
                      </tr>
                      <tr v-if="expenses.data.length == 0">
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
              <pagination class="mt-4" :links="expenses.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar gasto
      </template>
      <template #content>
        <div v-if="form.recentlySuccessful && !$page.props.flash.error" class="p-3 mb-3 bg-green-100 rounded border border-green-300">
          Guardado correctamente
        </div>
        <div v-if="$page.props.flash.error" class="p-3 mb-3 bg-red-100 rounded border border-red-300">
          {{ $page.props.flash.error }}
        </div>
        <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
          <div>
            <jet-label :value="'Monto'"></jet-label>
            <jet-input :modelValue="form.amount" v-model="form.amount" type="number" min="1" autofocus></jet-input>
            <jet-input-error :message="this.form.errors.amount"></jet-input-error>
          </div>
          <div>
            <jet-label :value="'Concepto'"></jet-label>
            <jet-input :modelValue="form.concept" v-model="form.concept" type="text"></jet-input>
            <jet-input-error :message="this.form.errors.concept"></jet-input-error>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-4">
          <jet-secondary-button @click="closeModal()">Cerrar</jet-secondary-button>
          <jet-primary-button :loading="form.processing" :class="{ 'opacity-75': form.processing }" :disabled="form.processing" :type="'button'" @click="storeExpense()">Agregar</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <jet-confirmation-modal :show="triggerConfirmDeleteExpenseAction"  @close="triggerConfirmDeleteExpenseAction = false">
      <template #title>
        ¿Está seguro que desea eliminar este gasto? 
      </template>
      <template #content>
        Esta acción eliminará por completo el registro del gasto. Esta acción no se puede corregir.
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="deleteExpense">Eliminar</jet-primary-button>
        </div>
      </template>
    </jet-confirmation-modal>
  </app-layout>
</template>

<script>
  import { useToast } from "vue-toastification";
  import { useForm } from '@inertiajs/inertia-vue3'
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
        amount: null,
        concept: null
      });
      return { toast, form }
    },
    props: {
      expenses: Object,
      currentTotalExpenses: Number,
      previousTotalExpenses: Number
    },
    data() {
      return {
        triggermodal: false,
        triggerConfirmDeleteExpenseAction: false,
        selectedExpense: null,
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
        }
      }
    },
    methods: {
      storeExpense: function() {
        this.form.post(this.route('expenses.store'),{
          preserveScroll: true,
          onSuccess: () =>  this.form.reset()
        });
      },
      confirmDeleteExpense: function(expense){
        this.triggerConfirmDeleteExpenseAction =  true;
        this.selectedExpense = expense;
      },
      deleteExpense: function(){
        axios.delete(route('expenses.destroy', this.selectedExpense.id))
        .then(response => {
          if(response.data.code == 200){
            this.triggerConfirmDeleteExpenseAction = false;
            this.toast.success(response.data.message, this.toastconfig);
            Inertia.visit('/expenses', {
              only: ['expenses','currentTotalExpenses','previousTotalExpenses'],
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
      closeModal:function() {
        this.triggermodal = false;
        this.triggerConfirmDeleteExpenseAction = false;
        this.form.reset();
        this.form.clearErrors();
        this.$page.props.flash.error = false;
      },
    },
  }
</script>

<style>

</style>