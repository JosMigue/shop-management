<template>
    <div class="relative inline-flex items-center px-1 pt-1 hover:text-gray-700 hover:border-gray-300">
        <button @click="open = ! open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white focus:outline-none focus:text-gray-200 focus:border-yellow-200 transition">
            <slot name="menu-text"></slot> <i :class="{'rotate-180': open}" class="fa fa-chevron-down ml-2 transform transition-all ease-out duration-200"></i>
        </button>
        <div v-show="open" class="fixed inset-0 z-40" @click="open = false"> </div>
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div v-show="open"
                    class="absolute bg-white z-50 mt-44 rounded-md shadow-lg w-48"
                    style="display: none;"
                    @click="open = false">
                <div class="flex flex-col">
                    <slot name="content"></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: ['href', 'active'],
        data() {
            return {
                open: false
            }
        },

        computed: {
            classes() {
                return this.active
                            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition'
                            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition'
            }
        }
    }
</script>
