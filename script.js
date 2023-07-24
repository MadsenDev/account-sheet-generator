document.addEventListener('DOMContentLoaded', function() {

  var sectionLimit = 5;

  var headerText = document.querySelector('#header-text');
  const selectedUserType = getSelectedUserType();
  console.log("Selected user type:", selectedUserType);

  const logoLeft = document.querySelector('#logo-left');
  const logoRight = document.querySelector('#logo-right');
  const logoInputLeft = document.querySelector('#logo-input-left');
  const logoInputRight = document.querySelector('#logo-input-right');
  const printButton = document.querySelector('#print-button');

  const radioButtons = document.getElementsByName('user-type');
  const radioButtonsArray = Array.from(radioButtons);
  const a4Content = document.querySelector('#a4-content');
  const clearSectionsButton = document.querySelector('#clear-sections');
  const clearLogosButton = document.querySelector('#clear-logos');
  const clearWatermarkButton = document.querySelector('#clear-watermark');
  const rightSidebar = document.querySelector('#right-sidebar');
  const leftSidebar = document.querySelector('#left-sidebar');
  const brandButtonDiv = document.querySelector('#brand-buttons');
  const brandButtons = document.querySelectorAll('.brand-button');
  const selectElement = document.getElementById('language-select');

  var header = "Account Information";
  var eMailText = "E-mail:";
  var passwordText = "Password:";

  const a4Area = document.querySelector('#a4-content');

  const openOverlayButton = document.getElementById('open-overlay');
  const closeOverlayButton = document.getElementById('close-overlay');
  const overlay = document.getElementById('overlay');

  openOverlayButton.addEventListener('click', () => {
    overlay.style.display = 'block';
  });

  closeOverlayButton.addEventListener('click', () => {
    overlay.style.display = 'none';
  });

document.querySelector('#add-watermark-button').addEventListener('click', () => {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.onchange = () => {
    const file = input.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
      //a4Content.style.backgroundImage = `url(${reader.result})`;
      a4Content.style.backgroundRepeat = 'no-repeat';
      a4Content.style.backgroundPosition = 'center';
      a4Content.style.backgroundSize = 'fill';
      a4Content.classList.add('watermark');
    };
  };
  input.click();
});
  
    // Add event listeners to the file input elements
    logoInputLeft.addEventListener('change', function() {
      const file = logoInputLeft.files[0];
      if (file) {
        const reader = new FileReader();
        reader.addEventListener('load', function() {
          logoLeft.style.backgroundImage = `url(${reader.result})`;
        });
        reader.readAsDataURL(file);
      }
    });

    let selectedLanguage = '2'; // Set default language as English

    document.getElementById('language-select').addEventListener('change', function() {
      selectedLanguage = this.value;
      console.log(selectedLanguage);
    });
  
    // Add event listeners to the file input elements
    logoInputRight.addEventListener('change', function() {
      const file = logoInputRight.files[0];
      if (file) {
        const reader = new FileReader();
        reader.addEventListener('load', function() {
          logoRight.style.backgroundImage = `url(${reader.result})`;
        });
        reader.readAsDataURL(file);
      }
    });

    // Add event listeners to the radio buttons
radioButtonsArray.forEach(button => {
  button.addEventListener('click', function() {
    // Get the selected user type
const selectedUserType = getSelectedUserType();

// Fetch user type info
fetch(`get_user_type_info.php?id=${selectedUserType}`)
    .then(response => response.json())
    .then(data => {
        const { title, logo_left, logo_right, watermark, language_id, brands } = data;
        header = title;
        headerText.textContent = header;
        selectedLanguage = language_id;
        selectElement.value = language_id;

        if (logo_left) {
            logoLeft.style.backgroundImage = `url(${logo_left})`;
        } else {
            logoLeft.style.backgroundImage = '';
        }

        if (logo_right) {
            logoRight.style.backgroundImage = `url(${logo_right})`;
        } else {
            logoRight.style.backgroundImage = '';
        }

        if (watermark) {
            //a4Content.style.backgroundImage = `url(${watermark})`;
            a4Content.style.backgroundRepeat = 'no-repeat';
            a4Content.style.backgroundPosition = 'center';
            a4Content.style.backgroundSize = 'fill';
            a4Content.classList.add('watermark');
        } else {
            a4Content.style.backgroundImage = '';
            a4Content.classList.remove('watermark');
        }

        // Make all buttons visible or not based on whether the brands array is empty or not
        const displayStyle = brands.length > 0 ? 'block' : 'none';
        brandButtons.forEach(button => {
            button.style.display = displayStyle;
        });
    })
    .catch(error => console.error('Error:', error));


    // Using Fetch API with GET method
    fetch(`fetch_brands.php?user_type=${selectedUserType}`)
      .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(brandsList => {
        // Check brandButtons for brands from brandsList. Hide the ones that are not in the list
        brandButtons.forEach(button => {
            if (brandsList.includes(button.id)) {
                button.style.display = 'block';
            } else {
                button.style.display = 'none';
            }
        });
      })
      .catch(e => {
        console.log('There was a problem with your fetch operation: ' + e.message);
      });
  });
});

    // Get brand info from the database
    async function fetchInfo(id) {
      const response = await fetch(`https://accountsheet.madsens.dev/fetch_info.php?id=${id}`);
      const data = await response.json();
      return data.info;
    }    
  
    brandButtons.forEach(button => {
      button.addEventListener('click', function(event) {
          event.preventDefault();

          console.log(button.dataset.id);

          const brandId = button.dataset.id;
  
          // Limit the number of sections
          if (a4Content.childElementCount >= sectionLimit) {
              return;
          }
  
          // Create a new section with the brand logo
          const section = document.createElement('div');
          section.className = 'section';
          section.style.width = '100%';
  
          const brandLogo = document.createElement('img');
          brandLogo.src = this.querySelector('img').src;
          brandLogo.alt = this.querySelector('img').alt;
          brandLogo.className = 'section-logo';
  
          section.appendChild(brandLogo);
  
          // Add a title for the section. The title is the same as the brand name
          const sectionTitle = document.createElement('h2');
          sectionTitle.textContent = this.querySelector('img').alt;
  
          // If you have specific title replacements based on user type or brand, you can still do that here.
          if (getSelectedUserType() === '3') {
              if (sectionTitle.textContent === 'Jotta') {
                  sectionTitle.textContent = 'Elkjøp Cloud';
              }
          }
  
          section.appendChild(sectionTitle);
          // Make the title appear at the top center of the section
        sectionTitle.style.position = 'absolute';
        sectionTitle.style.top = '0';
        sectionTitle.style.left = '15%';
        sectionTitle.style.transform = 'translateX(-50%)';
        sectionTitle.style.fontSize = '20px';

          console.log(brandId);
  
  
                  // Fetch fields and labels for this brand from your PHP script
                  fetch(`fetch_fields.php?brand_id=${brandId}&selectedLanguage=${selectedLanguage}`)
.then(response => response.json())
.then(data => {
    // Container for input fields
    const container = document.createElement('div');
    container.style.display = 'flex';
    container.style.flexDirection = 'column';
    container.style.width = '80%';
    container.style.marginLeft = '20px';

    // Loop through each field and create inputs
    data.forEach(field => {
        const fieldLabel = document.createElement('label');
        fieldLabel.textContent = field.label + ':';
        fieldLabel.style.display = 'inline-block';
        fieldLabel.style.width = '100px';

        const fieldInput = document.createElement('input');
        fieldInput.type = field.type;
        fieldInput.style.display = 'inline-block';
        fieldInput.style.marginLeft = '10px';

        // Create a div to group the label and input, and style it
        const fieldDiv = document.createElement('div');
        fieldDiv.style.display = 'flex';

        // Append label and input to the div
        fieldDiv.appendChild(fieldLabel);
        fieldDiv.appendChild(fieldInput);

        // Append the div to the container
        container.appendChild(fieldDiv);
    });

    // Append container to section
    section.appendChild(container);

    // Append section to your main content area
    a4Content.appendChild(section);

  
                  // Append container to section
                  section.appendChild(container);
  
                  // Append section to your main content area
                  a4Content.appendChild(section);
              })
              .catch(error => {
                  console.error('There has been a problem with your fetch operation:', error);
              });
      });
  });  
  
    // Add event listener to the clear sections button
    clearSectionsButton.addEventListener('click', function() {
      a4Content.innerHTML = '';
    });

    clearLogosButton.addEventListener('click', function() {
      logoLeft.style.backgroundImage = '';
      logoRight.style.backgroundImage = '';
    });

    clearWatermarkButton.addEventListener('click', function() {
      a4Content.style.backgroundImage = '';
    });

    // Add an event listener to the print button
printButton.addEventListener('click', function() {
  convertInputsToText();

  // Create a new window
  const printWindow = window.open('', '_blank');

  headerText.textContent = header;

  // Write the A4 area's content to the new window
  printWindow.document.write('<!DOCTYPE html><html><head><title>Printable Account Sheets</title><link rel="stylesheet" href="styles.css"></head><body>');
  printWindow.document.write(document.querySelector('#a4-area').innerHTML);
  printWindow.document.write('</body></html>');

  // Close the document and print the content
  printWindow.document.close();
  setTimeout(() => {
    printWindow.print();
    printWindow.close();
    convertTextToInputs();
  }, 500);

  // Listen for the onbeforeunload event
  printWindow.onbeforeunload = function() {
    // Clear sections when the print window is closed
    a4Content.innerHTML = '';

    // Make an AJAX request to log_event.php
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "log_event.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("event=Printable Account Sheets printed");
  };
});
  
  // Function to convert input fields to static text for printing
  function convertInputsToText() {
    const inputs = a4Content.querySelectorAll('input');
    inputs.forEach(input => {
      const container = document.createElement('div');
      container.style.width = input.offsetWidth + 'px';
      container.style.overflow = 'hidden';
      container.style.display = 'inline-block';
      
      const textNode = document.createElement('span');
      textNode.className = 'print-text';
      textNode.textContent = input.value;
      textNode.style.position = 'relative';
      textNode.style.top = '10px';
      
      container.appendChild(textNode);
      input.parentNode.replaceChild(container, input);
    });
  }  
  
  // Function to convert static text back to input fields after printing
  function convertTextToInputs() {
    const containers = a4Content.querySelectorAll('.input-container');
    containers.forEach(container => {
      const textNode = container.querySelector('.print-text');
      if (textNode) {
        const input = document.createElement('input');
        input.type = 'text';
        input.value = textNode.textContent;
        input.style.width = '100%';
        input.style.boxSizing = 'border-box';
        container.parentNode.replaceChild(input, container);
      }
    });
  }  

  function getSelectedUserType() {
    const radioButtons = document.getElementsByName("user-type");
    let selectedValue;
  
    for (let i = 0; i < radioButtons.length; i++) {
      if (radioButtons[i].checked) {
        selectedValue = radioButtons[i].value;
        break;
      }
    }
  
    return selectedValue;
}

// Get the search input element
const searchInput = document.getElementById("search-input");

// Add an event listener to the search input to filter the list
searchInput.addEventListener("input", () => {
    // Get the value of the search input
    const searchTerm = searchInput.value.toLowerCase();

    // Get all the brand buttons
    const brandButtons = document.querySelectorAll(".brand-button");

    // Loop through the brand buttons and show/hide them based on the search term
    brandButtons.forEach(brandButton => {
        const brandName = brandButton.textContent.toLowerCase();
        if (brandName.includes(searchTerm)) {
            brandButton.style.display = "block";
        } else {
            brandButton.style.display = "none";
        }
    });
});

// Add an event listener to the search form to call the search function
document.querySelector('#search-form').addEventListener('submit', handleSearchFormSubmit);


let fieldCount = 0;

function addField() {
const container = document.getElementById('fields-container');

const field = document.createElement('div');
field.classList.add('field');

const label = document.createElement('label');
label.textContent = 'Label:';
field.appendChild(label);

const labelInput = document.createElement('input');
labelInput.type = 'text';
labelInput.name = `fields[${fieldCount}][label]`;
field.appendChild(labelInput);

const typeLabel = document.createElement('label');
typeLabel.textContent = 'Type:';
field.appendChild(typeLabel);

const typeSelect = document.createElement('select');
typeSelect.name = `fields[${fieldCount}][type]`;

const textOption = document.createElement('option');
textOption.value = 'text';
textOption.textContent = 'Text';
typeSelect.appendChild(textOption);

const numberOption = document.createElement('option');
numberOption.value = 'number';
numberOption.textContent = 'Number';
typeSelect.appendChild(numberOption);

field.appendChild(typeSelect);

container.appendChild(field);

fieldCount++;
}

function submitForm() {
  const name = document.getElementById('name').value;
  const logo = document.getElementById('logo').files[0];
  const fields = [];

  const fieldElements = document.querySelectorAll('#fields-container .field');
  fieldElements.forEach((fieldElement) => {
    const label = fieldElement.querySelector('input[name$="[label]"]').value;
    const type = fieldElement.querySelector('select[name$="[type]"]').value;
    fields.push({ label, type });
  });

  // Create a new section with the brand logo
  const section = document.createElement('div');
  section.className = 'section';
  section.style.width = '100%';

  const brandLogo = document.createElement('img');
  brandLogo.src = URL.createObjectURL(logo);
  brandLogo.alt = name;
  brandLogo.className = 'section-logo';

  section.appendChild(brandLogo);

  // Add a title for the section. The title is the same as the brand name
  const sectionTitle = document.createElement('h2');
  sectionTitle.textContent = name;

  fetch('/fields')
    .then(response => response.json())
    .then(data => {
      // Container for input fields
      const container = document.createElement('div');
      container.style.display = 'flex';
      container.style.flexDirection = 'column';
      container.style.width = '80%';
      container.style.marginLeft = '20px';

      // Loop through each field and create inputs
      fields.forEach((field, index) => {
        const fieldLabel = document.createElement('label');
        fieldLabel.textContent = field.label + ':';
        fieldLabel.style.display = 'inline-block';
        fieldLabel.style.width = '100px';

        const fieldInput = document.createElement('input');
        fieldInput.type = field.type;
        fieldInput.style.display = 'inline-block';
        fieldInput.style.marginLeft = '10px';
        fieldInput.name = `fields[${index}][value]`;

        // Create a div to group the label and input, and style it
        const fieldDiv = document.createElement('div');
        fieldDiv.style.display = 'flex';

        // Append label and input to the div
        fieldDiv.appendChild(fieldLabel);
        fieldDiv.appendChild(fieldInput);

        // Append the div to the container
        container.appendChild(fieldDiv);
      });

      // Append container to section
      section.appendChild(container);

      // Append section to your main content area
      a4Content.appendChild(section);
    });
}
});