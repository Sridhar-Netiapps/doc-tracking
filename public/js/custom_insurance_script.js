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

document.addEventListener("DOMContentLoaded", function () {

  let numberonlyInputs = document.querySelectorAll(".numbersonly");

    numberonlyInputs.forEach(function (input) {
      
        input.addEventListener("keypress", function (event) {
            numbersonly(event);
        });

 
    });

    let alphaInputs = document.querySelectorAll(".clsAlphaNoOnly");

    alphaInputs.forEach(function (input) {
      
       
        input.addEventListener("keypress", function (event) {
            clsAlphaNoOnly(event);
        });

    });

     let numberInputs = document.querySelectorAll(".number-with-format");

    numberInputs.forEach(function (input) {
      // Restrict input to numbers and a single decimal point
        input.addEventListener("keypress", function (event) {
           numbersonly(event);
        });

        // Format input on change
        input.addEventListener("input", function () {
            formatNumber(this);
        });
    });

    let datesInputs = document.querySelectorAll(".valid-date");

    datesInputs.forEach(function (input) {
      
       
        input.addEventListener("keypress", function (event) {
            event.preventDefault();
        });

    });

    document.querySelectorAll(".number-input").forEach(inputElement => {
      // Format and display existing value on load
      inputElement.value = transformation(inputElement.value);

      inputElement.addEventListener("input", function(event) {
        const cursorPosition = inputElement.selectionStart;

        // Remove commas and get raw value
        const rawValue = inputElement.value.replace(/,/g, "");
        const formattedValue = transformation(rawValue);

        // Update the input value with the formatted number
        inputElement.value = formattedValue;

        // Restore cursor position based on digits before the cursor
        const digitsBeforeCursor = rawValue.slice(0, cursorPosition).replace(/[^0-9]/g, "").length;
        let newCursorPosition = 0;
        let digitCount = 0;

        for (let i = 0; i < formattedValue.length; i++) {
          if (/\d/.test(formattedValue[i])) {
            digitCount++;
          }
          if (digitCount === digitsBeforeCursor) {
            newCursorPosition = i + 1;
            break;
          }
        }

        inputElement.setSelectionRange(newCursorPosition, newCursorPosition);
      });
    });

    function transformation(input) {
      input = input.replace(/,/g, ""); // Remove existing commas
      const lastThreeDigits = input.slice(-3); // Extract the last 3 digits
      const restOfTheNumber = input.slice(0, -3); // Extract the remaining part
      
      if (restOfTheNumber !== "") {
        return restOfTheNumber.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + lastThreeDigits;
      }
      return lastThreeDigits;
    }


     const scrollTopBtn = document.getElementById("scrollTopBtn");
    const scrollBottomBtn = document.getElementById("scrollBottomBtn");

    // Scroll to Top
    if (scrollTopBtn) {
        scrollTopBtn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    // Scroll to Bottom
    if (scrollBottomBtn) {
        scrollBottomBtn.addEventListener("click", function () {
            window.scrollTo({ top: document.body.scrollHeight, behavior: "smooth" });
        });
    }

    // Show/hide top button on scroll
    window.addEventListener("scroll", function () {
        if (scrollTopBtn) {
            if (window.scrollY > 200) {
                scrollTopBtn.style.display = "block";
            } else {
                scrollTopBtn.style.display = "none";
            }
        }
    });

    // Start with hidden top button
    if (scrollTopBtn) {
        scrollTopBtn.style.display = "none";
    }



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
 // alert("ll");
       var regex = new RegExp("^[a-zA-Z0-9 ,.-]+$");

        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }

        e.preventDefault();
        return false;
    }

 document.addEventListener("contextmenu", function(event) {
        event.preventDefault();
    });

    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && (event.key === "u" || event.key === "U" || 
                             // event.key === "i" || event.key === "I" || 
                              event.key === "j" || event.key === "J" || 
                              event.key === "s" || event.key === "S" || 
                              event.key === "h" || event.key === "H")) {
            event.preventDefault();
        }
    }); 

$(document).ready(function () {
  $(".select2").select2();
});  