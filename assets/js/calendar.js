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

    let isTodayRadioChecked = i === date.getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear() ? "checked" : "";

    const mdy = `${currMonth + 1}/${i}/${currYear}`;
    liTag += `<li data-mdy="${mdy}">`;
    liTag += `<label><input type="radio" name="gap_day" value="${mdy}" ${isTodayRadioChecked} /> `;
    liTag += `<span class="gt_text">${i}</span></label></li>`;
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

/* radiobtn = document.getElementById("theid");
radiobtn.checked = true; */

/* const element = document.querySelectorAll("li");
element.addEventListener("click", () => {
  alert(element.textContent);
}); */


/* document.querySelector(".days").addEventListener("click", function (e) {
  if (e.target && e.target.matches("li")) {
    if (e.target.classList.contains('inactive')) {
      return;
    }

    const others_li = document.querySelector('.days li.active');
    if (typeof (others_li) != 'undefined' && others_li != null) {
      others_li.className = '';
    }

    e.target.className = "active";

    const mdy = e.target.getAttribute('data-mdy');
    //alert(mdy);
    document.getElementById('gap_date').value = mdy;
    //alert(e.target.textContent);
    //e.target.className = "foo"; // new class name here
  }
}); */