
  document.addEventListener('DOMContentLoaded', function() {
    // Function to get the value of URL parameter
    function getParameterByName(name) {
      const url = new URL(window.location.href);
      return url.searchParams.get(name);
    }

    // Function to set the content based on the URL parameter
    function setContent(service) {
      const contentContainer = document.getElementById('content');

      if (service === 'uber') {
        contentContainer.textContent = 'WELCOME TO UBER';
      } else if (service === 'glovo') {
        contentContainer.textContent = 'WELCOME TO BOLT';
      } else if (service === 'stc') {
        contentContainer.textContent = 'WELCOME TO STC';
      } else if (service === 'metrobusgh') {
        contentContainer.textContent = 'WELCOME TO METRO BUS GH';
      }
      // Add more conditions for other services as needed
    }

    // Call the function when the page loads to set content based on the URL parameter
    const service = getParameterByName('service');
    setContent(service);

    // Get references to the elements for preventing same location selection and form validation
    const fromSelect = document.getElementById('fromSelect');
    const toSelect = document.getElementById('toSelect');
    const dateInput = document.getElementById('dateInput');
    const submitButton = document.getElementById('submitButton');

    // Add an event listener to the submit button for preventing same location selection and form validation
    submitButton.addEventListener('click', function(event) {
      // Check if all the fields are filled out
      if (fromSelect.value === "Traveling From" || toSelect.value === "Traveling To" || dateInput.value === "") {
        event.preventDefault(); // Prevent form submission
        alert('Please fill out all the form fields.');
        return;
      }

      // Check if the selected values in both dropdowns are the same
      if (fromSelect.value === toSelect.value) {
        event.preventDefault(); // Prevent form submission
        alert('Please select different locations for "Traveling From" and "Traveling To".');
        return;
      }

      // Check if the selected date is valid (from today to the future)
      const currentDate = new Date();
      const selectedDate = new Date(dateInput.value);
      if (selectedDate < currentDate) {
        event.preventDefault(); // Prevent form submission
        alert('Please select a date from today or the future.');
        return;
      }
    });
  });

