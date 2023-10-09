jQuery(document).ready(function ($) {

  const today = new Date();
  const date_str = (today.getMonth() + 1) + '/' + today.getDate() + '/' + today.getFullYear();
  $('#gap_date').val(date_str);

  function selectDate(date) {
    $('#calendar-wrapper').updateCalendarOptions({
      date: date
    });
    $('#gap_date').val(calendar.getSelectedDate());
    console.log(calendar);
    //console.log(calendar.getSelectedDate());
  }

  var defaultConfig = {
    weekDayLength: 1,
    date: date_str,
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

/*---------------------------------------------------------------------------*/


const daysTag = document.querySelector(".days"),
  currentDate = document.querySelector(".current-date"),
  prevNextIcon = document.querySelectorAll(".icons span");

let date = new Date(),
  currYear = date.getFullYear(),
  currMonth = date.getMonth();

/* const months = ["January", "February", "March", "April", "May", "June", "July",
  "August", "September", "October", "November", "December"]; */

const months = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7",
  "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];

const renderCalendar = () => {
  let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
    lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
    lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
    lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
  let liTag = "";

  for (let i = firstDayofMonth; i > 0; i--) {
    liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
  }

  for (let i = 1; i <= lastDateofMonth; i++) {
    let isToday = i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear() ? "active" : ""; liTag += `<li data-mdy="${currMonth+1}/${i}/${currYear}" class="${isToday}">${i}</li>`;
  }

  for (let i = lastDayofMonth; i < 6; i++) {
    liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
  }
  currentDate.innerText = `${months[currMonth]} ${currYear}`;
  daysTag.innerHTML = liTag;
}
renderCalendar();

prevNextIcon.forEach(icon => {
  icon.addEventListener("click", () => {
    currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

    if (currMonth < 0 || currMonth > 11) {
      date = new Date(currYear, currMonth);
      currYear = date.getFullYear();
      currMonth = date.getMonth();
    } else {
      date = new Date();
    }
    renderCalendar();
  });
});



/* const element = document.querySelectorAll("li");
element.addEventListener("click", () => {
  alert(element.textContent);
}); */


document.querySelector(".days").addEventListener("click", function (e) {
  if (e.target && e.target.matches("li")) {
    //data-mdy
    const mdy = e.target.getAttribute('data-mdy');
    //alert(mdy);
    document.getElementById('gap_date').value = mdy;
    //alert(e.target.textContent);
    //e.target.className = "foo"; // new class name here
  }
});