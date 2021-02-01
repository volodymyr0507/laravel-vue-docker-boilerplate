<template>
  <main>
    <NavigationBar />
    <template v-if="Object.keys(fulfilledJob).length">
      <div class="flex flex-col py-8 px-6 bg-gray-100">
        <h1 class="text-2xl font-bold text-gray-900">Test Job</h1>
        <!-- eslint-disable-next-line  -->
        <p class="mt-4 text-gray-700">Posted by <span class="font-medium">{{ fulfilledJob.job.user.name }}</span> in <span class="font-medium">{{ fulfilledJob.job.category.name }}</span></p>
        <!-- eslint-disable-next-line  -->
        <p class="mt-2 text-gray-700">Job undertaken by <span class="font-medium">{{ fulfilledJob.bid.user.name }}</span></p>
      </div>
      <!-- eslint-disable-next-line  -->
      <p class="mt-8 px-6 text-gray-700">Working on a <span class="font-medium text-royal-blue">{{ fulfilledJob.job.vehicle }}</span></p>
      <!-- eslint-disable-next-line  -->
      <p class="mt-6 mb-8 px-6 text-gray-900 leading-relaxed whitespace-pre-line">{{ fulfilledJob.job.description }}</p>
    </template>
  </main>
</template>

<script lang="ts">
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import NavigationBar from '@/components/global/NavigationBar.vue';

@Component({
  components: {
    NavigationBar,
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
      fulfilledJob: 'driver/fulfilledJob',
    }),
  },
  methods: {
    ...mapActions({
      getFulfilledJob: 'driver/getFulfilledJob',
    }),
  },
})
export default class ActiveJob extends Vue {
  async mounted() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).getFulfilledJob(this.$route.params.id);
  }
}
</script>
