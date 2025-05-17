<!--Delete Function-->
<?php
// Include the connection file to connect to the database
include('connection.php');

// Check if the deleteData form data has been posted
if (isset($_POST['deleteData'])) {
    // Retrieve the reservation ID to be deleted from the form data
    $reservationID = $_POST['deletereservationID'];

    // Create the SQL DELETE query to remove the reservation with the ID
    $sql = "DELETE FROM reservation WHERE reservationID='$reservationID'"; 
    // Execute the query and store the result
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
        // If successful, show a JavaScript alert indicating success
        echo '<script> alert("Data Deleted."); </script>';
        
        // Redirect the user back to the index page
        header('Location: index.php');
        
        // Stop further script execution after the header redirection
        exit; // Added exit after header to prevent further execution
    } else {
        // If the query failed, show a JavaScript alert indicating failure
        echo '<script> alert("Data Not Deleted."); </script>';
    }
}
?>
