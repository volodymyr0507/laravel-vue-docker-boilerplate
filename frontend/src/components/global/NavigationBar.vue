<template>
  <nav class="px-6 py-4 flex flex-row justify-between items-center border-gray-300 border-b-2">
    <router-link :to="{ name: 'home' }">
      <Logo v-if="authenticated" class="h-8" />
      <LogoWithText v-else class="h-8" />
    </router-link>
    <ul class="flex align-middle">
      <template v-if="!authenticated">
        <li>
          <router-link :to="{ name: 'register' }">Register</router-link>
        </li>
        <li class="ml-4">
          <router-link :to="{ name: 'auth.login' }">Login</router-link>
        </li>
      </template>
      <template v-else>
        <li>
          <button class="m font-bold text-royal-blue" @click.prevent="logOut">Log out</button>
        </li>
      </template>
    </ul>
  </nav>
</template>

<script lang="ts">
import { mapGetters, mapActions } from 'vuex';
import { Component, Vue } from 'vue-property-decorator';

import Logo from '@/components/global/Logo.vue';
import LogoWithText from '@/components/global/LogoWithText.vue';

@Component({
  components: {
    Logo,
    LogoWithText,
  },
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
    }),
  },
  methods: {
    ...mapActions({
      logout: 'auth/logout',
    }),
  },
})
export default class NavigationBar extends Vue {
  public async logOut() {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    await (this as any).logout();

    this.$router.replace({ name: 'auth.login' });
  }
}
</script>
