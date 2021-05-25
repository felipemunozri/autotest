<template>
  <card :title="title" class="card-table">
    <div slot="header-left" v-if="showOptions">
      <b-dropdown
        aria-role="list"
        :mobile-modal="false"
        position="is-bottom-left"
      >
        <template #trigger>
          <div class="is-clickable">
            <span class="icon">
              <i class="mdi mdi-18px mdi-dots-vertical"></i>
            </span>
          </div>
        </template>
        <b-dropdown-item aria-role="listitem" @click="viewType = 'table'" v-if="viewType === 'chart'">
          <span class="icon-text">
            <span class="icon">
              <i class="mdi mdi-table-large"></i>
            </span>
            <span>Ver tabla</span>
          </span>
        </b-dropdown-item>
        <b-dropdown-item aria-role="listitem" @click="viewType = 'chart'" v-if="viewType === 'table'">
          <span class="icon-text">
            <span class="icon">
              <i class="mdi mdi-chart-bar"></i>
            </span>
            <span>Ver gráfico</span>
          </span>
        </b-dropdown-item>
        <b-dropdown-item aria-role="listitem">
          
          <a
            :href="downloadUrl"
            download
            class="has-text-dark"
          >
            <span class="icon-text">
              <span class="icon">
                <i class="mdi mdi-download"></i>
              </span>
              <span>Descargar</span>
            </span>
          </a>
        </b-dropdown-item>
      </b-dropdown>
    </div>
    <div slot="content">
      <embeddable-iframe
        :src="url"
      />
    </div>
  </card>
</template>

<script>
import Card from "@/components/common/card";
import EmbeddableIframe from "@/components/common/embeddableIframe";
export default {
  components: {
    Card,
    EmbeddableIframe,
  },
  props:{
    title: {
      type: String,
    },
    showOptions: {
      type: Boolean,
      default: false
    },
    routeName: {
      type: String,
      required: true
    },
    filters: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      viewType: 'chart'
    };
  },
  computed: {
    url() {
      return route(this.routeName, { 
        ...this.filters,
        view: this.viewType,
      })
    },
    downloadUrl() {
      return route('api.exports.embeddable.pdf', {
        url: encodeURIComponent(this.url),
        title: this.title, 
      })
    }
  }
};
</script>

<style lang="scss" scoped>
</style>