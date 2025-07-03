document.addEventListener('DOMContentLoaded', (event) => {
    let numberCells = document.querySelectorAll('.number');
    numberCells.forEach((cell) => {
        let originalNumber = cell.textContent.trim(); // Get the original number
        let formattedNumber = formatNumberIndianStyle(originalNumber); // Format it
        cell.textContent = formattedNumber; // Update the table cell
    });

     let numberonlyInputs = document.querySelectorAll(".numbersonly");

    numberonlyInputs.forEach(function (input) {
      
       
        input.addEventListener("keypress", function (event) {
            numbersonly(event);
        });

    });
});



function formatNumberIndianStyle(number) {
    let numStr = number.toString();

    // Return the number as-is if it has 3 or fewer digits
    if (numStr.length <= 3) {
        return `${numStr}`; 
    }

    let lastThreeDigits = numStr.slice(-3);
    let remainingDigits = numStr.slice(0, -3);

    // Format the remaining digits with commas
    if (remainingDigits !== '') {
        remainingDigits = remainingDigits.replace(/\B(?=(\d{2})+(?!\d))/g, ",");
    }

    return `${remainingDigits + ',' + lastThreeDigits}`;

}

function numbersonly(evt) {
  var theEvent = evt || window.event;
 // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
 var regex = /^[0-9]+$/;

  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function numbersonlynodecimal(evt) {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /^[0-9]$/;;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
} 

function formatNumber(input) {
    let value = input.value.replace(/,/g, ''); // Remove existing commas
    if (!/^\d*\.?\d{0,2}$/.test(value)) {
        input.value = input.dataset.lastValid || ""; // Revert to last valid value
        return;
    }

    input.dataset.lastValid = value; // Store last valid value

    let parts = value.split(".");
    let integerPart = parts[0].replace(/[^\d]/g, '');
    let decimalPart = parts[1] ? "." + parts[1].slice(0, 2) : ""; // Limit to 2 decimals

    input.value = formatIndianNumber(integerPart) + decimalPart;
}  

 function formatIndianNumber(number) {
        let len = number.length;
        if (len <= 3) {
            return number;
        } else if (len > 3 && len <= 5) {
            return number.slice(0, len - 3) + ',' + number.slice(len - 3);
        } else {
            let lastThree = number.slice(-3);
            let remaining = number.slice(0, len - 3);
            remaining = remaining.replace(/\B(?=(\d{2})+(?!\d))/g, ",");
            return remaining + ',' + lastThree;
        }
    }

 function clsAlphaNoOnly (e) {  // Accept only alpha numerics, no special characters 
       var regex = new RegExp("^[a-zA-Z0-9]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    }

  
