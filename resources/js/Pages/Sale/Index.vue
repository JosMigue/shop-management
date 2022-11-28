<template>
  <app-layout>
    <template #header>
      <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Pedidos
        </h2>
        <jet-primary-button :type="'button'" @click="this.triggermodal = true">Agregar <i class="fa fa-plus"></i></jet-primary-button>
      </div>
    </template>
    <div>
      <div class="container mx-auto lg:px-36 mt-10 pb-5">
        <div class="bg-white shadow rounded-lg py-4">
          <div class="overflow-x-auto py-2">
            <div class="py-4 px-4 align-middle inline-block min-w-full sm:px-2 lg:px-4">
              <div class="shadow border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <table-th>
                        Folio
                      </table-th>
                      <table-th>
                        Cliente
                      </table-th>
                      <table-th>
                        Vendedor
                      </table-th>
                      <table-th title="Método de pago">
                        M.P.
                      </table-th>
                      <table-th>
                        Total
                      </table-th>
                      <table-th>
                        Estado
                      </table-th>
                      <table-th>
                        Flete
                      </table-th>
                      <table-th>
                        Fecha
                      </table-th>
                      <table-th>
                        Acciones
                      </table-th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="sale in sales.data" :key="sale.id" class="hover:bg-blue-200">
                      <table-td>
                        {{sale.code}}
                      </table-td>
                      <table-td>
                        {{sale.customer_id != 0 ? sale.customer.name : 'Mostrador'}}
                      </table-td>
                      <table-td>
                        {{sale.user.name}}
                      </table-td>
                      <table-td>
                        {{sale.payment_method}}
                      </table-td>
                      <table-td>
                        {{currencyHelper(sale.total)}}
                      </table-td>
                      <table-td>
                        {{sale.current_status}}
                      </table-td>
                      <table-td v-if="sale.status == 1">
                        {{currencyHelper(sale.freight_amount)}}
                      </table-td>
                      <table-td v-else>
                        N/A
                      </table-td>
                      <table-td>
                        {{sale.created_at}}
                      </table-td>
                      <td class="lg:pb-10 lg:pl-8 md:px-4 py-4 text-sm text-center">
                        <dropdown align="right" width="48">
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
                            <jet-dropdown-link :as="'button'" @click="showSale(sale)">
                              <i class="fa fa-info"></i> Detalle de pedido
                            </jet-dropdown-link>
                            <jet-dropdown-link v-if="sale.status == 0" :as="'button'" @click="confirmAction(sale,'status-change',1)">
                              <i class="fa fa-shipping-fast"></i> Marcar como envíado
                            </jet-dropdown-link>
                            <jet-dropdown-link v-else :as="'button'" @click="changeStatusSaleShipped(sale)">
                              <i class="fa fa-clock"></i> Marcar como pendiente
                            </jet-dropdown-link>
                            <div class="border-t border-gray-100"></div>
                          </template>
                        </dropdown>
                      </td>
                    </tr>
                    <tr v-if="sales.data.length == 0">
                      <td class="px-6 py-4 whitespace-nowrap" colspan="6">
                        <p class="text-sm text-center">No hay registros</p>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="flex justify-center">
              <pagination class="mt-4" :links="sales.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <dialog-modal :show="triggermodal"  @close="triggermodal = false">
      <template #title>
        Agregar pedido
      </template>
      <template #content>
        <div class="main flex border rounded-full overflow-hidden m-4 select-none">
          <div class="title py-3 my-auto px-5 bg-yellow-500 text-white text-sm font-semibold mr-3">Tipo</div>
          <div class="flex justify-evenly w-full">
            <label class="flex radio p-2 cursor-pointer">
              <input class="my-auto transform scale-125 text-yellow-500 focus:ring-yellow-500" v-model="type" type="radio" value="0" name="type" />
              <div class="title px-2">Cliente</div>
            </label>
            <label class="flex radio p-2 cursor-pointer">
              <input class="my-auto transform scale-125 text-yellow-500 focus:ring-yellow-500" v-model="type" type="radio" value="1" name="type" />
              <div class="title px-2">Mostrador</div>
            </label>
          </div>
        </div>
        <div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
          <div v-if="type == 0">
            <jet-label :required="true" :value="'Cliente'"></jet-label>
            <select v-model="form.customer_id" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-500 rounded-md shadow-sm w-full">
              <option value="" disabled>Seleccione un cliente</option>
              <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{customer.name}}</option>
            </select>
            <jet-input-error :message="this.form.errors['customer_id']"></jet-input-error>
          </div>
          <div v-else>
            <jet-label :required="true" :value="'Cliente'"></jet-label>
            <select v-model="form.customer_id" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-500 rounded-md shadow-sm w-full">
              <option value="0" disabled>Mostrador</option>
            </select>
            <jet-input-error :message="this.form.errors['customer_id']"></jet-input-error>
          </div>
          <div>
            <jet-label :required="true" :value="'Método de pago'"></jet-label>
            <select v-model="form.payment_method" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-500 rounded-md shadow-sm w-full">
              <option value="" disabled>Seleccione un método de pago</option>
              <option value="1">Contado</option>
              <option value="2" :disabled="type == 1">Crédito</option>
            </select>
            <jet-input-error :message="this.form.errors['payment_method']"></jet-input-error>
          </div>
          <div>
            <jet-label :required="true" :value="'Tipo de pago'"></jet-label>
            <select v-model="form.payment_type" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-500 rounded-md shadow-sm w-full">
              <option value="" disabled>Seleccione un tipo de pago</option>
              <option value="1">Efectivo</option>
              <option value="2">Electrónico</option>
            </select>
            <jet-input-error :message="this.form.errors['payment_type']"></jet-input-error>
          </div>
          <div class="lg:col-span-2" v-if="form.payment_method == 2">
            <p class="text-blue-400 text-center text-md">Anticipo de pago</p>
            <p class="text-xs text-blue-400 text-justify">
              Este campo no es obligatorio, en caso de que el cliente quiera dar un aticipo al momento de hacer un pedido puede agregarlo desde aquí.
            </p>
            <div class="grid gird-cols-1 gap-4">
              <div>
                <jet-label :value="'Monto'"></jet-label>
                <jet-input :modelValue="form.payment.amount" v-model="form.payment.amount" type="number" min="1" step=".01"></jet-input>
                <jet-input-error  :message="this.form.errors['payment.amount']"></jet-input-error>
              </div>
            </div>
          </div>
          <div class="lg:col-span-2">
            <jet-label :value="'Producto'"></jet-label>
            <jet-input :modelValue="search" @focus="searchPresentations" @focusout="removeMatches" placeholder="tecleé algo para empezar a buscar..."  @keyup="searchPresentations" v-model="search" type="text"></jet-input>
            <jet-input-error  :message="this.form.errors['products']"></jet-input-error>
            <div class="bg-white shadow-xl rounded-lg lg:w-11/12 w-10/12 absolute">
              <ul class="divide-y divide-gray-300" v-if="isLoading">
                <li class="p-4 hover:bg-gray-50 cursor-pointer"><i class="fa fa-spinner fa-spin"></i></li>
              </ul>
              <ul class="divide-y divide-gray-300" v-else>
                <li @click="addPresentation(presentation)" v-for="presentation in presentations" :key="presentation.id" class="p-4 hover:bg-yellow-200 cursor-pointer"><i class="fa fa-shopping-basket mx-1"></i>{{presentation.name}}</li>
                <li v-if="noMatches" class="p-4 hover:bg-yellow-200 cursor-pointer"><i class="fa fa-shopping-basket mx-1"></i>No se encontraron resultados para "{{this.search}}" ...</li>
              </ul>
            </div>
            <div>
              <div class="p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm grid grid-cols-5 gap-4" v-if="selectedPresentation.name">
                <div class="flex justify-center items-center">
                  <div class="p-5 bg-yellow-500 text-white text-center rounded-full">
                    <i class="fa fa-shopping-basket mx-1"></i>
                  </div>
                </div>
                <div class="col-span-4">
                  <p class="mb-2 text-sm font-medium text-gray-900">Presentación seleccionada: {{selectedPresentation.name}}</p>
                  <div class="grid lg:grid-cols-2 grid-cols-1 gap-2">
                    <div>
                      <jet-label :required="true" :value="'Unidad'"></jet-label>
                      <select v-model="selectedPresentation.measurement" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-200 rounded-md shadow-sm w-full">
                        <option value="" disabled>Unidad...</option>
                        <option v-for="(unit, index) in selectedPresentation.unit_of_measurements" :key="index" :value="unit">{{unit.name}}</option>
                      </select>
                    </div>
                    <div>
                      <jet-label :required="true" :value="'Cantidad'"></jet-label>
                      <jet-input :modelValue="selectedPresentation.quantity" v-model="selectedPresentation.quantity" type="number" step=".1" min="1"></jet-input>
                    </div>
                  </div>
                  <div class="flex justify-between">
                    <span class="lg:text-lg text-xs">Precio unidad</span>
                    <p class="lg:text-lg text-xs text-center flex items-center">${{selectedPresentation.measurement.pivot && selectedPresentation.quantity > 0 ? parseFloat(selectedPresentation.measurement.pivot.current_price).toFixed(2) : parseFloat(0.00).toFixed(2)}}</p>
                    <span class="lg:text-lg text-xs">Total</span>
                    <p class="lg:text-lg text-xs text-center flex items-center">${{selectedPresentation.measurement.pivot && selectedPresentation.quantity > 0 ? parseFloat(selectedPresentation.measurement.pivot.current_price * selectedPresentation.quantity).toFixed(2) : parseFloat(0.00).toFixed(2) }}</p>
                  </div>
                </div>
                <div class="col-span-full">
                  <div class="flex justify-between p-2">
                    <jet-primary-button @click="savePresentation()" :type="'button'">Agregar<i class="mx-1 fa fa-plus"></i></jet-primary-button>
                    <jet-button class="bg-red-500 hover:bg-red-700"  @click="resetSelectedPresentation()" :type="'button'">Quitar<i class="mx-1 fa fa-trash"></i></jet-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg" v-if="form.products.length > 0">
          <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Pedido
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Detalles del pedido
            </p>
          </div>
          <div class="border-t border-gray-200">
            <div class="px-1 py-5 sm:gap-4 sm:px-6 overflow-x-auto">
              <div class="flex lex-row justify-between items-center mt-4 table w-full">
                <div class="table-row-group ">
                  <div class="table-row bg-yellow-400">
                    <p class="table-cell border border-yellow-600 text-black text-center lg:text-lg md:text-md text-xs py-2">Producto</p>  
                    <p class="table-cell border border-yellow-600 text-black text-center lg:text-lg md:text-md text-xs py-2">Unidad</p> 
                    <p class="table-cell border border-yellow-600 text-black text-center lg:text-lg md:text-md text-xs py-2">Cantidad</p>
                    <p class="table-cell border border-yellow-600 text-black text-center lg:text-lg md:text-md text-xs py-2">Precio</p>  
                    <p class="table-cell border border-yellow-600 text-black text-center lg:text-lg md:text-md text-xs py-2">Total</p> 
                    <p class="table-cell border border-yellow-600 text-black text-center lg:text-lg md:text-md text-xs py-2">Acción</p>
                  </div>
                  <div class="table-row" v-for="(product, key) in form.products" :key="key">
                    <p class="table-cell border border-yellow-600 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">{{product.name}}</p>
                    <p class="table-cell border border-yellow-600 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">{{product.unit_of_measurement_name}}</p>
                    <div class="table-cell border border-yellow-600 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">
                      <jet-input class="text-xs lg:1/2 w-full" :modelValue="product.quantity" v-model="product.quantity" type="number" step=".1" min="1"></jet-input>
                      <jet-input-error :message="this.form.errors['products.'+key+'.quantity']"></jet-input-error>
                    </div>
                    <p class="table-cell border border-yellow-600 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">
                      {{product.current_price && product.quantity > 0 ? currencyHelper(product.current_price) : currencyHelper(0)}}
                    </p>
                    <p class="table-cell border border-yellow-600 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">
                      {{product.current_price && product.quantity > 0 ? currencyHelper(product.current_price * product.quantity) : currencyHelper(0) }}
                    </p>
                    <div class="table-cell border border-yellow-600 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">
                      <jet-primary-button class="bg-red-500 hover:bg-red-700" @click="removeItem(key)" :type="'button'"><i class="fa fa-trash"></i></jet-primary-button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <dl>
              <div class="bg-gray-50 px-4 py-5 grid lg:grid-cols-5 md:grid-cols-5 grid-cols-4 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500 col-span-5 flex justify-between">
                  <p class="text-left font-bold">Total en venta</p>
                  <p class="text-right">{{currencyHelper(this.total)}}</p>
                </dt>
              </div>
            </dl>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-4">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" @click="saveSale()">Finalizar pedido</jet-primary-button>
        </div>
      </template>
    </dialog-modal>
    <dialog-modal :show="triggerModalShowSale"  @close="triggerModalShowSale = false">
      <template #title>
        Pedido
      </template>
      <template #content>
          <div v-if="tab == 1" class="font-bold text-xl">Detalles</div>
          <div v-if="tab == 2" class="font-bold text-xl">Pagos</div>
        <div class="flex flex-row items-center justify-end px-5 mt-5">
          <div class="font-semibold">
            <span @click="tab=1" :class="[tab == 1 ? 'text-yellow-500 bg-yellow-100' : '']" class="border border-b-0 py-2 px-6 bg-white rounded-t-lg cursor-pointer">Productos</span>
            <span @click="tab=2" :class="[tab == 2 ? 'text-yellow-500 bg-yellow-100' : '']" class="border border-b-0 py-2 px-6 bg-white rounded-t-lg cursor-pointer">Pagos</span>
          </div>
        </div>
        <div class="rounded-md shadow-lg" v-if="tab == 1">
          <div class="px-5 py-4 mt-5 overflow-y-auto max-h-64">
            <div class="flex lex-row justify-between items-center mt-4 table w-full">
              <div class="table-row-group ">
                <div class="table-row bg-yellow-400">
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Producto</p>
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Cant.</p>
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Unidad</p>
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Precio</p>
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Total</p>
                </div>
                <div class="table-row"  v-for="(product, index) in selectedSale.products" :key="index">
                  <p class="table-cell border border-yellow-500 text-gray-600 text-center lg:text-lg md:text-md text-xs py-2">
                    {{product.name}}
                  </p>
                  <p class="table-cell border border-yellow-500 tgray-600 text-center lg:text-lg md:text-md text-xs py-2">
                    {{product.pivot.quantity}}
                  </p>
                  <p class="table-cell border border-yellow-500 text-gray-600 text-center lg:text-lg md:text-md text-xs py-2">
                    {{getCurrentUnit(product.unit_presentation_price, product.pivot.unit_of_measurement_id)}}
                  </p>
                  <p class="table-cell border border-yellow-500 text-gray-600 text-center lg:text-lg md:text-md text-xs py-2">
                    {{currencyHelper(product.pivot.price)}}
                  </p>
                  <p class="table-cell border border-yellow-500 text-gray-600 text-center lg:text-lg md:text-md text-xs py-2">
                    {{currencyHelper(product.pivot.price * product.pivot.quantity)}}
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="px-5 mt-5">
            <div class="py-4">
              <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                <span class="font-semibold lg:text-xl text-md">Total</span>
                <span class="font-bold lg:text-xl text-md">
                  {{currencyHelper(this.selectedSale.total)}}
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="rounded-md shadow-lg" v-if="tab == 2">
          <div class="flex justify-center my-6" v-if="selectedSale.payment_method === 'Crédito'" >
            <jet-primary-button class="lg:text-md text-xs" v-if="!triggerAddPayment" @click="triggerAddPayment = true" >Agregar<i class="fa fa-plus mx-1"></i></jet-primary-button>
            <jet-primary-button class="lg:text-md text-xs" v-if="triggerAddPayment" @click="triggerAddPayment = false" >Cerrar<i class="fa fa-times mx-1"></i></jet-primary-button>
          </div>
          <div v-if="triggerAddPayment">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-4 mx-4 my-4">
              <div>
                <jet-label :value="'Monto'"></jet-label>
                <jet-input :modelValue="payment.amount" v-model="payment.amount" type="number" min="1" step=".01"></jet-input>
                <jet-input-error  v-if="this.errors['amount']" :message="this.errors['amount'][0]"></jet-input-error>
              </div>
              <div>
                <jet-label :value="'Tipo de pago'"></jet-label>
                <select v-model="payment.payment_type" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-200 rounded-md shadow-sm w-full">
                  <option value="" disabled>Seleccione un tipo de pago</option>
                  <option value="1">Efectivo</option>
                  <option value="2">Electrónico</option>
                </select>
                <jet-input-error v-if="this.errors['payment_type']" :message="this.errors['payment_type'][0]"></jet-input-error>
              </div>
            </div>
            <div class="flex justify-end mx-4 my-4">
              <jet-primary-button @click="savePayment">Guardar</jet-primary-button>
            </div>
          </div>
          <div class="px-5 py-4 mt-5 overflow-y-auto max-h-64">
            <div class="flex lex-row justify-between items-center mt-4 table w-full relative">
              <div class="table-row-group ">
                <div class="table-row bg-yellow-400 sticky -top-4">
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Fecha</p>  
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Tipo de pago</p> 
                  <p class="table-cell border border-yellow-500 text-black text-center lg:text-lg md:text-md text-xs py-2">Monto (MXN)</p>
                </div>
                <div class="table-row" v-for="(payment, index) in selectedSale.payments" :key="index">
                  <p class="table-cell border border-yellow-500 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">{{payment.created_at}}</p>
                  <p class="table-cell border border-yellow-500 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">{{payment.payment_type}}</p>
                  <p class="table-cell border border-yellow-500 text-gray-800 text-center lg:text-lg md:text-md text-xs py-2">{{currencyHelper(payment.amount)}}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="px-5">
            <div class="py-4">
              <div class="border-t-2 mt-3 py-1 px-4 flex items-center justify-between">
                <span class="font-semibold lg:text-lg text-sm">Total pedido</span>
                <span class="font-bold lg:text-lg text-sm">{{currencyHelper(selectedSale.total)}}</span>
              </div>
              <div class="py-1 px-4 flex items-center justify-between">
                <span class="font-semibold lg:text-lg text-sm">Total pagado</span>
                <span class="font-bold lg:text-lg text-sm">{{currencyHelper(selectedSale.payments_sum)}}</span>
              </div>
              <div class="py-1 px-4 flex items-center justify-between">
                <span class="font-semibold lg:text-lg text-sm">Adeudo</span>
                <span class="font-bold lg:text-lg text-sm">{{currencyHelper(selectedSale.total - selectedSale.payments_sum)}}</span>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-4">
          <jet-secondary-button @click="closeModalInfo()">Cerrar</jet-secondary-button>
        </div>
      </template>
    </dialog-modal>
    <!-- CONFIRM ACTIONS MODALS -->
    <jet-confirmation-modal :show="triggerConfirmChangeStatus"  @close="triggerConfirmChangeStatus = false">
      <template #title>
        ¿Está seguro que desea cambiar el estado del pedido? 
      </template>
      <template #content>
        Esta acción cambiará el estado del pedido a envíado.
        Por lo que debe ingresar el precio monto del flete en el siguiente campo:
        <jet-label :value="'Monto de flete'"></jet-label>
        <div class="block relative">
          <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
            <i class="h-4 w-4 fill-current text-yellow-500 fa fa-dollar-sign"></i>
          </span>
          <jet-input class="pl-6" :modelValue="formStatusSale.freight_amount" v-model="formStatusSale.freight_amount" type="number" :autofocus="triggerConfirmChangeStatus"  step=".1" min="1"></jet-input>
          <jet-input-error :message="this.formStatusSale.errors['freight_amount']"></jet-input-error>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end">
          <jet-secondary-button @click="closeModal()">Cancelar</jet-secondary-button>
          <jet-primary-button :type="'button'" :class="{'cursor-not-allowed': !formStatusSale.freight_amount}" :disabled="formStatusSale.freight_amount ? false : true" @click="changeStatusSale()">Cambiar</jet-primary-button>
        </div>
      </template>
    </jet-confirmation-modal>
  </app-layout>
</template>
<script>
  import { useToast } from "vue-toastification";
  import { Inertia } from '@inertiajs/inertia';
  import { useForm } from '@inertiajs/inertia-vue3';
  import AppLayout from '@/Layouts/AppLayout';
  import Pagination from '@/Components/Pagination';
  import DialogModal from '@/Jetstream/DialogModal';
  import JetPrimaryButton from '@/Components/PrimaryButton';
  import JetButton from '@/Jetstream/Button';
  import JetSecondaryButton from '@/Jetstream/SecondaryButton';
  import JetInput from '@/Jetstream/Input';
  import JetLabel from '@/Jetstream/Label';
  import JetInputError from '@/Jetstream/InputError';
  import Dropdown from '@/Components/Dropdown';
  import TableTh from '@/Components/Th';
  import TableTd from '@/Components/Td';
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
      JetButton,
      JetConfirmationModal,
      TableTh,
      TableTd
    },
    setup() {
      const toast = useToast();
      const form = useForm({
        customer_id: '',
        payment_method: '',
        payment_type:'',
        payment: {
          amount: ''
        },
        products: [],
      });
      const formStatusSale = useForm({
        status: null,
        freight_amount: null
      })
      return { toast, form, formStatusSale}
    },
    props: {
      sales: Object,
      unit_of_measurements: Object
    },
    mounted () {
      this.getAllCustomers();
    },
    data() {
      return {
        customers: Object,
        presentations: [],
        triggermodal: false,
        triggerModalShowSale: false,
        triggerAddPayment : false,
        triggerConfirmChangeStatus: false,
        payment: {
          amount: '',
          payment_type:''
        },
        payments_sum_amount: 0,
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
        timeout: null,
        search: '',
        noMatches: false,
        selectedCustomer: '',
        selectedPresentation: {
          id: null,
          name: null,
          idPresentation: null,
          quantity: null,
          unit_of_measurements: null,
          measurement: null
        },
        selectedSale: '',
        currentValue: '',
        freightAmount: '',
        isLoading: false,
        total: 0,
        tab: 1,
        type: 0
      }
    },
    methods: {
      closeModal:function() {
        this.triggermodal = false;
        this.triggerModalShowSale = false;
        this.triggerConfirmChangeStatus = false;
        this.triggerConfirmChangeStatus = false;
        this.formStatusSale.reset();
        this.form.reset();
        this.formStatusSale.clearErrors();
        this.form.clearErrors();
      },
      closeModalInfo:function(){
        this.triggerModalShowSale = false;
        Inertia.reload({ only: ['sales'] })
      },
      getAllCustomers: function(){
        axios.get('/get/customers')
        .then(response => {
          this.customers = response.data.customers
        })
        .catch(error => {
          this.toast.error('Ocurrió un error al intentar obtener los clientes, intente más tarde o contacte al administrador del sistema.', this.toastconfig);
        })
      },
      searchPresentations: function() {
        clearTimeout(this.timeout);
        let self = this;
        this.search ? this.isLoading = true : '';
        this.timeout = setTimeout(function () {
          if (self.search) {
            axios.get('/get/mango_presentations', {
              params: {
                search: self.search
              }
            })
            .then(response => {
              self.presentations = response.data.presentations;
              self.presentations.length == 0 ? self.noMatches = true : self.noMatches = false;
              self.isLoading = false;
            })
            .catch(error => {
              self.isLoading = false;
              this.toast.error('Ocurrió un error al intentar obtener las presentaciones de mango, intente más tarde o contacte al administrador del sistema.', this.toastconfig);
            })
          } else {
            self.presentations = [];
            self.isLoading = false;
          }
        }, 1000);
      },
      addPresentation:function(presentation){
        this.selectedPresentation.id = presentation.id;
        this.selectedPresentation.name = presentation.name;
        this.selectedPresentation.quantity = 1;
        this.selectedPresentation.mango_presentation_id = presentation.id;
        this.selectedPresentation.unit_of_measurements = presentation.unit_presentation_price;
        this.selectedPresentation.measurement = '';
      },
      savePresentation:function(){
        if(this.selectedPresentation.measurement.id && this.selectedPresentation.quantity){
          const self = this;
          const found_index = this.form.products.findIndex(function(product, index) { //Verificamos si el producto ya ha sido agregado
            if(product.mango_presentation_id == self.selectedPresentation.id && product.unit_of_measurement_id == self.selectedPresentation.measurement.id)
              return true;
          });
          if(found_index >= 0){ //Si se encuentra solo se le agrega la cantidad
            this.form.products[found_index].quantity = parseInt(this.selectedPresentation.quantity) + parseFloat(this.form.products[found_index].quantity);
            this.toast.success('Cantidad actualizada', this.toastconfig);
          }else{
            this.form.products.push({
              name: this.selectedPresentation.name, 
              quantity: this.selectedPresentation.quantity, 
              mango_presentation_id: this.selectedPresentation.id, 
              unit_of_measurement_id: this.selectedPresentation.measurement.id,
              unit_of_measurement_name: this.selectedPresentation.measurement.name,
              current_price: this.selectedPresentation.measurement.pivot.current_price
            });
            this.toast.success('Producto agregado', this.toastconfig);
          }
          this.total = this.sumOfProducts;
          this.presentations = [];
          this.resetSelectedPresentation();
        }else{
          this.toast.warning('No deje selectores vacíos y/o campos en 0 o vacíos...', this.toastconfig);
        }
      },
      removeItem: function(key){
        this.form.products.splice(key, 1);
        this.toast.success('Producto quitado', this.toastconfig);
      },
      removeMatches:function(){
        setTimeout(() => {
          this.presentations = [];
          this.noMatches = false;
        }, 500);
      },
      saveSale:function(){
        this.form.post(this.route('sales.store'),{
          preserveScroll: true,
          onError: () => {
            this.toast.warning('¡Vaya!, hemos encontrado errores...', this.toastconfig);
          },
          onSuccess: () => {
            this.closeModal();
            this.toast.success('Pedido agregado correctamente', this.toastconfig);
          }
        });
      },
      showSale: function(sale){
        this.selectedSale = sale;
        this.triggerModalShowSale = true;
      },
      sumOfProducts: function() {
        this.total = 0;
        this.form.products.reduce((sum, product) => {
          let value = isNaN(parseFloat(product.current_price * product.quantity)) ? 0 : parseFloat(product.current_price * product.quantity);
          this.total = parseFloat(this.total) + value;
        }, 0);
      },
      openModalAddPayment: function(){
        this.triggerModalAddPayment =  true;
      },
      savePayment: function(){
        axios.post(`/payments/${this.selectedSale.id}`, this.payment)
        .then(response => {
          if(response.data.code == 200){
            this.toast.success(response.data.message, this.toastconfig);
            axios.get(`/sales/${this.selectedSale.id}`)
            .then(response => {
              this.selectedSale = response.data;
            });
            this.resetData();
          }else{
           this.toast.error(response.data.message, this.toastconfig);
          }
        })
        .catch(error => {
          console.log(error);
          if(error.response.data){
            this.errors = error.response.data.errors;
          }else{
            this.toast.error('Ocurrió un error desconocido al agregar el pedido, intente más tarde o contacte al administrador del sistema.', this.toastconfig);
          }
        });
      },
      confirmAction: function(sale,action,value){
        this.selectedSale = sale;
        if(action === 'status-change'){
          this.formStatusSale.status = value;
          this.triggerConfirmChangeStatus = true;
        }
      },
      changeStatusSale: function(){
        this.formStatusSale.patch(this.route('sale.update.ship', this.selectedSale.id),{
          preserveScroll: true,
          onError: () => {
            this.toast.warning('¡Vaya!, hemos encontrado errores...', this.toastconfig);
          },
          onSuccess: () => {
            this.closeModal();
            this.toast.success('Estado cambiado correctamente', this.toastconfig);
          }
        });
      },
      changeStatusSaleShipped: function(sale){
        this.formStatusSale.freightAmount = 0;
        this.formStatusSale.status = 0;
        this.formStatusSale.patch(this.route('sale.update.ship', sale.id),{
          preserveScroll: true,
          onError: () => {
            this.toast.warning('¡Vaya!, hemos encontrado errores...', this.toastconfig);
          },
          onSuccess: () => {
            this.closeModal();
            this.toast.success('Estado cambiado correctamente', this.toastconfig);
          }
        });
      },
      resetData: function(){
        this.payment.amount = '';
        this.payment.payment_type = '';
        this.triggerAddPayment =  false;
      },
      resetSelectedPresentation: function(){
        this.selectedPresentation.id = null;
        this.selectedPresentation.name = null;
        this.selectedPresentation.idPresentation = null;
        this.selectedPresentation.quantity = null;
        this.selectedPresentation.unit_of_measurements = null;
        this.selectedPresentation.measurement = null;
      },
      getCurrentUnit: function(items, current){
        let  label = '';
        items.filter(unit => {
          if(unit.id == current)
            label =  unit.name;
        });
        return label;
      }
    },
    watch: {
      form:{ //Ver si los datos del array cambian
        deep: true,
        handler(data) {
          this.sumOfProducts();
        }
      },
      type:function(newValue, oldValue){
        if(newValue == 0){
          this.form.customer_id = '';
          this.form.payment_method = '';
        }else{
          this.form.customer_id = 0;
          this.form.payment_method = 1;
        }
      },
      triggermodal: function(newValue, oldValue){
        if(newValue)
          this.form.customer_id = '';
          this.form.payment_method = '';
          this.form.payment_type = '';
          this.form.products = [];
          this.search = '';
          this.errors =  {};
      }
    },
  };
</script>
<style>
  /*Remove input type number styles*/
  input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }

  /* TRANSITION */
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s
  }

  .fade-enter-from, .fade-leave-to {
    opacity: 0
  }

</style>