<template>
  <div class="datepicker-container input-group">
    <input
      class="form-input"
      type="text"
      :id="id"
      placeholder="HH/BB/TTTT"
      v-model="date"
      @focus="onFocusDate"
      @blur="onBlurDate"
      @keyup.enter="onBlurDate"
    />
    <button
      @click="onClickCalendarInputButton"
      class="btn btn-primary input-group-btn show-md"
    >
      <i class="form-icon ri-calendar-event-fill mt-2 ri-xl"></i>
    </button>

    <div
      v-if="show"
      v-closable="onOuterCalendar"
      class="calendar-container bg-gray"
    >
      <div class="calendar-date columns">
        <div class="column col-auto">
          <button
            @click.prevent="onClickCalendarMonthButton('prev')"
            class="text-center btn"
          >
            <i class="ri-arrow-left-s-line ri-2x"></i>
          </button>
        </div>
        <div class="column col-mx-auto col-auto text-center mt-2">
          <b>{{ getSelectedMonthYear }}</b>
        </div>
        <div class="column col-auto">
          <button
            @click="onClickCalendarMonthButton('next')"
            class="text-center btn"
          >
            <i class="ri-arrow-right-s-line ri-2x"></i>
          </button>
        </div>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th class="text-center" v-for="values in days" :key="values.id">
              {{ values.alias }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="active" v-for="week in weekOfMonth" :key="week">
            <td
              class="text-center"
              :class="{
                active: activeCalendar(days[index - 1].value[week - 1]),
              }"
              v-for="index in 7"
              :key="index"
              @click="onClickCalendarDay(days[index - 1].value[week - 1])"
            >
              <a
                :class="{
                  'other-month-day': !classCalendar(
                    days[index - 1].value[week - 1]
                  ),
                  'text-gray': disableDayCalendar(
                    days[index - 1].value[week - 1]
                  ),
                  'text-primary': classCalendar(
                    days[index - 1].value[week - 1]
                  ),
                }"
                @click.prevent=""
              >
                {{ dateFilter(days[index - 1].value[week - 1]) }}
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div v-show="show" class="isolate" @click="onOuterCalendarMobile"></div>
</template>

<script>
import { DateTime, Info } from "luxon";
import { computed, reactive, ref, toRef, toRefs, watch } from "vue";
import { defaultDatepicker, helper } from "./DatepickerFunction";
import { random, validation } from "./UtilityFunction";
import UseBreakpoint from "./UseBreakpoint";

export default {
  components: {},
  props: {
    id: { type: String, default: "datepicker" },
    modelValue: { type: String },
    max: { type: String },
    min: { type: String },
  },
  emits: ["update:modelValue"],
  setup(props, { emit }) {
    // const dt = DateTime.local();
    const isMobile = computed(() => {
      return UseBreakpoint().type.value != "lg";
    });
    const current = reactive({
      day: helper.today.day,
      month: helper.today.month,
      year: helper.today.year,
    });
    const day = ref(toRef(current, "day"));
    const month = ref(toRef(current, "month"));
    const year = ref(toRef(current, "year"));
    const date = ref(day.value + "/" + month.value + "/" + year.value);
    const calendar = reactive({
      show: false,
      focusInput: false,
      selectedDay: day.value,
      selectedMonth: month.value,
      selectedYear: year.value,
      selectedYearGroup: year.value - (year.value % 10),
      weekOfMonth: 0,
    });
    const maxDate = computed(() => {
      let max = helper.today;
      if (typeof props.max != "undefined") {
        // let a = props.max.split(",", 3);
        let obj = DateTime.fromFormat(props.max, "d/M/yyyy");
        max = obj.isValid ? obj : defaultDatepicker.max;
      } else {
        max = helper.defaultDatepicker.max;
      }
      console.log("MAX:", max, props.max);
      return max;
    });

    const minDate = computed(() => {
      let min = helper.today;
      console.log(typeof props.min != "undefined", "TEST");
      if (typeof props.min != "undefined") {
        // let a = props.min.split(",", 3);
        // console.log(a, "TEST");
        let obj = DateTime.fromFormat(props.min, "d/M/yyyy");
        min = obj.isValid ? obj : defaultDatepicker.min;
      } else {
        min = defaultDatepicker.min;
      }
      return min;
    });
    const getSelectedMonthYear = computed(() => {
      let month = Info.months();
      return month[calendar.selectedMonth - 1] + " " + calendar.selectedYear;
    });
    const days = computed(() => {
      calendar.weekOfMonth = 0;
      let arrDays = [
        { id: 0, name: "Minggu", alias: "Min", value: [] },
        { id: 1, name: "Senin", alias: "Sen", value: [] },
        { id: 2, name: "Selasa", alias: "Sel", value: [] },
        { id: 3, name: "Rabu", alias: "Rab", value: [] },
        { id: 4, name: "Kamis", alias: "Kam", value: [] },
        { id: 5, name: "Jumat", alias: "Jum", value: [] },
        { id: 6, name: "Sabtu", alias: "Sab", value: [] },
      ];

      let firstDay = helper.getStartDayofMonth(
        calendar.selectedMonth,
        calendar.selectedYear
      );
      let end = helper.getTotalDaysofMonth(
        calendar.selectedMonth,
        calendar.selectedYear
      );
      let endDay = helper.getEndDayofMonth(
        calendar.selectedMonth,
        calendar.selectedYear
      );
      console.log("Get First and End day", firstDay, endDay);
      if (firstDay > 0) {
        calendar.weekOfMonth += 1;
        let monthPrev =
          calendar.selectedMonth == 1
            ? 12
            : parseInt(calendar.selectedMonth, 10) - 1;
        // console.log("Not Sunday:", firstDay);
        let prevDay = helper.getTotalDaysofMonth(
          monthPrev,
          calendar.selectedYear
        );
        for (let index = prevDay; index > prevDay - firstDay; index--) {
          helper.pushArrayDays(
            index,
            monthPrev,
            monthPrev == 12 ? calendar.selectedYear - 1 : calendar.selectedYear,
            arrDays
          );
        }
      }
      for (let index = 1; index <= end; index++) {
        let dayIndex = helper.pushArrayDays(
          index,
          calendar.selectedMonth,
          calendar.selectedYear,
          arrDays
        );
        if (dayIndex == 0) {
          calendar.weekOfMonth += 1;
        }
      }
      if (endDay < 6) {
        let monthNext =
          calendar.selectedMonth == 12 ? 1 : calendar.selectedMonth + 1;
        // console.log("Not Saturday:", firstDay);

        for (let index = 1; index < 7 - endDay; index++) {
          helper.pushArrayDays(
            index,
            monthNext,
            monthNext == 1 ? calendar.selectedYear + 1 : calendar.selectedYear,
            arrDays
          );
        }
      }
      return arrDays;
    });

    const years = computed(() => {
      let arrYear = helper.getGroupOfYear(
        calendar.selectedYear,
        calendar.selectedYearGroup,
        minDate.value,
        maxDate.value
      );

      return arrYear;
    });

    watch(date, (newDate, prevDate) => {
      let arrayDate = newDate.split("/", 3);
      if (arrayDate.length < 3) {
        date.value = prevDate;
      }
    });
    watch(current, (newCurrent) => {
      console.log("Current change", newCurrent);
      date.value = helper.getLocaleString(year.value, month.value, day.value);
      calendar.selectedDay = newCurrent.day;
      calendar.selectedMonth = newCurrent.month;
      calendar.selectedYear = newCurrent.year;
    });

    function onFocusDate() {
      if (isMobile.value) return;
      console.log("Open");
      calendar.show = true;
      calendar.focusInput = true;
    }

    function onClickCalendarInputButton() {
      console.log("Open");
      //   calendar.focusInput = true;
      calendar.show = true;
    }
    function onOuterCalendar() {
      if (!calendar.focusInput && !isMobile.value) {
        console.log("CLOSE");
        calendar.show = false;
      }
    }

    function onOuterCalendarMobile() {
      if (isMobile.value) {
        console.log("CLOSE");
        calendar.show = false;
      }
    }

    function onBlurDate() {
      calendar.focusInput = false;
      let cutDate = [
        date.value.slice(6, 10),
        date.value.slice(3, 5),
        date.value.slice(0, 2),
      ];
      let formatDate = [true, true, true];
      //   let yearGroup = year.value - (year.value % 10);
      let dateRef = [year, month, day];
      for (let index = 2; index >= 0; index--) {
        let value = parseInt(cutDate[index], 10);
        if (isNaN(value) || value == 0) {
          console.log("Is not number/empty in index date: ", index);
          formatDate[index] = false;
          continue;
        }
      }
      for (let index = 0; index < cutDate.length; index++) {
        let value = parseInt(cutDate[index], 10);
        if (index <= 1 && !formatDate[index + 1]) {
          console.log("Format false: ", index);
          cutDate[index] = dateRef[index].value;
          continue;
        }
        if (value > 0 && value <= 9 && index >= 1) {
          console.log("Include 0 to index date: ", index);
          cutDate[index] = "0" + value.toString(10);
          continue;
        }
        if (!(value > 0 && value <= 12) && index == 1) {
          console.log("Maximum/minimum month in index date: ", index);
          cutDate[index] = value > 12 ? "12" : "01";
          continue;
        }
        if (index == 0) {
          if (value < minDate.value.year) {
            console.log("Minimum year in index date: ", index);
            if (
              value >=
                parseInt(minDate.value.toFormat("yyyy").slice(0, 3), 10) + 1 &&
              value <=
                parseInt(maxDate.value.toFormat("yyyy").slice(0, 3), 10) - 1
            ) {
              cutDate[index] = value.toString(10) + "0";
            } else if (
              value > parseInt(maxDate.value.year.toString(10).slice(2, 4), 10)
            ) {
              cutDate[index] =
                value < 10
                  ? "190" + value.toString(10)
                  : "19" + value.toString(10);
              if (parseInt(cutDate[index], 10) < minDate.value.year) {
                cutDate[index] = minDate.value.year;
              }
            } else if (
              value >= 1 &&
              value <= parseInt(maxDate.value.year.toString(10).slice(2, 4), 10)
            )
              cutDate[index] =
                value < 10
                  ? "200" + value.toString(10)
                  : "20" + value.toString(10);
            else cutDate[index] = minDate.value.year.toString();
            if (parseInt(cutDate[index], 10) >= maxDate.value.year) {
              cutDate[index] = maxDate.value.year.toString(10);
            }
            continue;
          }
          if (value >= maxDate.value.year) {
            console.log("Maximal year in index date: ", index);
            cutDate[index] = maxDate.value.year.toString(10);
          }
        }
        if (index == 2) {
          //   let first = 1;
          let date = DateTime.local(
            parseInt(cutDate[0], 10),
            parseInt(cutDate[1], 10),
            value
          ).startOf("days");
          console.log(date);
          if (date >= minDate.value && date <= maxDate.value) {
            cutDate[index] =
              value > 0 && value <= 9
                ? "0" + value.toString("10")
                : value.toString("10");
            continue;
          } else {
            console.log("Not day format in index date: ", index);
            cutDate[index] = day.value;
            continue;
          }
        }
        if (!validation.isNumber(cutDate[index])) {
          console.log("Have special character in index date: ", index);
          cutDate[index] = dateRef[index].value;
          continue;
        }
      }
      calendar.selectedDay = day.value = parseInt(cutDate[2], 10);
      calendar.selectedMonth = month.value = parseInt(cutDate[1], 10);
      calendar.selectedYear = year.value = parseInt(cutDate[0], 10);
      calendar.selectedYearGroup = year.value - (year.value % 10);
      date.value = helper.getLocaleString(year.value, month.value, day.value);
      emit(
        "update:modelValue",
        helper.getLocaleString(year.value, month.value, day.value)
      );
      //   console.log(
      //     "day",
      //     cutDate[2],
      //     "month",
      //     cutDate[1],
      //     "year",
      //     cutDate[0],
      //     "date",
      //     cutDate
      //   );
    }

    function onClickCalendarMonthButton(params) {
      let month = calendar.selectedMonth;
      if (params === "next") {
        if (
          calendar.selectedMonth + 1 == 13 &&
          calendar.selectedYear + 1 > maxDate.value.year
        ) {
          return;
        }
        month += 1;
        calendar.selectedMonth = month;
        if (month == 13) {
          calendar.selectedMonth = 1;
          calendar.selectedYear = calendar.selectedYear + 1;
        }
      } else {
        if (
          calendar.selectedMonth - 1 == 0 &&
          calendar.selectedYear - 1 < minDate.value.year
        ) {
          return;
        }
        month -= 1;
        calendar.selectedMonth = month;
        if (calendar.selectedMonth == 0) {
          calendar.selectedMonth = 12;
          calendar.selectedYear = calendar.selectedYear - 1;
        }
      }
    }
    function onClickCalendarDay(index) {
      let d = DateTime.local(index.year, index.month, index.value).startOf(
        "day"
      );
      let max = maxDate.value;
      let min = minDate.value;
      console.log(
        "Sos",
        d.toLocaleString(),
        min.toLocaleString(),
        max.toLocaleString()
      );
      if (d < min || d > max) {
        console.log("Unexpected minimal max clicked");
        calendar.show = false;
        return;
      }
      if (typeof index != "undefined") {
        console.log("Day clicked");
        day.value = index.value;
        month.value = index.month;
        year.value = index.year;
        // console.log(current.value.toObject());
      }
      calendar.show = false;
      emit(
        "update:modelValue",
        helper.getLocaleString(year.value, month.value, day.value)
      );
    }

    function dateFilter(val) {
      return typeof val == "undefined" ? "" : val.value;
    }

    function classCalendar(val) {
      if (typeof val != "undefined") {
        // console.log("class", val.month);
        return calendar.selectedMonth == val.month;
      }
      return false;
    }

    function activeCalendar(index) {
      if (typeof index != "undefined") {
        return (
          calendar.selectedDay == index.value &&
          calendar.selectedMonth == index.month &&
          calendar.selectedYear == index.year
        );
      }
      return false;
    }

    function disableDayCalendar(index) {
      let date = DateTime.local(index.year, index.month, index.value);
      date >= minDate.value && date <= maxDate.value;
      return !(date >= minDate.value && date <= maxDate.value);
    }

    return {
      day,
      days,
      month,
      year,
      years,
      date,
      maxDate,
      minDate,
      activeCalendar,
      isMobile,
      onBlurDate,
      onClickCalendarMonthButton,
      onClickCalendarDay,
      onClickCalendarInputButton,
      onOuterCalendarMobile,
      onFocusDate,
      onOuterCalendar,
      dateFilter,
      disableDayCalendar,
      classCalendar,
      getSelectedMonthYear,
      ...toRefs(calendar),
    };
  },
};
</script>

<style scoped>
.btn:hover {
  color: #fff !important;
}
.btn:focus {
  color: #fff !important;
}
.calendar-container {
  position: absolute;
  max-width: 400px;
  min-height: 200px;
  margin-top: 40px;
  z-index: 2;
}

.calendar-date {
  min-height: 30px;
}

.other-month-day {
  color: #184c8f71;
}

.table {
  table-layout: fixed;
}
.table tbody tr td.active {
  background-color: #184c8f;
  color: #fff !important;
}

.table tbody tr td.active a {
  color: #fff !important;
}

.table tbody tr td:hover a {
  text-decoration: none;
}

.table tbody tr td:hover {
  cursor: pointer;
  background-color: #184c8f;
}
.table tbody tr td:hover a {
  color: #fff !important;
}

@media only screen and (max-width: 840px) {
  .calendar-container {
    position: fixed;
    /* top: 30vh; */
    /* left: 0; */
    width: 100%;
    z-index: 10;
    /* margin-top:0; */
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }

  .isolate {
    position: fixed;
    z-index: 9;
    height: 100vh;
    width: 110vw;
    top: 0;
    left: -10;
    background-color: rgba(53, 51, 51, 0.466);
  }
}
</style>
