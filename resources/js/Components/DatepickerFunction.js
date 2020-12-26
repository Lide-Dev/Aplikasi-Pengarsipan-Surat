// import dayjs from "dayjs";
import { DateTime } from "luxon";

export const defaultDatepicker = {
    min: DateTime.local(1920, 1, 1, 0, 0, 0, 0),
    max: DateTime.local().plus({ year: 59 }).endOf('years'),
}

/**
 * Function to help your calendar
 */
export const helper = {
    today: DateTime.local(),
    getLocaleString: (year,month,day) => DateTime.local(year,month,day).toFormat("dd/MM/yyyy"),
    getPHPFormatString: (year,month,day) => DateTime.local(year,month,day).toFormat("dd-MM-yyyy"),
    /**
     * Get day like monday or anything at first day of month
     * @param {*} month
     * @param {*} year
     */
    getStartDayofMonth: (month, year) => {
        // console.log("test",DateTime.local(year, month, 1).startOf("month").weekday - 1);
        return DateTime.local(year, month, 1).startOf("month").weekday - 1;
    },
    getEndDayofMonth: (month, year) => {
        // console.log("test",DateTime.local(year, month, 1).startOf("month").weekday - 1);
        return DateTime.local(year, month, 1).endOf("month").weekday - 1;
    },
    getStartOf: (year, month, startOf) => DateTime.local(year, month, 1).startOf(startOf),
    /**
     * Get total days of months.
     *
     * @param {*} month
     * @param {*} year
     */
    getTotalDaysofMonth: (month, year) => DateTime.local(year, month).daysInMonth,
    getDayofDate: (day, month, year) => DateTime.local(year, month, day).startOf("day").weekday - 1,
    pushArrayDays: (day, month, year, arrDays) => {
        let dayIndex = helper.getDayofDate(
            day,
            month,
            year
        );
        arrDays[dayIndex].value.push({
            key: day,
            value: day,
            month: month,
            year: year,
        });
        return dayIndex;
    },
    getGroupOfYear: (year, groupYear, minDate = DateTime.local(), maxDate = DateTime.local()) => {
        let arrYear = [];
        let start = 0;
        let end = 0;
        if (year < minDate.plus({ year: 10 }).year) {
            start = minDate.year;
            end = minDate.plus({ year: 9 }).year;
        } else if (year >= maxDate.year) {
            start = maxDate.year - 9;
            end = maxDate.year;
        } else {
            start = groupYear;
            end = groupYear + 9;
        }
        for (let index = start; index <= end; index++) arrYear.push(index);
        return arrYear;
    }

}
