// Get elements
const headerInput = document.getElementById('header-input');
const sections = document.getElementById('sections');
const logoLeft = document.getElementById('logo-left');
const logoRight = document.getElementById('logo-right');
const uploaderLeft = document.getElementById('uploader-left');
const uploaderRight = document.getElementById('uploader-right');

// Event listeners
headerInput.addEventListener('input', updateHeader);
logoLeft.addEventListener('change', updateLogo);
logoRight.addEventListener('change', updateLogo);
uploaderLeft.addEventListener('change', updateUploader);
uploaderRight.addEventListener('change', updateUploader);
sections.addEventListener('click', clearSection);

// Functions
function updateHeader() {
  const header = document.getElementById('header');
  header.textContent = headerInput.value;
}

function updateLogo(event) {
  const file = event.target.files[0];
  const reader = new FileReader();
  reader.onload = (e) => {
    if (event.target.id === 'logo-left') {
      document.getElementById('logo-left').style.backgroundImage = `url(${e.target.result})`;
    } else if (event.target.id === 'logo-right') {
      document.getElementById('logo-right').style.backgroundImage = `url(${e.target.result})`;
    }
  };
  reader.readAsDataURL(file);
}

function updateUploader(event) {
  const file = event.target.files[0];
  const reader = new FileReader();
  reader.onload = (e) => {
    if (event.target.id === 'uploader-left') {
      document.getElementById('logo-left').style.backgroundImage = `url(${e.target.result})`;
    } else if (event.target.id === 'uploader-right') {
      document.getElementById('logo-right').style.backgroundImage = `url(${e.target.result})`;
    }
  };
  reader.readAsDataURL(file);
}

function clearSection(event) {
  if (event.target.classList.contains('clear-section')) {
    event.target.parentNode.querySelector('.username-input').value = '';
    event.target.parentNode.querySelector('.password-input').value = '';
    event.target.style.display = 'none';
  }
}

// Brand buttons
const buttons = document.querySelectorAll('.brand-button');
buttons.forEach((button) => {
  button.addEventListener('click', (event) => {
    const section = event.target.closest('.section');
    const usernameInput = section.querySelector('.username-input');
    const passwordInput = section.querySelector('.password-input');
    if (usernameInput.value === '' && passwordInput.value === '') {
      usernameInput.style.display = 'block';
      passwordInput.style.display = 'block';
      section.querySelector('.clear-section').style.display = 'block';
    }
  });
});
