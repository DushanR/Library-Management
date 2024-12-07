<?php
// Registration logic here (e.g., checking if user exists, validating input)

$registration_successful = true; // This is just an example; change based on your actual logic

if ($registration_successful) {
    echo "<script type='text/javascript'>
            $(document).ready(function(){
                $('#registrationModal').modal('show'); // Show the modal
            });
          </script>";
} else {
    // Handle errors here
}
?>
