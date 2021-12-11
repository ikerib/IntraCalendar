<template>
  <div class="row">
    <div class="col-6">
      <datepicker
          :language="eu"
          :inline="true"
          :highlighted="highlighted"
          :monday-first=true
          @selected="daySelectHandler">
          v-model="selectedDate"
      </datepicker>
    </div>
    <div class="col-5">
      <ul class="list-unstyled">
        <li v-for="calendar in calendars" :key="calendar.id">
          <input type="checkbox"  v-on:change="filterCalendar(calendar.id)">{{calendar.name}}
        </li>
      </ul>
    </div>

    <div class="col-12" v-for="(item, index) in selected">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title">{{ item.start | luxon('HH:mm') }} - {{ item.title }}</h5>

          <p class="card-text" v-html="item.body" v-links-in-new-window></p>

        </div>
<!--        <div class="card-footer">-->
<!--          <ul class="list-inline">-->
<!--            <li class="list-inline-item">Hasi:&nbsp;&nbsp;{{ item.start | luxon('yyyy-MM-dd HH:mm:ss') }}</li>-->
<!--            <li class="list-inline-item">Amaitu:&nbsp;&nbsp;{{ item.end | luxon('yyyy-MM-dd HH:mm:ss') }}</li>-->
<!--          </ul>-->
<!--        </div>-->
      </div>
    </div>

  </div>
</template>

<script>

import Datepicker from 'vuejs-datepicker';
import {en, es} from 'vuejs-datepicker/dist/locale/'

import eu from './eu.js'
const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
window.routing = Routing
function date2format(fetxa) {
  return fetxa.getUTCFullYear() + "-" + ('0' + (fetxa.getMonth()+1)).slice(-2) + "-" + ('0' + fetxa.getDate()).slice(-2);
}
function isInState(fetxa, dates) {
  const strFetxa = date2format(fetxa); // 2021-12-30
  let resp = false;

  dates.forEach(function (d) {
    const current = date2format(d);
    if ( current === strFetxa) {
      resp = true;
    }
  });

  return resp;
}
export default {
  name: "App",
  data () {
    return {
      en: en,
      es: es,
      eu: eu,
      isFirstFilter: false,
      calendars: [],
      selected: [],
      selectedDate:null,
      schedules: [],
      highlighted: {
          dates: [],
          customPredictor: function(date) {
          },
          includeDisabled: true
      }
    }
  },
  components: {
    Datepicker
  },
  mounted() {
    this.axios.get('/api/schedules.json').then((response) => {
      let dates=[]
      this.schedules = response.data;
      response.data.forEach(function(item) {
        const dStart = new Date(item.start);
        const strStart = date2format(dStart);
        const dEnd = new Date(item.end);
        const strEnd = date2format(dEnd);
        if (strStart === strEnd) {
          // dates.push(new Date(item.start))
          dates.indexOf(new Date(item.start)) === -1 ? dates.push(new Date(item.start)) : console.log("This item already exists");
        } else {
          let index = -1;
          for (let hasi=new Date(item.start); hasi <= dEnd; hasi.setDate(hasi.getDate() + 1)) {
            index++;
            let dcopy = new Date(hasi);
            let dcopy1 = new Date(item.start);
            // dcopy.setDate(dcopy.getDate() + index + 1)
            const dago = isInState(dcopy, dates);
            if ( !dago ) {
              dates.push(dcopy);
            }
          }
        }
      });
      this.highlighted.dates = dates;
    })
    this.axios.get('/api/calendars.json').then((response) => {
      this.calendars = response.data;
    })
  },
  methods: {
    filterCalendar(calendarid) {
      if (!this.isFirstFilter) {
        this.highlighted.dates = [];
        this.isFirstFilter = true;
      }
      const calendarIRI = "/api/calendars/" + calendarid;
      const url3 = Routing.generate('api_schedules_get_collection', {"calendar":  calendarIRI }, false);

      this.axios.get(url3).then((response) => {
        let dates=[]
        this.schedules = response.data;
        response.data.forEach(function(item) {
          const dStart = new Date(item.start);
          const strStart = date2format(dStart);
          const dEnd = new Date(item.end);
          const strEnd = date2format(dEnd);
          if (strStart === strEnd) {
            // dates.push(new Date(item.start))
            dates.indexOf(new Date(item.start)) === -1 ? dates.push(new Date(item.start)) : console.log("This item already exists");
          } else {
            let index = -1;
            for (let hasi=new Date(item.start); hasi <= dEnd; hasi.setDate(hasi.getDate() + 1)) {
              index++;
              let dcopy = new Date(hasi);
              let dcopy1 = new Date(item.start);
              // dcopy.setDate(dcopy.getDate() + index + 1)
              const dago = isInState(dcopy, dates);
              if ( !dago ) {
                dates.push(dcopy);
              }
            }
          }
        });
        console.log(dates)
        this.highlighted.dates.push (...dates);
      })
    },
    daySelectHandler(val) {
      const fetxa = new Date(val);
      const strStart = date2format(fetxa);
      const strEnd = date2format(fetxa);
      const url = routing.generate('api_schedules_get_collection', {
        "start[before]": strStart,
        "end[after]": strEnd,
      });
      this.axios.get(url).then((response) => {
        let selected = [];
        response.data.forEach(function(item) {
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

</style>
