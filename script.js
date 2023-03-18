// script.js


document.addEventListener('DOMContentLoaded', function() {
    // Get the logo elements and file input elements
    const logoLeft = document.querySelector('#logo-left');
    const logoRight = document.querySelector('#logo-right');
    const logoInputLeft = document.querySelector('#logo-input-left');
    const logoInputRight = document.querySelector('#logo-input-right');

    const a4Content = document.querySelector('#a4-content');

    // Add event listeners to the file input elements
    logoInputLeft.addEventListener('change', function() {
    // Get the selected file
    const file = logoInputLeft.files[0];

    // Check if a file was selected
    if (file) {
        const reader = new FileReader();

        reader.addEventListener('load', function() {
        // Set the background image of the logo element to the selected file
        logoLeft.style.backgroundImage = `url(${reader.result})`;
        });

        // Read the selected file as a data URL
        reader.readAsDataURL(file);
    }
    });

    logoInputRight.addEventListener('change', function() {
    // Get the selected file
    const file = logoInputRight.files[0];

    // Check if a file was selected
    if (file) {
        const reader = new FileReader();

        reader.addEventListener('load', function() {
        // Set the background image of the logo element to the selected file
        logoRight.style.backgroundImage = `url(${reader.result})`;
        });

        // Read the selected file as a data URL
        reader.readAsDataURL(file);
    }
    });

    // Get the brand buttons and the clear sections button
    const brandButtons = document.querySelectorAll('.brand-button');
    const clearSectionsButton = document.querySelector('#clear-sections');

    // Add event listeners to the brand buttons
    brandButtons.forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        // Create a new section with the brand logo
        const section = document.createElement('div');
        section.className = 'section';

        const brandLogo = document.createElement('img');
        brandLogo.src = this.querySelector('img').src;
        brandLogo.alt = this.querySelector('img').alt;
        brandLogo.className = 'section-logo';

        section.appendChild(brandLogo);
        a4Content.appendChild(section);
    });
    });

    // Add event listener to the clear sections button
    clearSectionsButton.addEventListener('click', function() {
    a4Content.innerHTML = '';
    });
  });

