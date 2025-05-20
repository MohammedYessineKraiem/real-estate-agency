// js/admin.js

// Highlight current link based on page URL
const links = document.querySelectorAll('.sidebar a');
const currentPath = window.location.pathname.split("/").pop();
links.forEach(link => {
  if (link.getAttribute('href') === currentPath) {
    link.classList.add('active');
  }
});

// Logout function
function logout() {
  const confirmLogout = confirm("Are you sure you want to log out?");
  if (confirmLogout) {
    window.location.href = "LoginAdmin.html";
  }
}

// Friendly greeting (time-based)
const content = document.querySelector('.content');
const now = new Date().getHours();
let greeting = 'Hello';

if (now < 12) greeting = 'Good morning';
else if (now < 18) greeting = 'Good afternoon';
else greeting = 'Good evening';

const heading = document.createElement('h2');
heading.textContent = `${greeting}, Admin 👋`;
content.prepend(heading);

document.addEventListener('DOMContentLoaded', function () {
    // Get the logout button
    const logoutBtn = document.querySelector('.logout-btn');
    
    // Add click event listener to the logout button
    logoutBtn.addEventListener('click', function (e) {
      // Prevent default action (avoid immediate redirect)
      e.preventDefault();
      
      // Show confirmation dialog
      const confirmLogout = confirm("Êtes-vous sûr de vouloir vous déconnecter?");
      
      // If the user confirms, redirect to the login page
      if (confirmLogout) {
        window.location.href = 'LoginAdmin.html';  // Redirect to login page
      }
      // If the user cancels, no action is taken, and they stay on the page
    });
  });

  // admin.js

// Validate password change form
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // Clear previous errors
    document.querySelectorAll('.error-message').forEach((el) => el.style.display = 'none');

    let isValid = true;

    // Get form values
    const oldPassword = document.getElementById('oldPassword').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    // Validate old password
    if (!oldPassword) {
        showError('oldPasswordError', 'L\'ancien mot de passe est requis.');
        isValid = false;
    }

    // Validate new password
    if (!newPassword) {
        showError('newPasswordError', 'Le nouveau mot de passe est requis.');
        isValid = false;
    } else if (newPassword.length < 6) {
        showError('newPasswordError', 'Le mot de passe doit comporter au moins 6 caractères.');
        isValid = false;
    }

    // Validate confirm password
    if (!confirmPassword) {
        showError('confirmPasswordError', 'La confirmation du mot de passe est requise.');
        isValid = false;
    } else if (newPassword !== confirmPassword) {
        showError('confirmPasswordError', 'Les mots de passe ne correspondent pas.');
        isValid = false;
    }

    // If form is valid, show success message
    if (isValid) {
        alert("Mot de passe modifié avec succès!");
        document.getElementById('changePasswordForm').reset();
    }
});

// Function to display error message
function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

// Logout confirmation
function logoutConfirmation() {
    const confirmLogout = confirm("Êtes-vous sûr de vouloir vous déconnecter ?");
    if (confirmLogout) {
        window.location.href = 'LoginAdmin.html';
    }
}
