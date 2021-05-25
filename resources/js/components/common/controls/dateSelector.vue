<template>
  <div class="columns is-multiline">
    <div class="column">
        <b-field label="Inicio" label-position="inside" >
            <b-datepicker
                v-model="fromDate"
                :first-day-of-week="1"
                :month-names="$moment.months()"
                :day-names="$moment.weekdaysShort()"
                
                :max-date="toDate"
                :show-week-number="true"
                placeholder="Click para seleccionar"
                icon="calendar-today">
            </b-datepicker>
        </b-field>
    </div>
    <div class="column">
        <b-field label="Fin" label-position="inside">
            <b-datepicker
                v-model="toDate"
                :first-day-of-week="1"
                :month-names="$moment.months()"
                :day-names="$moment.weekdaysShort()"
                :show-week-number="true"
                :min-date="fromDate"
                :max-date="$moment().toDate()"
                placeholder="Click para seleccionar"
                position="is-bottom-left"
                icon="calendar-today">
            </b-datepicker>
        </b-field>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    selectedFromDate: {
      type: Date,
      required: false,
      default: null
    },
    selectedToDate: {
      type: Date,
      required: false,
      default: null
    }
  },
  data () {
    return {
      fromDate: this.selectedFromDate,
      toDate: this.selectedToDate,
    }
  },
  watch: {
    fromDate (value) {
      this.$emit('update:selected-from-date', value)
    },
    toDate (value) {
      this.$emit('update:selected-to-date', value)
    }
  }
}
</script>
