<template>
  <main>
    <NavigationBar />
    <div class="flex py-8 px-6">
      <div class="w-24 h-24 bg-gray-100 rounded-full"></div>
      <div class="flex-1 pl-4">
        <h1 class="mb-2 text-xl font-bold text-gray-900"
          v-if="driverProfile">{{ driverProfile.name }}</h1>
      </div>
    </div>
    <ul class="flex mx-6 mb-6 p-1 bg-gray-100 rounded-lg">
      <li class="flex-1">
        <!-- eslint-disable-next-line  -->
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
          @click.prevent="switchTab(0)">Active Job</button>
      </li>
      <li class="flex-1">
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isDirectoryTab  }"
          @click.prevent="switchTab(1)">Job History</button>
      </li>
    </ul>
    <template v-if="isServicesTab">
      <p v-show="unfulfilledJobs.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No active jobs yet!</p>
      <InifiniteScroll v-if="unfulfilledJobsMeta.last_page"
        @inifinite-scroll:fetch="getDriversUnfulfilledJobs"
        :items="unfulfilledJobs"
        :lastPage="unfulfilledJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <router-link class="text-xl font-bold text-gray-900"
              :to="{ name: 'driver.job.active.show', params: { id: item.id } }"
                v-text="item.job.name"
              ></router-link>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">Job completed by <span class="font-medium">{{ item.bid.user.name }}</span></p>
            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">
              Fulfillment status:
              <span class="font-medium text-royal-blue">
                <template v-if="item.fulfilled === true">Fulfilled</template>
                <template v-else>Unfulfilled</template>
              </span>
            </p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Worked on a <span class="font-medium text-royal-blue">{{ item.job.vehicle }}</span></p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">
              <span class="font-medium">{{ item.bid.user.name }}</span>
              said "{{ item.bid.comment }}"
            </p>
          </div>
        </template>
      </InifiniteScroll>
    </template>
    <template v-if="isDirectoryTab">
      <p v-show="fulfilledJobs.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No fulfilled jobs yet!</p>
      <InifiniteScroll v-if="fulfilledJobsMeta.last_page"
        @inifinite-scroll:fetch="getDriversFulfilledJobs"
        :items="fulfilledJobs"
        :lastPage="fulfilledJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <a href="#" class="text-xl font-bold text-gray-900">{{ item.job.name }}</a>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">Job undertaken by <span class="font-medium">{{ item.bid.user.name }}</span></p>
            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">
              Fulfillment status:
              <span class="font-medium text-royal-blue">
                <template v-if="item.fulfilled === true">Fulfilled</template>
                <template v-else>Unfulfilled</template>
              </span>
            </p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ item.job.vehicle }}</span></p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">
              <span class="font-medium">{{ item.bid.user.name }}</span>
              said "{{ item.bid.comment }}"
            </p>
          </div>
        </template>
      </InifiniteScroll>
    </template>
  </main>
</template>

<script lang="ts">
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import InifiniteScroll from '@/components/global/InfiniteScroll.vue';

@Component({
  components: {
    NavigationBar,
    InifiniteScroll,
  },
  computed: {
    ...mapGetters({
      hasError: 'hasError',
      error: 'error',
      user: 'auth/user',
      driverProfile: 'driver/driverProfile',
      fulfilledJobs: 'job/fulfilledJobs',
      unfulfilledJobs: 'job/unfulfilledJobs',
      fulfilledJobsMeta: 'job/fulfilledJobsMeta',
      unfulfilledJobsMeta: 'job/unfulfilledJobsMeta',
    }),
  },
  methods: {
    ...mapActions({
      clearError: 'clearError',
      getDriverProfile: 'driver/getDriverProfile',
      getDriversFulfilledJobs: 'job/getFulfilledJobs',
      getDriversUnfulfilledJobs: 'job/getUnfulfilledJobs',
    }),
  },
})
export default class Profile extends Vue {
  private isServicesTab = true;

  private isDirectoryTab = false;

  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriverProfile(this.$route.params.id);
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversUnfulfilledJobs({ page: 1 });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversFulfilledJobs({ page: 1 });
  }

  private async switchTab(tab: number) {
    if (tab === 0) {
      this.isServicesTab = true;
      this.isDirectoryTab = false;

      return;
    }

    this.isServicesTab = false;
    this.isDirectoryTab = true;
  }

  private async getFulfilledJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversFulfilledJobs({ page: nextPage });
  }

  private async getUnfulfilledJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getDriversUnfulfilledJobs({ page: nextPage });
  }
}
</script>

<style scoped>
  .bottom-right {
    bottom: 1.5rem;
    right: 1.5rem;
  }
</style>
