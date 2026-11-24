const popup = document.getElementById('popup'); 
const overlay = document.getElementById('overlay');
const showPopupBtns = document.querySelectorAll('.show-popup'); // All buttons
const closePopupBtn = document.getElementById('close-popup');

// Show the pop-up for all buttons
showPopupBtns.forEach((button) => {
    button.addEventListener('click', () => {
        popup.style.display = 'block';
        overlay.style.display = 'block';
    });
});

// Close the pop-up
closePopupBtn.addEventListener('click', () => {
    popup.style.display = 'none';
    overlay.style.display = 'none';
});

// Close the pop-up when clicking on the overlay
overlay.addEventListener('click', () => {
    popup.style.display = 'none';
    overlay.style.display = 'none';
});



const matriculeInput = document.getElementById('matricule-input');

showPopupBtns.forEach((button) => {
    button.addEventListener('click', function() {
        const matricule = this.getAttribute('data-matricule'); // Get the matricule from data-matricule
        matriculeInput.value = matricule; // Set the value of the hidden input
        document.getElementById('popup').style.display = 'block'; // Show the popup
        document.getElementById('overlay').style.display = 'block'; // Show the overlay
    });
});

// Close the popup
document.getElementById('close-popup').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
});