<template>
  <main>
    <NavigationBar />
    <div class="flex py-8 px-6">
      <div class="w-24 h-24 bg-gray-100 rounded-full"></div>
      <div class="flex-1 pl-4">
        <!-- eslint-disable-next-line  -->
        <h1 class="mb-2 text-xl font-bold text-gray-900"
          v-if="mechanicProfile">{{ mechanicProfile.name }}</h1>
        <p class="font-medium text-gray-700"
          v-if="mechanicProfile.address">{{ mechanicProfile.address.address }}</p>
      </div>
    </div>
    <ul class="flex mx-6 mb-6 p-1 bg-gray-100 rounded-lg" v-if="user.id === mechanicProfile.id">
      <li class="flex-1">
        <!-- eslint-disable-next-line  -->
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isServicesTab  }"
          @click.prevent="switchTab(0)">Services</button>
        </li>
      <li class="flex-1">
        <button class="w-full p-2 font-bold text-gray-900"
          :class="{ 'bg-white rounded-md shadow-sm ': isDirectoryTab  }"
          @click.prevent="switchTab(1)">Active Jobs</button>
        </li>
    </ul>
    <template v-if="isServicesTab">
      <InifiniteScroll v-if="mechanicServicesMeta.last_page"
        @inifinite-scroll:fetch="getServices"
        :items="mechanicServices"
        :lastPage="mechanicServicesMeta.last_page">
        <template v-slot:item="{ item }">
          <ServiceItem :name="item.name"
            :cost="item.cost"
            :description="item.description"
            :mechanic="item.mechanic" />
        </template>
      </InifiniteScroll>
    </template>
    <template v-if="isDirectoryTab">
      <p v-show="unfulfilledJobs.length === 0"
        class="mt-48 px-6 text-center text-gray-700 text-xl">No active jobs yet!</p>
      <InifiniteScroll v-if="unfulfilledJobsMeta.last_page"
        @inifinite-scroll:fetch="getMechanicsUnfulfilledJobs"
        :items="unfulfilledJobs"
        :lastPage="unfulfilledJobsMeta.last_page">
        <template v-slot:item="{ item }">
          <div class="mx-6 mb-8 rounded-md border-gray-300 border-2">
            <div class="flex flex-col p-4 bg-gray-100 rounded-tl-md rounded-tr-md">
              <a href="#" class="text-xl font-bold text-gray-900">{{ item.job.name }}</a>
              <!-- eslint-disable-next-line  -->
              <p class="mt-2 text-gray-700">
                Posted by <span class="font-medium">{{ item.job.user.name }}</span>
                in <span class="font-medium">{{ item.job.category.name }}</span>
              </p>
            </div>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 px-4 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ item.job.vehicle }}</span></p>
            <!-- eslint-disable-next-line  -->
            <p class="mt-4 mb-4 px-4 text-gray-900 leading-relaxed whitespace-pre-line">{{ item.job.description }}</p>
            <!-- fulfillJob -->
            <!-- eslint-disable-next-line  -->
            <button class="w-full block mt-6 p-4 text-white text-center font-bold bg-royal-blue rounded-bl-md rounded-br-md disabled:opacity-50 disabled:pointer-events-none"
              @click="fulfillJob(item.id)"
              :disabled="fulfilledJobs.includes(item.id)">
              Mark fulfilled
            </button>
          </div>
        </template>
      </InifiniteScroll>
    </template>
    <!-- eslint-disable-next-line  -->
    <button class="flex justify-center items-center fixed bottom-right w-16 h-16 text-4xl bg-royal-blue shadow-md rounded-full text-white font-medium"
      v-if="user.id === mechanicProfile.id"
      @click.prevent="gotoCreateService">
      +
    </button>
  </main>
</template>

<script lang="ts">
import axios from 'axios';
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';
import InifiniteScroll from '@/components/global/InfiniteScroll.vue';
import ServiceItem from './ServiceItem.vue';

@Component({
  components: {
    NavigationBar,
    InifiniteScroll,
    ServiceItem,
  },
  computed: {
    ...mapGetters({
      hasError: 'hasError',
      error: 'error',
      user: 'auth/user',
      mechanicProfile: 'mechanic/mechanicProfile',
      mechanicServices: 'mechanic/services',
      mechanicServicesMeta: 'mechanic/servicesMeta',
      unfulfilledJobs: 'job/unfulfilledJobs',
      unfulfilledJobsMeta: 'job/unfulfilledJobsMeta',
    }),
  },
  methods: {
    ...mapActions({
      clearError: 'clearError',
      getMechanicProfile: 'mechanic/getMechanicProfile',
      getMechanicServices: 'mechanic/getMechanicServices',
      clearMechanicServices: 'mechanic/clearMechanicServices',
      getMechanicsUnfulfilledJobs: 'job/getUnfulfilledJobs',
    }),
  },
})
export default class Profile extends Vue {
    private isServicesTab = true;

  private isDirectoryTab = false;

  private fulfilledJobs: number[] = [];

  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicProfile(this.$route.params.id);
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicServices({ id: this.$route.params.id });
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicsUnfulfilledJobs({ page: 1 });
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

  private async getUnfulfilledJobs(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicsUnfulfilledJobs({ page: nextPage });
  }

  private async getServices(nextPage: number) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getMechanicServices({
      id: this.$route.params.id,
      page: nextPage,
    });
  }

  // eslint-disable-next-line class-methods-use-this
  private fulfillJob(job: number) {
    this.fulfilledJobs.push(job);

    axios.post(`api/v1/drivers/jobs/fulfill/${job}`)
      .then(async () => {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        await (this as any).getMechanicsUnfulfilledJobs({ page: 1 });

        const index = this.fulfilledJobs.indexOf(job);
        if (index > -1) {
          this.fulfilledJobs.splice(index, 1);
        }
      });
  }

  public async gotoCreateService() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).clearMechanicServices();

    this.$router.push({ name: 'mechanic.service.create' });
  }
}
</script>

<style scoped>
  .bottom-right {
    bottom: 1.5rem;
    right: 1.5rem;
  }
</style>
