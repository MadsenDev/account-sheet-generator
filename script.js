document.addEventListener('DOMContentLoaded', function() {
    // Get the logo elements and file input elements
    const logoLeft = document.querySelector('#logo-left');
    const logoRight = document.querySelector('#logo-right');
    const logoInputLeft = document.querySelector('#logo-input-left');
    const logoInputRight = document.querySelector('#logo-input-right');
    const printButton = document.querySelector('#print-button');
  
    const a4Content = document.querySelector('#a4-content');
    const clearSectionsButton = document.querySelector('#clear-sections');
  
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
  
    // Get the brand buttons
    const brandButtons = document.querySelectorAll('.brand-button');
  
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
  
        // Add a container for the input fields
        const container = document.createElement('div');
  container.style.display = 'flex';
  container.style.flexDirection = 'column';
  container.style.width = '80%';
  container.style.marginLeft = '50px';

  const emailLabel = document.createElement('label');
  emailLabel.textContent = 'E-mail:';
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
  passwordLabel.textContent = 'Password:';
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
  
    // Write the A4 area's content to the new window
    printWindow.document.write('<!DOCTYPE html><html><head><title>Printable Account Sheets</title><link rel="stylesheet" href="styles.css"></head><body>');
    printWindow.document.write(document.querySelector('#a4-area').outerHTML);
    printWindow.document.write('</body></html>');
  
    // Close the document and print the content
    printWindow.document.close();
    setTimeout(() => {
      printWindow.print();
      printWindow.close();
      convertTextToInputs();
    }, 500);
  });
  
  // Function to convert input fields to static text for printing
function convertInputsToText() {
    const inputs = a4Content.querySelectorAll('input');
    inputs.forEach(input => {
      const textNode = document.createElement('span');
      textNode.className = 'print-text';
      textNode.textContent = input.value;
      input.parentNode.replaceChild(textNode, input);
    });
  }
  
  // Function to convert static text back to input fields after printing
  function convertTextToInputs() {
    const textNodes = a4Content.querySelectorAll('.print-text');
    textNodes.forEach(textNode => {
      const input = document.createElement('input');
      input.type = textNode.previousSibling.type;
      input.value = textNode.textContent;
      textNode.parentNode.replaceChild(input, textNode);
    });
  }
  });  