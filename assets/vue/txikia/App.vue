<template>
  <div class="row">
    <div class="col-12">
      <a href="javascript:void(0);" v-on:click="showEgutegiakHandler">Aukeratu egutegia</a>
      <ul class="list-unstyled" v-show="showEgutegiak">
        <li v-for="calendar in calendars" :key="calendar.id">
          <input type="checkbox" :id="calendar.id" v-model="calendar.checked" v-on:change="filterCalendar(calendar.id)">
          {{calendar.name}}
        </li>
      </ul>
    </div>
    <div class="col-12">
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
          <h5 class="card-title">{{ item.start | formatDateToHour  }} - {{ item.title }}</h5>
          <p class="card-text" v-html="item.body" v-links-in-new-window></p>
        </div>
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
      showEgutegiak: false,
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
      this.processGetSchedules(response);
    })
    this.axios.get('/api/calendars.json').then((response) => {
      let arrCalendar = [];
      response.data.map(function (c) {
        let cloneCalendar = {};
        for(let k in c) cloneCalendar[k]=c[k];
        cloneCalendar.checked=false;
        arrCalendar.push(cloneCalendar);
      });
      this.calendars = arrCalendar;
    })
  },
  methods: {
    showEgutegiakHandler() {
      this.showEgutegiak = !this.showEgutegiak;
    },
    processGetSchedules(response) {
      let dates=[]
      let items=[];
      response.data.forEach(function(item) {
        const dStart = new Date(item.start);
        const strStart = date2format(dStart);
        const dEnd = new Date(item.end);
        const strEnd = date2format(dEnd);
        if (strStart === strEnd) {
          let tempStart = {};
          for(let k in item) tempStart[k]=item[k];
          let fet = new Date(tempStart.start);
          tempStart.start = fet.toUTCString();
          dates.indexOf(item.start) === -1 ? dates.push(fet) : console.log("This item already exists");
          // dates.indexOf(new Date(item.start)) === -1 ? items.push(item) : console.log("This item already exists");
          dates.indexOf(item.start) === -1 ? items.push(tempStart) : console.log("This item already exists");
        } else {
          let index = -1;
          for (let hasi=new Date(item.start); hasi <= dEnd; hasi.setDate(hasi.getDate() + 1)) {
            index++;
            let dcopy = new Date(hasi);
            let dEnd = new Date(item.end)
            let dcopy1 = new Date(item.start);
            let temp = {};
            for(let k in item) temp[k]=item[k];
            temp.start = dcopy.toUTCString();
            temp.end = dEnd.toUTCString();
            const dago = isInState(dcopy, dates);
            if ( !dago ) {
              dates.push(dcopy);
              items.push(temp);
            }
          }
        }
      });
      this.highlighted.dates = dates;
      this.schedules = items;
    },
    filterCalendar(calendarid) {
      this.calendars.forEach(function (c){
        c.checked = c.id === calendarid;
      })
      if (!this.isFirstFilter) {
        this.highlighted.dates = [];
        this.isFirstFilter = true;
      }
      const calendarIRI = "/api/calendars/" + calendarid;
      const url3 = Routing.generate('api_schedules_get_collection', {"calendar":  calendarIRI }, false);
      this.axios.get(url3).then((response) => {
        this.processGetSchedules(response);
      })
    },
    daySelectHandler(val) {
      const fetxa = new Date(val);
      const strFetxa = date2format(fetxa);
      let selekzioa = [];
      this.schedules.forEach(function (s) {
        const sStart = date2format(new Date(s.start));
        const sEnd = date2format(new Date(s.end));
        if ( sStart === strFetxa ) {
          selekzioa.push(s);
        }
      });
      this.selected = selekzioa;
    }
  }
}
</script>

<style scoped>

</style>
