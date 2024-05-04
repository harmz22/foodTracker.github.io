/* Set the width of the side navigation to 250px */
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
  }
  
  /* Set the width of the side navigation to 0 */
  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
  }

// Function to load content dynamically using AJAX
function loadContent(containerScript) {
    $.ajax({
        url: containerScript,
        method: 'GET',
        success: function(response) {
            // Replace existing content with the new content
            $('.container').html(response);
        }
    });
}


// Display current date and time
function updateDateTime() {
    var now = new Date();
    var dateTimeString = now.toLocaleString();
    $('#currentDateTime').text(dateTimeString);
}

// Initial update of date and time
updateDateTime();

// Update date and time every second
setInterval(updateDateTime, 1000);