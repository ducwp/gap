jQuery(document).ready(function ($) {

  function selectDate(date) {
    $('#calendar-wrapper').updateCalendarOptions({
      date: date
    });
    console.log(calendar.getSelectedDate());
  }

  var defaultConfig = {
    weekDayLength: 1,
    date: '10/08/2023',
    onClickDate: selectDate,
    showYearDropdown: true,
    startOnMonday: false,
    prevButton: 'Trước',
    nextButton: 'Tiếp',
    startOnMonday: true,
    monthMap: {
      1: "Tháng 1",
      2: "Tháng 2",
      3: "Tháng 3",
      4: "Tháng 4",
      5: "Tháng 5",
      6: "Tháng 6",
      7: "Tháng 7",
      8: "Tháng 8",
      9: "Tháng 9",
      10: "Tháng 10",
      11: "Tháng 11",
      12: "Tháng 12",
    },
    customDateProps: (date) => ({
      classes: 'date-element date-element-custom',
      data: {
        type: 'date',
        form: 'date-element'
      }
    }),
    customDateHeaderProps: (weekDay) => ({
      classes: 'date-header-element date-header-element-custom',
      data: {
        type: 'date-header',
        form: 'date-header-element'
      }
    }),
    customWeekProps: (weekNo) => ({
      classes: 'week-day-element week-day-element-custom',
      data: {
        type: 'week-day',
        form: 'week-day-element'
      }
    }),
  };


  var calendar = $('#calendar-wrapper').calendar(defaultConfig);
  console.log(calendar.getSelectedDate());
});