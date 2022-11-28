<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Panel
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
          <div class="flex flex-col text-center w-full my-10">
            <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Indicadores</h1>
          </div>
          <div class="flex flex-wrap m-4 text-center" v-if="$page.props.user.role_id != 4">
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-shopping-basket text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.currentDaySales)}}</h2>
                <p class="leading-relaxed">Ventas del día actual</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-shopping-basket text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.yesterdaySales)}}</h2>
                <p class="leading-relaxed">Ventas del día anterior</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-shopping-cart text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.currentYearSales)}}</h2>
                <p class="leading-relaxed">Ventas del año actual ({{new Date().getFullYear()}})</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-truck text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{customersCount}}</h2>
                <p class="leading-relaxed">Distribuidores</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-hand-holding-usd text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">${{new Intl.NumberFormat('es-MX').format(parseFloat($page.props.user.sum_deposits).toFixed(2))}}</h2>
                <p class="leading-relaxed">Transferencias realizadas</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-hand-holding-usd text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">${{new Intl.NumberFormat('es-MX').format(parseFloat(this.totalExpenses).toFixed(2))}}</h2>
                <p class="leading-relaxed">Gastos</p>
              </div>
            </div>
          </div>
          <div class="flex flex-wrap m-4 text-center" v-else>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-shopping-basket text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.currentDaySales)}}</h2>
                <p class="leading-relaxed">Ventas del día actual</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-shopping-basket text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.yesterdaySales)}}</h2>
                <p class="leading-relaxed">Ventas del día anterior</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-shopping-cart text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">{{currencyHelper(this.currentYearSales)}}</h2>
                <p class="leading-relaxed">Ventas del año actual ({{new Date().getFullYear()}})</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-money-check-alt text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">${{new Intl.NumberFormat('es-MX').format(parseFloat(this.currentTotalExpenses).toFixed(2))}}</h2>
                <p class="leading-relaxed">Gastos realizados</p>
              </div>
            </div>
            <div class="p-4 md:w-1/4 sm:w-1/2 w-full">
              <div class="border-2 border-gray-200 px-4 py-6 rounded-lg transform trasition-all ease-in-out duration-500 hover:scale-105">
                <i class="fa fa-hand-holding-usd text-5xl text-yellow-500 w-12 h-12 mb-3 inline-block"></i>
                <h2 class="title-font font-medium text-3xl text-gray-900">${{new Intl.NumberFormat('es-MX').format(parseFloat(($page.props.user.sum_transfers + $page.props.user.sum_payments) - this.currentTotalExpenses).toFixed(2))}}</h2>
                <p class="leading-relaxed">Saldo disponible</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'

    export default {
        components: {
            AppLayout,
            Welcome,
        },
        props: {
          currentDaySales: Number,
          currentYearSales: Number,
          yesterdaySales: Number,
          customersCount: Number,
          currentTotalExpenses: Number,
          totalExpenses: Number
        },
    }
</script>
