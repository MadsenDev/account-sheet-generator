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

        // Limit the number of sections to 4
        if (a4Content.childElementCount >= 4) {
          return;
        }
  
        // Create a new section with the brand logo
        const section = document.createElement('div');
        section.className = 'section';
  
        const brandLogo = document.createElement('img');
        brandLogo.src = this.querySelector('img').src;
        brandLogo.alt = this.querySelector('img').alt;
        brandLogo.className = 'section-logo';
  
        section.appendChild(brandLogo);
  
        // Add a container for the input fields
        if (this.id === 'PIN') {
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
        } else {
          // Add a title for the section. The title is the same as the brand name
          const sectionTitle = document.createElement('h2');
          sectionTitle.textContent = this.querySelector('img').alt;
          section.appendChild(sectionTitle);
          // Make the title appear at the top center of the section
          sectionTitle.style.position = 'absolute';
          sectionTitle.style.top = '0';
          sectionTitle.style.left = '50%';
          sectionTitle.style.transform = 'translateX(-50%)';
          // Make the title be all capital letters
          sectionTitle.style.textTransform = 'uppercase';
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
        }

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
      textNode.style.display = 'inline-block';
      textNode.style.width = '100px';
      textNode.style.position = 'relative';
      textNode.style.top = '10px';
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

  // Add a function to add a brand button permanently
  function addBrandButton(brandName, brandLogo) {
    const button = document.createElement('button');
    button.className = 'brand-button';
    button.id = brandName;
  
    const logo = document.createElement('img');
    logo.src = brandLogo;
    logo.alt = brandName;
  
    button.appendChild(logo);
    brandButtonsContainer.appendChild(button);
  }
  });  