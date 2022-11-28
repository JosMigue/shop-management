<template>
	<app-layout>
    <template #header>
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Reportes
			</h2>
    </template>
    <div>
      <div class="container mx-auto lg:px-40 mt-10">
        <div class="bg-white shadow rounded-lg p-6">
          <div class="flex flex-col">
            <div class="my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
									<div class="flex flex-col text-center w-full mb-10">
										<div class="flex mx-auto border-2 border-yellow-500 rounded overflow-hidden mt-6">
											<button @click="section = 'single'" :class="section == 'single' ? 'bg-yellow-500 text-white': ''" class="py-1 px-4 focus:outline-none">Individual</button>
											<button @click="section = 'massive'" :class="section == 'massive' ? 'bg-yellow-500 text-white': ''" class="py-1 px-4 focus:outline-none">Masivo</button>
										</div>
									</div>
									<div v-if="section == 'massive'">
										<div class="container mx-auto my-5 p-5">
											<div class="md:flex no-wrap">
												<div class="w-full mx-2">
                          <h1 class="text-center text-2xl">
                            Adeudos de clientes
                          </h1>
													<div class="bg-white p-3 shadow-sm rounded-sm">
														<div class="text-gray-700 overflow-x-auto  max-h-80">
															<table class="min-w-full divide-y divide-gray-200">
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
																			Adeudado
																		</th>
																		<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
																			Fecha de registro
																		</th>
																	</tr>
																</thead>
																<tbody>
																	<tr v-for="customer in customers" :key="customer.id" :value="customer" class="bg-gradient-to-r hover:from-yellow-100 hover:to-yellow-300 transition-all transform duration-200 hover:-translate-y-1 hover:shadow-md">
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{customer.name}}</div>
																		</td>
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{customer.phone}}</div>
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
																			<div class="text-sm text-gray-900">{{currencyHelper(customer.sum_sales - customer.sum_payments)}}</div>
																		</td>
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{customer.created_at}}</div>
																		</td>
																	</tr>
																	<tr v-if="this.customers.length == 0">
																		<td class="px-6 py-4 whitespace-nowrap" colspan="6">
																			<p class="text-sm text-center">No hay registros</p>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div v-if="section == 'single'">
										<jet-label :value="'Clientes'"></jet-label>
										<select v-model="selectedCustomer" class="border-gray-300 focus:border-indigo-yellow focus:ring focus:ring-yellow-200 focus:ring-opacity-50 focus:border-yellow-200 rounded-md shadow-sm w-full">
											<option value="" disabled>Seleccione un cliente para ver información y las compras</option>
											<option v-for="customer in customers" :key="customer.id" :value="customer">{{customer.name}}</option>
										</select>
										<div class="container mx-auto my-5 p-5" v-if="selectedCustomer">
											<div class="md:flex no-wrap">
												<div class="w-full md:w-3/12 md:mx-2">
													<div class="bg-white p-3 border-t-4 " :class="selectedCustomer.deleted_at ? 'border-red-400':'border-green-400'">
														<div class="image overflow-hidden">
															<img class="h-auto w-full mx-auto" :src="`https://ui-avatars.com/api/?name=${this.selectedCustomer.name}&background=FCBC04&color=fff`" alt="">
														</div>
														<h1 class="text-gray-900 font-bold text-xl leading-8 my-1">{{this.selectedCustomer.name}}</h1>
														<h3 class="text-gray-600 font-lg text-semibold leading-6">Cliente</h3>
														<ul	class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
															<li class="flex items-center py-3">
																<span>Estado</span>
																<span class="ml-auto" v-if="this.selectedCustomer.deleted_at">
																	<span class="bg-red-500 py-1 px-2 rounded text-white text-sm">No activo</span>
																</span>
																<span class="ml-auto" v-else>
																	<span class="bg-green-500 py-1 px-2 rounded text-white text-sm">Activo</span>
																</span>
															</li>
															<li class="flex items-center py-3">
																<span>Fecha de registro</span>
																<span class="ml-auto">{{this.selectedCustomer.created_at}}</span>
															</li>
														</ul>
													</div>
												</div>
												<div class="w-full md:w-9/12 mx-2">
													<div class="bg-white p-3 shadow-sm rounded-sm">
														<div class="flex items-center space-x-2 font-semibold text-gray-900 leading-8">
															<span clas="text-green-500">
																<i class="fa fa-shopping-bag text-2xl"></i>
															</span>
															<span class="tracking-wide">Compras</span>
														</div>
														<div class="text-gray-700 overflow-x-auto  max-h-80">
															<table class="min-w-full divide-y divide-gray-200">
																<thead class="bg-gray-50">
																	<tr>
																		<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
																			Código
																		</th>
																		<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
																			Vendedor
																		</th>
																		<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
																			Método de pago
																		</th>
																		<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
																			Total
																		</th>
																		<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
																			Fecha
																		</th>
																	</tr>
																</thead>
																<tbody>
																	<tr v-for="sale in this.selectedCustomer.sales" :key="sale.id" class="hover:bg-yellow-200">
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{sale.code}}</div>
																		</td>
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{sale.user.name}}</div>
																		</td>
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{sale.payment_method}}</div>
																		</td>
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{currencyHelper(sale.total)}}</div>
																		</td>
																		<td class="px-6 py-4 whitespace-nowrap">
																			<div class="text-sm text-gray-900">{{sale.created_at}}</div>
																		</td>
																	</tr>
																	<tr v-if="this.selectedCustomer.sales.length == 0">
																		<td class="px-6 py-4 whitespace-nowrap" colspan="6">
																			<p class="text-sm text-center">No hay registros</p>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
														<div class="grid lg:grid-cols-2 grid-cols-1 gap-4">
															<div class="p-4">
																<div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
																	<i class="fa fa-hand-holding-usd text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
																	<h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.selectedCustomer.sum_sales - this.selectedCustomer.sum_payments)}}</h2>
																	<p class="leading-relaxed">Adeudado</p>
																</div>
															</div>
															<div class="p-4">
																<div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
																	<i class="fa fa-shopping-basket text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
																	<h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.selectedCustomer.sum_sales)}}</h2>
																	<p class="leading-relaxed">Total comprado</p>
																</div>
															</div>
														</div>
													</div>
												</div>
												</div>
											</div>
										</div>
									</div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
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
			customers: Object
		},
		setup() {
			const toast = useToast();
			return { toast}
		},
		data() {
			return {
				selectedCustomer: '',
				section: 'single'
			}
		},
		watch: {
			section: function() {
				this.selectedCustomer = '';
			}
		},
	}
</script>

<style>

</style>