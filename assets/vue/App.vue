
<template>
<div class="row">
  <div class="col-6">
    <select v-model="selectedView">
      <option v-for="(options, index) in viewModeOptions" :value="options.value" :key="index">
        {{options.title}}
      </option>
    </select>
    <span @click="onClickNavi($event)">
      <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">Gaur</button>
      <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">Aurrekoa</button>
      <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">Hurrengoa</button>
    </span>
    <span class="render-range">{{dateRange}}</span>
  </div>

<!--  <div class="row">-->
    <div class="col-6">
      <div id="calendarList" class="lnb-calendars-d1">
        <ul class="list-inline">
          <li class="list-inline-item lnb-calendars-item" v-for="(cal, index) in checkedCalendars" :key="index">
            <label>
              <input type="checkbox"
                     :id="cal.id"
                     class="tui-full-calendar-checkbox-round"
                     v-model="cal.isChecked"
                     @change="onChangeCalendar($event)"
              />
              <span :style="{ borderColor: cal.bgColor, backgroundColor: cal.isChecked ? cal.bgColor : 'transparent' }" />
              <span>{{cal.name}}</span>
            </label>
          </li>
        </ul>
      </div>
    </div>
<!--  </div>-->
  <div class="row">
    <div class="col-12">
      <calendar style="height: 800px"
                ref="tuiCal"
                :useDetailPopup="useDetailPopup"
                :view="selectedView"
                :calendars="calendarList"
                :schedules="scheduleList"
                :theme="theme"
                :template="template"
                :timezones="timezones"
                :taskView="false"
                :scheduleView="true"
                :month="month"
                :week="week"
                :disableDblClick="disableDblClick"
                :isReadOnly="isReadOnly"
                @clickSchedule="onClickSchedule"
                @clickDayname="onClickDayname"
                @beforeDeleteSchedule="onBeforeDeleteSchedule"
                @afterRenderSchedule="onAfterRenderSchedule"
                @clickTimezonesCollapseBtn="onClickTimezonesCollapseBtn"
      />
    </div>
  </div>
</div>
</template>
<script>
import 'tui-time-picker/dist/tui-time-picker.css';
import 'tui-date-picker/dist/tui-date-picker.css';
import 'tui-calendar/dist/tui-calendar.css';
import './app.css';

import {Calendar} from '@toast-ui/vue-calendar';
import myTheme from './myTheme';

const today = new Date();
const axios = require('axios');
const moment = require('moment')

const getDate = (type, start, value, operator) => {
  start = new Date(start);
  type = type.charAt(0).toUpperCase() + type.slice(1);

  if (operator === '+') {
    start[`set${type}`](start[`get${type}`]() + value);
  } else {
    start[`set${type}`](start[`get${type}`]() - value);
  }

  return start;
};

export default {
  name: 'App',
  components: {
    'calendar': Calendar
  },
  data() {
    return {
      checkedCalendars: [],
      filterSchedules: [],
      viewModeOptions: [
        {
          title: 'Hilabeteka',
          value: 'month'
        },
        {
          title: 'Asteka',
          value: 'week'
        },
        {
          title: 'Egunerokoa',
          value: 'day'
        }
      ],
      dateRange: '',
      selectedView: 'month',
      calendarList: [],
      scheduleList: [],
      timezones: [
        // {
        //   zones: [
            {
              timezoneName: 'Europe/Madrid',
              timezoneOffset: 0,
              displayLabel: '',
              tooltip: 'Madrid'
            }
          // ]
        // }
      ],
      theme: myTheme,
      template: {
        alldayTitle: function () {
          return '<span class="tui-full-calendar-left-content">ALL DAY</span>';
        },
        goingDuration: function (schedule) {
          return (
              '<span class="calendar-icon ic-travel-time"></span>' +
              schedule.goingDuration +
              "min."
          );
        },
        comingDuration: function (schedule) {
          return (
              '<span class="calendar-icon ic-travel-time"></span>' +
              schedule.comingDuration +
              "min."
          );
        },
        monthMoreTitleDate: function (date, dayname) {
          let day = date.split(".")[2];

          return (
              '<span class="tui-full-calendar-month-more-title-day">' +
              day +
              '</span> <span class="tui-full-calendar-month-more-title-day-label">' +
              dayname +
              "</span>"
          );
        },
        monthMoreClose: function () {
          return '<span class="tui-full-calendar-icon tui-full-calendar-ic-close"></span>';
        },
        monthGridHeader: function (dayModel) {
          let date = parseInt(dayModel.date.split("-")[2], 10);
          let classNames = ["tui-full-calendar-weekday-grid-date "];

          if (dayModel.isToday) {
            classNames.push("tui-full-calendar-weekday-grid-date-decorator");
          }

          return (
              '<span class="' + classNames.join(" ") + '">' + date + "</span>"
          );
        },
        monthGridHeaderExceed: function (hiddenSchedules) {
          return (
              '<span class="weekday-grid-more-schedules">+' +
              hiddenSchedules +
              "</span>"
          );
        },
        monthGridFooter: function () {
          return "";
        },
        monthGridFooterExceed: function (hiddenSchedules) {
          return "";
        },
        monthDayname: function (model) {
          return model.label.toString().toLocaleUpperCase();
        },
        weekDayname: function (model) {
          return (
              '<span class="tui-full-calendar-dayname-date">' +
              model.date +
              '</span>&nbsp;&nbsp;<span class="tui-full-calendar-dayname-name">' +
              model.dayName +
              "</span>"
          );
        },
        weekGridFooterExceed: function (hiddenSchedules) {
          return "+" + hiddenSchedules;
        },
        dayGridTitle: function (viewName) {
          let title = "";
          switch (viewName) {
            case "milestone":
              title =
                  '<span class="tui-full-calendar-left-content">MILESTONE</span>';
              break;
            case "task":
              title =
                  '<span class="tui-full-calendar-left-content">TASK</span>';
              break;
            case "allday":
              title =
                  '<span class="tui-full-calendar-left-content">EGUN OSOA</span>';
              break;
            default:
              break;
          }

          return title;
        },
        timegridDisplayPrimayTime: function(time) {
          /* will be deprecated. use 'timegridDisplayPrimaryTime' */
          let hour = time.hour;
          return hour + ':00';
        },
        collapseBtnTitle: function () {
          return '<span class="tui-full-calendar-icon tui-full-calendar-ic-arrow-solid-top"></span>';
        },

      },
      month: {
        startDayOfWeek: 1,
        daynames: ['Iga','Ast','Atr','Atz','Ost','Osr','Lar'],
        workweek: true
      },
      week: {
        daynames: ['Iga','Ast','Atr','Atz','Ost','Osr','Lar'],
        startDayOfWeek: 1,
        showTimezoneCollapseButton: true,
        timezonesCollapsed: true,
        hourStart: 6,
        hourEnd: 23,
        workweek: true
      },
      taskView: false,
      scheduleView: false,
      useDetailPopup: true,
      disableDblClick: true,
      isReadOnly: true
    };
  },
  watch: {
    selectedView(newValue) {
      this.$refs.tuiCal.invoke('changeView', newValue, true);
      this.setRenderRangeText();
    }
  },
  methods: {
    init() {
      this.setRenderRangeText();
    },
    setRenderRangeText() {
      const {invoke} = this.$refs.tuiCal;
      const view = invoke('getViewName');
      const calDate = invoke('getDate');
      const rangeStart = invoke('getDateRangeStart');
      const rangeEnd = invoke('getDateRangeEnd');
      let year = calDate.getFullYear();
      let month = calDate.getMonth() + 1;
      let date = calDate.getDate();
      let dateRangeText = '';
      let endMonth, endDate, start, end;

      switch (view) {
        case 'month':
          dateRangeText = `${year}-${month}`;
          break;
        case 'week':
          year = rangeStart.getFullYear();
          month = rangeStart.getMonth() + 1;
          date = rangeStart.getDate();
          endMonth = rangeEnd.getMonth() + 1;
          endDate = rangeEnd.getDate();

          start = `${year}-${month}-${date}`;
          end = `${endMonth}-${endDate}`;
          dateRangeText = `${start} ~ ${end}`;
          break;
        default:
          dateRangeText = `${year}-${month}-${date}`;
      }
      this.dateRange = dateRangeText;
    },
    onClickNavi(event) {
      if (event.target.tagName === 'BUTTON') {
        const {target} = event;
        let action = target.dataset ? target.dataset.action : target.getAttribute('data-action');
        action = action.replace('move-', '');

        this.$refs.tuiCal.invoke(action);
        this.setRenderRangeText();
      }
    },
    setCheckedCalendars() {
      this.calendarList = this.calendarList.map((element) => ({ ...element, isChecked: true }))
    },
    filterCalendar(cloneCheckedCalendars) {
      const filterCalendars = cloneCheckedCalendars
        .filter((element) => element.isChecked === false)
        .map((item) => item.id);
      const cloneSchedules = this.filterSchedules.filter((element) => {
          return filterCalendars.indexOf(element.calendar[0].id) === -1;
      });
      this.scheduleList = cloneSchedules;
    },
    onChangeCalendar(event) {
      const cloneCheckedCalendars = [...this.checkedCalendars];
      cloneCheckedCalendars.forEach((element) => {
        if (element.id === event.target.value)
          element.isChecked = event.target.checked;
      });
      this.setCheckedCalendars(cloneCheckedCalendars);
      this.filterCalendar(cloneCheckedCalendars);
    },
    onClickSchedule(res) {
      console.group('onClickSchedule');
      console.log('MouseEvent : ', res.event);
      console.log('Calendar Info : ', res.calendar);
      console.log('Schedule Info : ', res.schedule);
      console.groupEnd();
      const matches = document.querySelectorAll('pre > a');
      matches.forEach(element => {
        return element.target = "_blank";
      })
    },
    onClickDayname(res) {
      // view : week, day
      // console.group('onClickDayname');
      // console.log(res.date);
      // console.groupEnd();
    },
    onBeforeDeleteSchedule(res) {
      // console.group('onBeforeDeleteSchedule');
      // console.log('Schedule Info : ', res.schedule);
      // console.groupEnd();
      //
      const idx = this.scheduleList.findIndex(item => item.id === res.schedule.id);
      this.scheduleList.splice(idx, 1);
    },
    onAfterRenderSchedule(res) {
      // console.group('onAfterRenderSchedule');
      // console.log('Schedule Info : ', res.schedule);
      // console.groupEnd();
    },
    onClickTimezonesCollapseBtn(timezonesCollapsed) {
      // view : week, day
      // console.group('onClickTimezonesCollapseBtn');
      // console.log('Is Collapsed Timezone? ', timezonesCollapsed);
      // console.groupEnd();
      //
      // if (timezonesCollapsed) {
      //   this.theme['week.timegridLeft.width'] = '100px';
      //   this.theme['week.daygridLeft.width'] = '100px';
      // } else {
      //   this.theme['week.timegridLeft.width'] = '50px';
      //   this.theme['week.daygridLeft.width'] = '50px';
      // }
    }
  },
  mounted() {
    this.init();
  },
  created() {
    let self = this;
    axios.get('/api/calendars.json').then(function (response) {
      self.calendarList = response.data;
      self.checkedCalendars = self.calendarList.map((element) => ({ ...element, isChecked: true }))
    });
    axios.get('/api/schedules.json').then(function (response) {
      let dataOri = response.data;
      dataOri.forEach(element => {
        element.calendarId = element.calendar[0].id;
        return element;
      })
      // convert
      dataOri.forEach(element => {
        element.start = moment(element.start, "YYYY-MM-DDTHH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
        element.end = moment(element.end, "YYYY-MM-DDTHH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
      });
      self.scheduleList = response.data;
      // self.filterSchedules = response.data;
      self.filterSchedules = dataOri;
    });
  }
};
</script>
<style>
</style>
