// Get the modal
var modal = document.getElementById('myModal');

var content = document.getElementById('content');
// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal


btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
function closeModal() {

    var firstField = document.getElementById("first_name");
    firstField.value = "";
    var lastField = document.getElementById("last_name");
    lastField.value = "";
    var email = document.getElementById("email");
    email.value = "";
    var course = document.getElementById("course");
    course.value = "";
    var avatar = document.getElementById('av');
    avatar.src = "";
    modal.style.display = "none";

}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {

    if (event.target == modal) {

        modal.style.display = "none";
    }
}

function addHidden(theForm, key, value, type) {
    // Create a hidden input element, and append it to the form:
    var input = document.createElement('input');
    input.type = type;
    input.name = key;;
    input.value = value;
    theForm.appendChild(input);
}
