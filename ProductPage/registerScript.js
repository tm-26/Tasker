var firstPswd = document.getElementById("exampleDropdownFormPassword1");
var pswdRetype = document.getElementById("exampleDropdownFormPassword2");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

var pswdNotMatch = document.getElementById("pswdNotMatch");
var pswdMatch = document.getElementById("pswdMatch");


//will execute when user clicks on the respective password field
firstPswd.onfocus = function() {
    document.getElementById("pswdValidMessage").style.display = "block";
}
pswdRetype.onfocus = function() {
    document.getElementById("pswdCheckMessage").style.display = "block";
}

//will execute when user clicks out of respective password field
firstPswd.onblur = function() {
    document.getElementById("pswdValidMessage").style.display = "none";
}
pswdRetype.onblur = function() {
    document.getElementById("pswdCheckMessage").style.display = "none";
}

//while user is typing their password in the first password field, it is constantly checked whether they entered the correct characters
firstPswd.onkeyup = function() {
    var lowerCaseLetters = /[a-z]/g;
    if(firstPswd.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
    }
    else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }

    var upperCaseLetters = /[A-Z]/g;
    if(firstPswd.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
    } 
    else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    }

    var numbers = /[0-9]/g;
    if(firstPswd.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
    } 
    else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }

    if(firstPswd.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
    } 
    else {
        length.classList.remove("valid");
        length.classList.add("invalid");
    }
}

//while user is typing their password in the second password field, it is constantly checked whether the password being entered matches the first password
pswdRetype.onkeyup = function() {
    if(firstPswd.value == pswdRetype.value) {
        pswdNotMatch.style.display = "none";
        pswdMatch.style.display = "block";
    }
    else{
        pswdNotMatch.style.display = "block";
        pswdMatch.style.display = "none";
    }
}

//handles the ranges of dates the user can select for their dateOfBirth
function dateRange(){
    date = new Date();
    
    day = date.getDate();
    month = date.getMonth() + 1; //+1 since month starts from 0
    year = date.getFullYear() - 18;
    oldestYear = date.getFullYear() - 150;

    //if day and month are numbers less than 10, a 0 is added as a prefix to allow them to be placed in legalAge and oldestAgre
    //thus allowing the "max" date to be set

    if (day < 10){
        day = "0" + day;
    }
    if (month < 10){
        month = "0" + month;
    }            

    legalAge = year + "-" + month + "-" + day;
    oldestAge = oldestYear + "-" + month + "-" + day;

    //by setting max and min, user can enter any date provided they were born at least 18 years prior and at most 150 years prior to date of registration
    document.getElementById("birthDate").setAttribute("max", legalAge);
    document.getElementById("birthDate").setAttribute("min", oldestAge);
}