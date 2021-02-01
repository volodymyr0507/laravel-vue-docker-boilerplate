<template>
  <ais-instant-search :search-client="searchClient"
    :index-name="index">
    <ais-configure :hits-per-page.camel="5"
      :aroundLatLng="location"
      :aroundRadius="radius" />
    <InfiniteHits>
      <template slot="item" slot-scope="{ item }">
        <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
          <div class="flex p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
            <router-link :to="{ name: 'mechanic.profile', params: { id: item.id } }"
              class="text-xl font-bold text-gray-900"
              v-text="item.name ">
            </router-link>
          </div>
          <p class="mt-4 px-4 font-medium text-gray-700">{{ item.address }}</p>
          <!-- eslint-disable-next-line  -->
          <a class="w-full block mt-6 p-4 text-white text-center font-bold bg-royal-blue rounded-bl-md rounded-br-md"
            :href="`tel:${item.phone}`">
            Enquire
          </a>
        </div>
      </template>
    </InfiniteHits>
  </ais-instant-search>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

import InfiniteHits from '@/components/global/InfiniteHits.vue';

@Component({
  components: {
    InfiniteHits,
  },
  props: {
    searchClient: {
      type: Object,
      required: true,
    },
    index: {
      type: String,
      required: true,
    },
    location: {
      type: String,
      required: true,
    },
    radius: {
      type: Number,
      required: true,
    },
  },
})
export default class DriverDirectoryTab extends Vue {}
</script>
