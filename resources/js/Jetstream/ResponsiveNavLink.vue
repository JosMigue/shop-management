<template>
    <div>
        <button :class="classes" class="w-full text-left" v-if="as == 'button'">
            <slot></slot>
        </button>

        <inertia-link :href="href" :class="classes" v-else>
            <slot></slot>
        </inertia-link>
    </div>
</template>

<script>
    export default {
        props: ['active', 'href', 'as'],

        computed: {
            classes() {
                return this.active
                            ? 'block pl-3 pr-4 py-2 border-l-4 border-yellow-600 text-base font-medium text-yellow-700 bg-yellow-50 focus:outline-none focus:text-yellow-800 focus:bg-yellow-100 focus:border-yellow-700 transition'
                            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-white hover:text-gray-300 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition'
            }
        }
    }
</script>
