var form = document.getElementById('form');

function myFunction() {
    if (form.checkValidity()) {
        alert("Il n'y a plus de sandwichs.");
    }
}