// Get the modal
const modal = document.getElementById('myModal');
// Get the button that opens the modal
const btn = document.getElementById('myModalBtn');
// Get the button closeSearch element that closes the modal
const closeSearch = document.getElementsByClassName('closeSearch')[0];
// When the user clicks the button, open the modal
btn.onclick = function () {
    modal.style.display = 'block';
};
// When the user clicks on rechercher(x), close the modal
closeSearch.onclick = function () {
    modal.style.display = 'none';
};
// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
};
