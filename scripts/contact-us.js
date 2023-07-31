document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contact-form');
  const successMessage = document.getElementById('success-message');

  form.addEventListener('submit', function(event) {
    event.preventDefault();

    form.reset();

    successMessage.style.display = 'block';
    
    setTimeout(function() {
      successMessage.style.display = 'none';
    }, 3000);
  });
});
