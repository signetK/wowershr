<!DOCTYPE html>
<html>
<head>
    <title>WHR | Reservation Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="details.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<header>
    <img src="pictures/wowers.png" width="100" height="100">
    <div class="wowers-hotel-restaurant">WOWERS HOTEL & RESTAURANT</div>
    <div class="items">
        <span class="about-1">
            <a href="index.php" class="about_us_text">About</a>
        </span>
        <span class="rooms">
            <a href="rooms.html" class="rooms_text">Room</a>
        </span>
        <span class="dining">
            <a href="dining.html" class="dining_text">Dining</a>
        </span>
        <span class="reserve">
            <a href="reserve.php" class="reserve_text">Reserve</a>
        </span>
    </div>
</header>

<?php
    // include the database connection file
    include('connection.php');

    // Check if the database connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the reservationID is passed via GET request
    if (isset($_GET['reservationID'])) {
        $reservationID = $_GET['reservationID'];

        // query to get reservation details 
        $sql = "SELECT r.*, rt.roomType AS roomType, rt.roomPrice AS roomPrice FROM reservation r
                INNER JOIN room rt ON r.roomID = rt.roomID
                WHERE r.reservationID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $reservationID);
        $stmt->execute();
        $result = $stmt->get_result();

        //checks if any reservation details are found
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['name'];
            $roomType = $row['roomType'];
            $dateIN = $row['dateIN'];
            $dateOUT = $row['dateOUT'];
            $roomPrice = $row['roomPrice'];
            
            // Calculate number of days
            $dateINDate = new DateTime($dateIN);
            $dateOUTDate = new DateTime($dateOUT);
            $interval = $dateINDate->diff($dateOUTDate);
            $numDays = $interval->days;
            
            // Calculate total payment
            $totalPayment = $numDays * $roomPrice;
        } else {
            echo "<script>alert('No reservation found with ID: $reservationID');</script>";
            echo "<script>window.location.href = 'reserve.php';</script>";
            exit();
        }

        $stmt->close();
    } else {
        echo "<script>window.location.href = 'index.php';</script>";
    }

    $conn->close();
    ?>

<!-- Display Reservation Details -->
<?php if (isset($row)): ?>
    <form class="reservationForm">
        <div class="reservation-details">
            <h2>Reservation Details</h2>
            <p><strong>Reservation ID:</strong> <?php echo $reservationID; ?></p>
            <p><strong>Name:</strong> <?php echo $name; ?></p>
            <p><strong>Room Type:</strong> <?php echo $roomType; ?></p>
            <p><strong>Date In:</strong> <?php echo $dateIN; ?></p>
            <p><strong>Date Out:</strong> <?php echo $dateOUT; ?></p>
            <p><strong>Total Payment:</strong> <?php echo $totalPayment; ?></p>
        </div>
    </form>
<?php endif; ?>

<form>
    <!-- Update Reservation -->
    <div id="initialForm">
    <div class="modal-body">
        <input type="hidden" name="reservationID" id="updateId">
        <h4>Update Reservation?</h4>
        <input type="button" value="Update" class="update-btn" onclick="showUpdateForm()">
    </div>
</div>

<div id="updateForm" style="display: none;">
    <label for="roomID">Room Type</label>
    <select id="roomType" name="updateroomID" class="form-control" placeholder="Choose Room Type" required>
        <option value="" disabled selected>Choose Room Type</option>
        <option value="1">Single/Double Room</option>
        <option value="2">Deluxe Suite</option>
        <option value="3">Luxury Suite</option>
        <option value="4">Presidential Suite</option>
    </select>
    <label for="dateIN">Date In</label>
    <input type="date" name="dateIN" id="updatedateIN" class="form-control" required><br>
    <label for="dateOUT">Date Out</label>
    <input type="date" name="dateOUT" id="updatedateOUT" class="form-control" required> <br><br>
    <button type="button" onclick="updateReservation()" class="updateBtn">Update Reservation</button><br>
</div>

<script>
    // Show the update form and hide the initial form
    function showUpdateForm() {
        var urlParams = new URLSearchParams(window.location.search);
        var reservationID = urlParams.get('reservationID');
        document.getElementById("updateId").value = reservationID;
        document.getElementById("initialForm").style.display = "none";
        document.getElementById("updateForm").style.display = "block";
    }

    // Update reservation using AJAX request
    function updateReservation() {
        var reservationID = document.getElementById("updateId").value;
        var roomType = document.getElementById("roomType").value;
        var dateIN = document.getElementById("updatedateIN").value;
        var dateOUT = document.getElementById("updatedateOUT").value;

        $.ajax({
            url: 'update.php',
            type: 'POST',
            data: {
                updateReservation: 1,
                reservationID: reservationID,
                updateroomID: roomType,
                dateIN: dateIN,
                dateOUT: dateOUT
            },
            success: function(response) {
                window.location.href = 'reservation.php';
                alert("Reservation Updated successfully!");
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert("Failed to update reservation. Please try again.");
            }
        });
    }

    // Set the initial values and minimum dates for date input fields
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        var checkin = document.getElementById('updatedateIN');
        var checkout = document.getElementById('updatedateOUT');

        checkin.value = today;
        checkin.setAttribute('min', today);
        checkout.setAttribute('min', getNextDay(today));

        checkin.addEventListener('change', function() {
            var checkinDate = new Date(checkin.value);
            var minCheckoutDate = new Date(checkinDate);
            minCheckoutDate.setDate(minCheckoutDate.getDate() + 1);

            checkout.setAttribute('min', minCheckoutDate.toISOString().split('T')[0]);

            if (new Date(checkout.value) <= checkinDate) {
                checkout.value = minCheckoutDate.toISOString().split('T')[0];
            }
        });
    });

    // Helper function to get the next day
    function getNextDay(date) {
        var nextDay = new Date(date);
        nextDay.setDate(nextDay.getDate() + 1);
        return nextDay.toISOString().split('T')[0];
    }
</script>

    
    <!-- Cancel Reservation -->
    <form action="delete.php" method="POST">
        <div>
            <input type="hidden" name="deletereservationID" id="deletereservationID">
            <h4>Cancel Reservation?</h4>
        </div>
        <div>
            <button type="button" onclick="confirmDelete()" class="btn btn-primary" name="deleteData">Cancel</button>
        </div>
    </form>

    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var reservationID = urlParams.get('reservationID');
        document.getElementById("deletereservationID").value = reservationID;

        // Send AJAX request to delete the reservation
        function deleteReservation() {
            var reservationID = document.getElementById("deletereservationID").value;

            $.ajax({
                url: 'delete.php',
                type: 'POST',
                data: {
                    deleteData: 1,
                    deletereservationID: reservationID,
                },
                success: function(response) {
                    window.location.href = 'reservation.php';
                    alert("Reservation Cancelled successfully!");
                },
            });
        }

        // Confirm before deleting the reservation
        function confirmDelete() {
            var confirmation = confirm("Are you sure you want to cancel this reservation?");
            if (confirmation) {
                deleteReservation();
            }
        }
    </script>


<footer>
    <div class="wowershr-com">
        <p>wowershr.com</p>
    </div>
    <div class="contact-info-container">
        <span class="contact_us_text">Contact Us<br></span>
        <span class="contact_info">
            Pantropiko Island, Philippines <br>
            wowershr@gmail.com <br>
            +63 912 055 0056
        </span>
    </div>
</footer>
</body>
</html>