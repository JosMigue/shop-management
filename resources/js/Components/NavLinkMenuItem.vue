<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                            ? 'inline-flex items-center px-1 py-2 border-b-2 border-yellow-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition'
                            : 'inline-flex items-center px-1 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-yellow-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition'
            }
        }
    }
</script>

