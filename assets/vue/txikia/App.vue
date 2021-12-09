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
      <ul>
        <li>1111111</li>
        <li>1111111</li>
        <li>1111111</li>
        <li>1111111</li>
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

function date2format(fetxa) {
  return fetxa.getUTCFullYear() + "-" + ('0' + (fetxa.getMonth()+1)).slice(-2) + "-" + ('0' + fetxa.getDate()).slice(-2);
}

export default {
  name: "App",
  data () {
    return {
      en: en,
      es: es,
      eu: eu,
      selected: [],
      selectedDate:null,
      schedules: [],
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

    function isInState(fetxa, dates) {
      const strFetxa = date2format(fetxa); // 2021-12-30
      let resp = false;

      dates.forEach(function (d) {
        const current = date2format(d);
        console.log("current => " + current )
        console.log("strFetxa => " + strFetxa )
        if ( current === strFetxa) {
          resp = true;
        }
      });

      return resp;
    }

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
            // console.log(hasi);
            let dcopy = new Date(hasi);
            let dcopy1 = new Date(item.start);
            // dcopy.setDate(dcopy.getDate() + index + 1)
            const dago = isInState(dcopy, dates);

            console.log(dcopy)
            console.log(dcopy1)


            if ( !dago ) {
              dates.push(dcopy);
            }
          }
        }

      });

      this.highlighted.dates = dates;
    })
  },
  methods: {

    // daySelectHandler(val) {
    //   let kk = date2format(val);
    //   this.selected = [];
    //   const fetxa = new Date(val);
    //   const strStart = date2format(fetxa);
    //   console.log(strStart);
    //   this.schedules.map(s => {
    //     const i = new Date(s.start);
    //     const f = date2format(i);
    //     console.log("-------------")
    //     console.log(strStart);
    //     console.log(f);
    //     console.log("-------------")
    //     if (strStart === f) {
    //       this.selected.push(s);
    //     }
    //   })
    // },
    daySelectHandler(val) {
      const fetxa = new Date(val);
      const strStart = date2format(fetxa);

      //fetxa.setDate(fetxa.getDate() + 1);
      //fetxa.setDate(fetxa.getDate() + 1);

      const strEnd = date2format(fetxa);

      const url = routing.generate('api_schedules_get_collection', {
        "start[before]": strStart,
        "end[after]": strEnd,
      });

      console.log(url);

      this.axios.get(url).then((response) => {
        console.log("RESPONSE");
        console.log(response);
        console.log(response.data);
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
