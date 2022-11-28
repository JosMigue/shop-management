<template>
  <div v-if="links.length > 3">
    <div class="md:flex flex-wrap -mb-1 hidden">
      <template v-for="(link, k) in links" :key="k">
          <div v-if="link.url === null"  class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
          <inertia-link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-yellow-500 focus:text-yellow-500" :class="{ 'bg-yellow-600 text-white': link.active }" :href="link.url" v-html="link.label" />
      </template>
    </div>
    <div class="flex-1 flex justify-between sm:hidden">
      <div  v-if="links[0].url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="links[0].label"></div>
      <inertia-link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-yellow-500 focus:text-yellow-500" :href="links[0].url" v-html="links[0].label"></inertia-link>
      <div v-if="links[links.length-1].url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="links[links.length-1].label"></div>
      <inertia-link v-else :href="links[links.length-1].url" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-yellow-500 focus:text-yellow-500" v-html="links[links.length-1].label"></inertia-link>
    </div>
  </div>
</template>
  
<script>
export default {
  props: {
    links: Array,
  },
}
</script> 