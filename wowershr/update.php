<?php
// establish connection to the database
include('connection.php');

// Check if the updateReservation form data has been posted
if (isset($_POST['updateReservation'])) {
    $reservationID = $_POST['reservationID'];
    $roomID= $_POST['updateroomID'];
    $dateIN = $_POST['dateIN'];
    $dateOUT = $_POST['dateOUT'];

    // create sql query to update the database
    $stmt = $conn->prepare("UPDATE reservation SET roomID = ?, dateIN = ?, dateOUT = ? WHERE reservationID = ?");
    $stmt->bind_param("isss", $roomID, $dateIN, $dateOUT, $reservationID);

    // Execute the prepared statement and check if the execution was successful
    if ($stmt->execute()) {
         // If the update is successful, alert the user and redirect to the index page
        echo '<script>alert("Reservation updated successfully.");</script>';
        header('Location: index.php');
        exit; // Terminate the script after redirection to ensure the rest of the code does not execute
    } else {
        echo '<script>alert("Reservation update failed.");</script>';
    }

    // close the prepared statement
    $stmt->close();
}
?>