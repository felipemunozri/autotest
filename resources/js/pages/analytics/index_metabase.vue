<template>
  <div class="iframe">
    <iframe
      :src="iframeURL"
      frameborder="0"
      width="100%"
      style="min-height: 100vh;"
      allowtransparency
      v-if="iframeURL"
    ></iframe>
    <div class="no-analytics is-flex is-justify-content-center is-align-items-center" v-else>
      <div class="has-text-centered">
        <b-icon

            icon="chart-box-outline"
            size="is-large"
            class="has-text-grey-light">
        </b-icon>
        <div class="is-size-6 has-text-grey-light">
          ¡No se encontraron Analíticas disponibles!
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  var jwt = require("jsonwebtoken");

  export default {

    data() {
      return {
        iframeURL: null
      }
    },
    methods: {
      async getMetabaseReport() {
        const { data } = await this.$store.dispatch('metabaseReports/getReports', {
          query:{
            code: 'dashboard'
          }
        });
        const [report] = data;
        return report; 
      },
      async initMetabaseAnalytics() {
        const report = await this.getMetabaseReport();
        if ( !report ) return;

        const payload = {
          resource: { dashboard: parseInt(report.metabase_report_id) },
          params: {},
          exp: Math.round(Date.now() / 1000) + (60 * 60)
        };

        const metabase_url = process.env.MIX_METABASE_SITE_URL;
        const metabase_secret_key = process.env.MIX_METABASE_SECRET_KEY;


        const token = jwt.sign(payload, metabase_secret_key);
        this.iframeURL = `${metabase_url}/embed/dashboard/${token}#bordered=false&titled=false`;
      }
    },
    created() {
      this.initMetabaseAnalytics();
    },
  }
</script>

<style scoped>
.no-analytics{
  min-height: 100vh;
}
</style>
