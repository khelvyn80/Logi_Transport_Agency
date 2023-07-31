 // JavaScript to toggle password visibility
 function togglePW(inputName) {
  var password = document.querySelector(`[name=${inputName}]`);

  if (password.getAttribute('type') === 'password') {
      password.setAttribute('type', 'text');
  } else {
      password.setAttribute('type', 'password');
  }
}
