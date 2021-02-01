<template>
  <div>
    <div v-for="item in items" :key="item.id">
      <slot name="item" v-bind:item="item" />
    </div>
    <div v-if="items.length" v-observe-visibility="handleScrolledToBottom"></div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  props: {
    items: {
      type: Array,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
  },
})
export default class InfiniteScroll extends Vue {
  private currentPage = 1;

  private handleScrolledToBottom(visible: boolean) {
    if (!visible) {
      return;
    }

    if (this.currentPage >= this.$props.lastPage) {
      return;
    }

    this.currentPage += 1;

    this.$emit('inifinite-scroll:fetch', this.currentPage);
  }
}
</script>
