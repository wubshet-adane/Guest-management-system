//validate password length is greater than or equal to 5
//in add  and edit department.
function validateForm() {
    var x = document.forms["myForm"]["password"].value;
    if (x.length <= 4) {
      document.getElementById("hidenpass").innerHTML="password size must be greater than 4";
      return false;
    }
  }

document.addEventListener('DOMContentLoaded', function () {
    function toggleDisplay(buttonId, displayDivClass) {
        const button = document.getElementById(buttonId);
        const logDiv = button.parentElement.nextElementSibling; // The sibling div with class "hidden"
        const displayDiv = document.querySelector(displayDivClass);

        button.addEventListener('click', function () {
            if (logDiv.classList.contains('hidden')) {
                logDiv.classList.remove('hidden');
                logDiv.classList.add('visible');
                displayDiv.innerHTML = logDiv.innerHTML; // Display the content in the guestdisplay div
            } else {
                logDiv.classList.remove('visible');
                logDiv.classList.add('hidden');
                displayDiv.innerHTML = ''; // Clear the content in the guestdisplay div
            }
        });
    }

    toggleDisplay('toggleTodayguest', '.guestdisplay');
    toggleDisplay('toggleYesterdayguest', '.guestdisplay');
    toggleDisplay('toggleWeeklyguest', '.guestdisplay');
});


