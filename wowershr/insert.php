<?php
// Include the connection file to establish a connection to the database
include('connection.php');

// Check if the form data with the name 'addReservation' has been submitted via a POST request
if (isset($_POST['addReservation'])) {
    // Retrieve form data from the POST request and store it in variables
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $roomID = $_POST['roomID'];
    $dateIN = $_POST['dateIN'];
    $dateOUT = $_POST['dateOUT'];

    // Prepare an SQL statement to insert the reservation data into the database
    $stmt = $conn->prepare("INSERT INTO reservation (name, email, contactNumber, roomID, dateIN, dateOUT) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $contactNumber, $roomID, $dateIN, $dateOUT);

    // Execute the prepared statement and check if it was successful
    if ($stmt->execute()) {
        // Get the last inserted ID
        $reservationID = $conn->insert_id;
        // Redirect to the index page with the reservation ID in the query string
        header('Location: reserve.php?reservationID=' . $reservationID);
        // Stop further script execution to ensure the redirect happens immediately
        exit;
    } else {
        // If the execution failed, display a JavaScript alert indicating failure
        echo '<script>alert("Reservation failed to save.");</script>';
    }

    // Close the prepared statement to free up resources
    $stmt->close();
}
?>