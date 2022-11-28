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
                            ? 'inline-flex items-center px-1 pt-1 border-b-4 border-yellow-300 text-sm font-medium leading-5 text-white focus:outline-none focus:border-yellow-300 transition'
                            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-gray-200 hover:text-gray-700 hover:border-yellow-300 focus:outline-none focus:text-gray-700 focus:border-yellow-300 transition'
            }
        }
    }
</script>