"use strict";

function validateForm(form) {
  var isValid = true;
  var inputs = form.querySelectorAll('input, textarea, select, file');
  inputs.forEach(function (input) {
    if (!validateInput(input)) {
      isValid = false;
    }
  });
  return isValid;
}

function validateInput(input) {
  var isValid = true;
  var errorElement = input.nextElementSibling; // Required check

  if (input.required && input.value.trim() === '') {
    isValid = false;
    input.classList.add('is-invalid');
    showError(input, "".concat(getFieldName(input), " is required."));
  } else {
    input.classList.remove('is-invalid');

    if (errorElement && errorElement.classList.contains('invalid-feedback')) {
      errorElement.textContent = '';
    }
  } // Length constraints (use minlength & maxlength for text fields)


  if (input.value.trim() !== '') {
    var minLength = input.getAttribute('minlength');
    var maxLength = input.getAttribute('maxlength');

    if (minLength && input.value.length < parseInt(minLength)) {
      isValid = false;
      input.classList.add('is-invalid');
      showError(input, "".concat(getFieldName(input), " must be at least ").concat(minLength, " characters long."));
    }

    if (maxLength && input.value.length > parseInt(maxLength)) {
      isValid = false;
      input.classList.add('is-invalid');
      showError(input, "".concat(getFieldName(input), " cannot exceed ").concat(maxLength, " characters."));
    }
  } // Email validation


  if (input.type === 'email' && input.value.trim() !== '' && !validateEmail(input.value)) {
    isValid = false;
    input.classList.add('is-invalid');
    showError(input, 'Please enter a valid email address.');
  } // Number validation


  if (input.type === 'number' && input.value !== '' && input.value < 0) {
    isValid = false;
    input.classList.add('is-invalid');
    showError(input, "".concat(getFieldName(input), " cannot be negative."));
  } // Confirm password


  if (input.id === 'confirm_password') {
    var password = document.getElementById('password').value;

    if (input.value !== password) {
      isValid = false;
      input.classList.add('is-invalid');
      showError(input, 'Passwords do not match.');
    } else {
      input.classList.remove('is-invalid');

      if (errorElement && errorElement.classList.contains('invalid-feedback')) {
        errorElement.textContent = '';
      }
    }
  }

  return isValid;
}

function validateEmail(email) {
  var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function getFieldName(input) {
  var name = input.name.replace(/\[.*?\]/g, '').replace(/[_-]/g, ' ');
  return name.split(' ').map(function (word) {
    return word.charAt(0).toUpperCase() + word.slice(1);
  }).join(' ');
}

function showError(input, message) {
  var errorElement = input.nextElementSibling;

  if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
    errorElement = document.createElement('div');
    errorElement.className = 'invalid-feedback';
    input.parentNode.insertBefore(errorElement, input.nextSibling);
  }

  errorElement.textContent = message;
  errorElement.setAttribute('role', 'alert');
}

function submitFormViaAjax(form) {
  var formData = new FormData(form);
  var action = form.getAttribute('action');
  var method = form.getAttribute('method') || 'POST';
  fetch(action, {
    method: method,
    body: formData
  }).then(function (response) {
    return response.json();
  }).then(function (data) {
    console.log('Success:', data);
  })["catch"](function (error) {
    console.log('Error:', error);
  });
}