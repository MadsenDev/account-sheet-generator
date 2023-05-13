document.addEventListener('DOMContentLoaded', function() {

  var sectionLimit = 5;

  var headerText = document.querySelector('#header-text');
  const selectedUserType = getSelectedUserType();
  console.log("Selected user type:", selectedUserType);
    // Get the logo elements and file input elements
    const logoLeft = document.querySelector('#logo-left');
    const logoRight = document.querySelector('#logo-right');
    const logoInputLeft = document.querySelector('#logo-input-left');
    const logoInputRight = document.querySelector('#logo-input-right');
    const printButton = document.querySelector('#print-button');

    // Get radio buttons from user-input
    const radioButtons = document.getElementsByName('user-type');
    const radioButtonsArray = Array.from(radioButtons);
    // Fetch #a4-content
    const a4Content = document.querySelector('#a4-content');
    const clearSectionsButton = document.querySelector('#clear-sections');
    // Fetch #right-sidebar
    const rightSidebar = document.querySelector('#right-sidebar');
    // Fetch #left-sidebar
    const leftSidebar = document.querySelector('#left-sidebar');
    // Fetch #brand-buttons
    const brandButtonDiv = document.querySelector('#brand-buttons');
    // Get the brand buttons
    const brandButtons = document.querySelectorAll('.brand-button');

    // Strings for the header and input fields
    var header = "Account Information";
    var eMailText = "E-mail:";
    var passwordText = "Password:";
  
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

    if (selectedUserType === '2') {
      header = "Account Information";
      headerText.textContent = header;
      // Remove logo backgrounds
      logoRight.style.backgroundImage = '';
      logoLeft.style.backgroundImage = '';
      eMailText = "E-mail:";
      passwordText = "Password:";
      // Make all buttons visible
      brandButtons.forEach(button => {
        button.style.display = 'block';
      });
    } else if (selectedUserType === '3') {
      header = "Kontoinformasjon";
      headerText.textContent = header;
      eMailText = "E-post:";
      passwordText = "Passord:";
      logoLeft.style.backgroundImage = `url(images/supportplus.png)`;
      logoRight.style.backgroundImage = `url(images/elkjop-logo.png)`;
    }

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
  
    // Add event listeners to the brand buttons
    brandButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        event.preventDefault();

        // Get the category ID
        const categoryId = this.dataset.category;

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
  
        // Add a container for the input fields
        if (categoryId === '9') {
          const container = document.createElement('div');
          container.style.display = 'flex';
          container.style.flexDirection = 'column';
          container.style.width = '80%';
          container.style.marginLeft = '50px';
  
          const pinLabel = document.createElement('label');
          pinLabel.textContent = 'PIN:';
          pinLabel.style.display = 'inline-block';
          pinLabel.style.width = '100px';
  
          const pinInput = document.createElement('input');
          pinInput.type = 'text';
          pinInput.style.display = 'inline-block';
          pinInput.style.marginLeft = '10px';
  
          const pinDiv = document.createElement('div');
          pinDiv.style.display = 'flex';
          pinDiv.appendChild(pinLabel);
          pinDiv.appendChild(pinInput);
  
          container.appendChild(pinDiv);
  
          section.appendChild(container);
          a4Content.appendChild(section);

        } else if (categoryId === '10') {
          const container = document.createElement('div');
          container.style.display = 'flex';
          container.style.flexDirection = 'column';
          container.style.width = '80%';
          container.style.marginLeft = '50px';
  
          const pinLabel = document.createElement('label');
          pinLabel.textContent = 'License:';
          pinLabel.style.display = 'inline-block';
          pinLabel.style.width = '100px';
  
          const pinInput = document.createElement('input');
          pinInput.type = 'text';
          pinInput.style.display = 'inline-block';
          pinInput.style.marginLeft = '10px';
  
          const pinDiv = document.createElement('div');
          pinDiv.style.display = 'flex';
          pinDiv.appendChild(pinLabel);
          pinDiv.appendChild(pinInput);
  
          container.appendChild(pinDiv);
  
          section.appendChild(container);
          a4Content.appendChild(section);
        } else {
          const container = document.createElement('div');
          container.style.display = 'flex';
          container.style.flexDirection = 'column';
          container.style.width = '80%';
          container.style.marginLeft = '50px';
  
          const emailLabel = document.createElement('label');
          emailLabel.textContent = eMailText;
          emailLabel.style.display = 'inline-block';
          emailLabel.style.width = '100px';
  
          const emailInput = document.createElement('input');
          emailInput.type = 'email';
          emailInput.style.display = 'inline-block';
          emailInput.style.marginLeft = '10px';
  
          const emailDiv = document.createElement('div');
          emailDiv.style.display = 'flex';
          emailDiv.appendChild(emailLabel);
          emailDiv.appendChild(emailInput);
  
          const passwordLabel = document.createElement('label');
          passwordLabel.textContent = passwordText;
          passwordLabel.style.display = 'inline-block';
          passwordLabel.style.width = '100px';
  
          const passwordInput = document.createElement('input');
          passwordInput.type = 'password';
          passwordInput.style.display = 'inline-block';
          passwordInput.style.marginLeft = '10px';
  
          const passwordDiv = document.createElement('div');
          passwordDiv.style.display = 'flex';
          passwordDiv.appendChild(passwordLabel);
          passwordDiv.appendChild(passwordInput);
  
          container.appendChild(emailDiv);
          container.appendChild(passwordDiv);
  
          section.appendChild(container);
          a4Content.appendChild(section);
        }
      });
    });
  
    // Add event listener to the clear sections button
    clearSectionsButton.addEventListener('click', function() {
      a4Content.innerHTML = '';
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



});