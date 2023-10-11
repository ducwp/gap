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
    liTag += `<li class="inactive"><label><span class="gt_text">${lastDateofLastMonth - i + 1}</span></label></li>`;
  }

  let toDay = new Date();
  let toDayStr = (toDay.getMonth() + 1) + '/' + toDay.getDate() + '/' + toDay.getFullYear();

  for (let i = 1; i <= lastDateofMonth; i++) {

    //let isTodayRadioChecked = (i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear()) ? "checked" : "";

    const dmy = `${i}/${currMonth + 1}/${currYear}`;

    let d1 = `${currMonth + 1}/${i}/${currYear}`;
    let date1 = new Date(d1).getTime();
    let date2 = new Date(toDayStr).getTime();

    let liClass = '';
    let dis = '';
    let isChecked = '';

    if (date1 < date2) {
      liClass = 'past';
      dis = 'disabled';
    } else if (date1 == date2) {
      isChecked = 'checked'
    } else {
      liClass = '';
      dis = '';
    }

    liTag += `<li class="${liClass}"><label>`;
    liTag += `<input type="radio" name="gap_date" value="${dmy}" ${isChecked} ${dis} /> `;
    liTag += `<span class="gt_text">${i}</span></label></li>`;
  }

  for (let i = lastDayofMonth; i < 6; i++) {
    liTag += `<li class="inactive"><label><span class="gt_text">${i - lastDayofMonth + 1}</span></label></li>`
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