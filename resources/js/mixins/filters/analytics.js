import { mapState } from 'vuex';

export default {
  props: {
    urlParams: {
      type: Object,
      default: () => route().params
    }
  },
  data() {
    return {
      filterStorePrefix: 'analyticsFilters',
      filters: {
        startDate: null,
        finishDate: null
      }
    }
  },
  computed: {
		...mapState([
			'currentTenant'
		]),
		urlFilter() {
      const { startDate, finishDate } = this.filters;
			return  {
				tenant: this.currentTenant.id,
				start_date: startDate.toISOString(),
				finish_date: finishDate.toISOString()
			}
		},
    formatedFilters() {
      const { startDate, finishDate } = this.filters;
      return {
        startDate: this.$moment(startDate).format('YYYY-MM-DD'),
        finishDate: this.$moment(finishDate).format('YYYY-MM-DD'),
      }
    }
	},
  watch: {
    filters: {
      handler(val){
        this.saveFilters();
        this.updateParams();
      },
      deep: true
   }
  },
  methods: {
    getSavedFilters() {
      const savedFilters = JSON.parse(localStorage.getItem(this.filterStorePrefix));
      const { startDate, finishDate } = savedFilters || {};
      return {
        startDate: this.isValidDate(startDate) ? 
          this.stringToDate(startDate)
          : this.$moment().startOf('day').subtract(1, 'months').toDate(),
        finishDate: this.isValidDate(finishDate) ?
          this.stringToDate(finishDate)
          : this.$moment().startOf('day').toDate()
      }
      
    },
    isValidDate (date) {
      return date ? this.$moment(date, 'YYYY-MM-DD').isValid() : false;
    },
    stringToDate (dateString) {
      if(!dateString) return;
      return this.$moment(dateString, 'YYYY-MM-DD').toDate()
    },
    saveFilters(){
      localStorage.setItem(this.filterStorePrefix, JSON.stringify(this.filters))
    },
    updateParams () {
      let url = new URL(location.href);
      let params = url.searchParams;
      params.forEach((value, key) => {
          url.searchParams.delete(key);
      });

      Object.entries(this.formatedFilters).forEach(([key, value]) => {
          if ((value && !Array.isArray(value)) || ( value  && Array.isArray(value) && value.length > 0)) {
              url.searchParams.set(key, value);
          } else {
              url.searchParams.delete(key);
          }
      });
      window.history.pushState({}, document.title, url.toString());
    },
  },
  created () {
    this.filters = this.getSavedFilters()
    const { startDate, finishDate } = this.urlParams;
    if (this.isValidDate(startDate)) 
      this.filters.startDate = this.stringToDate(startDate);

    if (this.isValidDate(finishDate))
      this.filters.finishDate = this.stringToDate(finishDate);
    
    
    this.$nextTick(function () {
      this.saveFilters();
      this.updateParams();
    })
  }
};