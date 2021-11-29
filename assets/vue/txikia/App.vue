<template>
  <div class="row">
    <div class="col-12 text-center">
      <datepicker
          :language="eu"
          :inline="true"
          :highlighted="highlighted"
          :monday-first=true
          @selected="daySelectHandler">
          v-model="selectedDate"
      </datepicker>
    </div>

    <div class="col-12" v-for="(item, index) in selected">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title">{{ item.title }}</h5>

          <p class="card-text" v-html="item.body"></p>

        </div>
        <div class="card-footer">
          <ul class="list-inline">
            <li class="list-inline-item">Hasi:&nbsp;&nbsp;{{ item.start | luxon('yyyy-MM-dd HH:mm:ss') }}</li>
            <li class="list-inline-item">Amaitu:&nbsp;&nbsp;{{ item.end | luxon('yyyy-MM-dd HH:mm:ss') }}</li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</template>

<script>

import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale/'

import eu from './eu.js'

export default {
  name: "App",
  data () {
    return {
      en: en,
      es: es,
      eu: eu,
      selected: [],
      selectedDate:null,
      highlighted: {
          // to: new Date(2016, 0, 5), // Highlight all dates up to specific date
          // from: new Date(2016, 0, 26), // Highlight all dates after specific date
          // days: [6, 0], // Highlight Saturday's and Sunday's
          // daysOfMonth: [15, 20, 31], // Highlight 15th, 20th and 31st of each month
          dates: [ // Highlight an array of dates
            // new Date(2021, 11, 16),
            // new Date(2021, 11, 17),
            // new Date(2021, 11, 18)
          ],
          // a custom function that returns true of the date is highlighted
          // this can be used for wiring you own logic to highlight a date if none
          // of the above conditions serve your purpose
          // this function should accept a date and return true if is highlighted
          customPredictor: function(date) {
            // highlights the date if it is a multiple of 4
            // if(date.getDate() % 4 == 0){
            //   return true
            // }
          },
          includeDisabled: true // Highlight disabled dates

      }
    }
  },
  components: {
    Datepicker
  },
  mounted() {
    this.axios.get('/api/schedules.json').then((response) => {
      let dates=[]
      response.data.forEach(function(item) {
          dates.push(
              new Date(item.start)
          )
      });
      this.highlighted.dates = dates;
    })
  },
  methods: {
    daySelectHandler(val) {
      const fetxa = new Date(val);
      console.log(fetxa);
      const strStart = fetxa.getUTCFullYear()
          + "-"
          + ('0' + (fetxa.getMonth()+1)).slice(-2)
          + "-"
          + ('0' + fetxa.getDate()).slice(-2);

      fetxa.setDate(fetxa.getDate() + 1);

      const strEnd = fetxa.getUTCFullYear()
          + "-"
          + ('0' + (fetxa.getMonth()+1)).slice(-2)
          + "-"
          + ('0' + (fetxa.getUTCDate())).slice(-2);
      console.log(strStart);
      console.log(strEnd);
      const url = routing.generate('api_schedules_get_collection', {
        "start[after]": strStart,
        "end[before]": strEnd,
      });
      console.log(url);

      this.axios.get(url).then((response) => {
        console.log(response.data)
        let selected = [];
        response.data.forEach(function(item) {
          console.log(item);
          const resp = {
            title: item.title,
            start: item.start,
            end: item.end,
            body: item.body
          }
          selected.push(resp);
        });
        this.selected = selected;
      })
    },
  }
}
</script>

<style scoped>
.row {
  --bs-gutter-x: -4.5rem !important;
}
</style>
