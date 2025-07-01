document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.getElementById('menu-toggle'); // Get the hamburger menu
  const sideMenu = document.getElementById('side-menu'); // Get the side menu
  const body = document.body; // Get the body element for shifting content

  if (menuToggle && sideMenu) {  
    menuToggle.addEventListener('click', function () {
      sideMenu.classList.toggle('open'); // Toggle the 'open' class to slide the menu in/out
      body.classList.toggle('menu-open'); // Shift body content when menu is open

      // Toggle the hamburger icon to 'X' when menu is open
      if (sideMenu.classList.contains('open')) {
        menuToggle.textContent = '×'; // Change to 'X'
      } else {
        menuToggle.textContent = '☰'; // Change back to hamburger symbol
      }
    });
  } else {
    console.error("Menu or Side Menu not found.");
  }
});
