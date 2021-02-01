<template>
  <main>
    <NavigationBar />
    <div v-if="latitude && longitude && !hasError"
      class="flex flex-row items-end py-4 px-6 bg-gray-100">
      <label class="block flex-1">
        <span class="text-gray-700">Filter Distance</span>
        <select class="form-select mt-1 block w-full" v-model="filterDistance">
          <option selected :value="1609">1 mile</option>
          <option :value="8046">5 miles</option>
          <option :value="16093">10 miles</option>
          <option :value="40234">25 miles</option>
        </select>
      </label>
    </div>
    <template v-if="authenticated && user.role.name === 'mechanic'"></template>
      <ul v-else
        class="flex mt-4 mx-6 mb-6 p-1 bg-gray-100 rounded-lg">
        <li class="flex-1">
          <!-- eslint-disable-next-line  -->
          <button class="w-full p-2 font-bold text-gray-900"
            :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
            @click.prevent="switchTab(0)">Services</button>
        </li>
        <li class="flex-1">
          <button class="w-full p-2 font-bold text-gray-900"
            :class="{ 'bg-white rounded-md shadow-sm ': isDirectoryTab  }"
            @click.prevent="switchTab(1)">Directory</button>
        </li>
      </ul>
    <template v-if="latitude && longitude && !hasError">
      <template v-if="authenticated && user.role.name === 'mechanic'">
        <MechanicJobsTab :searchClient="searchClient"
          :location="locationForAlgolia"
          :radius="filterDistance"
          index="jobs" />
      </template>
      <template v-else>
        <DriverServicesTab v-if="isServicesTab"
          :searchClient="searchClient"
          :location="locationForAlgolia"
          :radius="filterDistance"
          index="services" />
        <DriverDirectoryTab v-else
          :searchClient="searchClient"
          :location="locationForAlgolia"
          :radius="filterDistance"
          index="users" />
      </template>
    </template>
    <div v-if="hasError">
      <h2 class="px-6 mb-2 font-bold text-2xl text-gray-900">Help us out here...</h2>
      <p class="px-6 leading-relaxed text text-gray-700">{{ error }}</p>
    </div>
    <!-- eslint-disable-next-line  -->
    <router-link class="flex justify-center items-center fixed bottom-right w-16 h-16 text-4xl bg-royal-blue shadow-md rounded-full text-white font-medium"
      :to="{ name: 'driver.job.create' }"
      v-if="authenticated && user.role.name === 'driver'">
      +
    </router-link>
  </main>
</template>

<script lang="ts">
import algoliasearch from 'algoliasearch/lite';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import DriverServicesTab from './DriverServicesTab.vue';
import DriverDirectoryTab from './DriverDirectoryTab.vue';
import MechanicJobsTab from './MechanicJobsTab.vue';

@Component({
  components: {
    NavigationBar,
    DriverServicesTab,
    DriverDirectoryTab,
    MechanicJobsTab,
  },
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      user: 'auth/user',
      hasError: 'hasError',
      error: 'error',
    }),
  },
  methods: {
    ...mapActions({
      setError: 'setError',
      clearError: 'clearError',
    }),
  },
})
export default class Home extends Vue {
  private isServicesTab = true;

  private isDirectoryTab = false;

  public filterDistance = 1609;

  public searchClient = algoliasearch(
    process.env.VUE_APP_ALGOLIA_ID,
    process.env.VUE_APP_ALGOLIA_SECRET_KEY,
  );

  private latitude: number|null = null;

  private longitude: number|null = null;

  mounted() {
    this.getLocation();
  }

  public get locationForAlgolia(): string {
    return `${this.latitude},${this.longitude}`;
  }

  private switchTab(tab: number) {
    this.filterDistance = 1609;

    if (tab === 0) {
      this.isServicesTab = true;
      this.isDirectoryTab = false;

      return;
    }

    this.isServicesTab = false;
    this.isDirectoryTab = true;
  }

  // eslint-disable-next-line class-methods-use-this
  private getLocation() {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        this.latitude = position.coords.latitude;
        this.longitude = position.coords.longitude;
      },
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      () => (this as any).setError('Please give the browser the permission to access your location so you can see the feed.'),
    );
  }
}
</script>

<style scoped>
  .bottom-right {
    bottom: 1.5rem;
    right: 1.5rem;
  }
</style>
